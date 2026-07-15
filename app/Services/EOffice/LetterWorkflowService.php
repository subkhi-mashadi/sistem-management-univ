<?php

namespace App\Services\EOffice;

use App\Enums\EOffice\ApprovalAction;
use App\Enums\EOffice\LetterRequestStatus;
use App\Models\Approval;
use App\Models\LetterRequest;
use App\Models\User;
use App\Models\WorkflowStep;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Spatie\Permission\Models\Role;

class LetterWorkflowService
{
    public function __construct(
        protected LetterNumberGenerator $numberGenerator = new LetterNumberGenerator(),
    ) {}

    public function submit(LetterRequest $request): LetterRequest
    {
        if ($request->status !== LetterRequestStatus::Draft) {
            throw new RuntimeException('Hanya pengajuan berstatus Draft yang bisa disubmit.');
        }

        return DB::transaction(function () use ($request) {
            $request = LetterRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();

            $template = $request->template;
            $workflow = $template?->defaultWorkflow;

            if (! $workflow) {
                throw new RuntimeException('Template surat belum punya workflow default.');
            }

            $request->workflow_id = $workflow->id;
            $request->letter_number = $this->numberGenerator->generate($request);
            $request->status = LetterRequestStatus::InProgress;
            $request->save();

            $firstStep = $workflow->steps()->orderBy('step_order')->first();

            if (! $firstStep) {
                throw new RuntimeException('Workflow belum punya step.');
            }

            $this->seedApprovalsForStep($request, $firstStep);
            $request->current_step_id = $firstStep->id;
            $request->save();

            return $request->fresh();
        });
    }

    public function approve(Approval $approval, User $actor, ?string $comments = null): LetterRequest
    {
        if ($approval->action !== ApprovalAction::Pending) {
            throw new RuntimeException('Approval ini sudah diproses.');
        }

        return DB::transaction(function () use ($approval, $actor, $comments) {
            $request = LetterRequest::whereKey($approval->letter_request_id)->lockForUpdate()->firstOrFail();

            $approval->action = ApprovalAction::Approved;
            $approval->approver_id = $actor->id;
            $approval->comments = $comments;
            $approval->acted_at = now();
            $approval->save();

            $step = $approval->step;

            if ($step->is_parallel && ! $this->stepIsComplete($request, $step)) {
                return $request->fresh();
            }

            if ($request->current_step_id !== $step->id) {
                // Sudah dimajukan approver lain (race), tidak perlu ulang.
                return $request->fresh();
            }

            $this->advance($request, $step);

            return $request->fresh();
        });
    }

    public function reject(Approval $approval, User $actor, ?string $comments = null): LetterRequest
    {
        if ($approval->action !== ApprovalAction::Pending) {
            throw new RuntimeException('Approval ini sudah diproses.');
        }

        $step = $approval->step;

        if (! $step->can_reject) {
            throw new RuntimeException('Step ini tidak boleh menolak pengajuan.');
        }

        return DB::transaction(function () use ($approval, $actor, $comments) {
            $request = LetterRequest::whereKey($approval->letter_request_id)->lockForUpdate()->firstOrFail();

            $approval->action = ApprovalAction::Rejected;
            $approval->approver_id = $actor->id;
            $approval->comments = $comments;
            $approval->acted_at = now();
            $approval->save();

            $request->status = LetterRequestStatus::Rejected;
            $request->save();

            return $request->fresh();
        });
    }

    public function userCanAct(LetterRequest $request, User $user): bool
    {
        return (bool) $this->pendingApprovalFor($request, $user);
    }

    /** Approval Pending row yang boleh diaksi user ini untuk request tsb. */
    public function pendingApprovalFor(LetterRequest $request, User $user): ?Approval
    {
        if ($request->status !== LetterRequestStatus::InProgress || ! $request->current_step_id) {
            return null;
        }

        $step = $request->currentStep;

        if (! $this->isApprover($step, $user)) {
            return null;
        }

        $query = $request->approvals()
            ->where('workflow_step_id', $request->current_step_id)
            ->where('action', ApprovalAction::Pending);

        // Step non-parallel dengan approver_role_id: satu row "milik bersama" role,
        // approver_id di-set ke salah satu pemegang role saat seeding tapi siapapun
        // pemegang role boleh mengambilnya (baris approve()/reject() akan overwrite approver_id).
        if ($step->approver_role_id && ! $step->is_parallel) {
            return $query->first();
        }

        return $query->where('approver_id', $user->id)->first();
    }

    protected function isApprover(?WorkflowStep $step, User $user): bool
    {
        if (! $step) {
            return false;
        }

        if ($step->approver_user_id) {
            return $step->approver_user_id === $user->id;
        }

        if ($step->approver_role_id) {
            $role = Role::find($step->approver_role_id);

            return $role && $user->hasRole($role->name);
        }

        return false;
    }

    protected function seedApprovalsForStep(LetterRequest $request, WorkflowStep $step): void
    {
        if ($step->approver_user_id) {
            Approval::create([
                'letter_request_id' => $request->id,
                'workflow_step_id' => $step->id,
                'approver_id' => $step->approver_user_id,
                'action' => ApprovalAction::Pending,
            ]);

            return;
        }

        if ($step->approver_role_id) {
            $role = Role::find($step->approver_role_id);
            $users = $role ? $role->users()->get() : collect();

            if ($step->is_parallel && $users->isNotEmpty()) {
                foreach ($users as $user) {
                    Approval::create([
                        'letter_request_id' => $request->id,
                        'workflow_step_id' => $step->id,
                        'approver_id' => $user->id,
                        'action' => ApprovalAction::Pending,
                    ]);
                }

                return;
            }

            // Non-parallel role step: satu row bersama; approver_id diisi salah satu
            // pemegang role hanya utk memenuhi kolom NOT NULL, siapapun pemegang role
            // tetap bisa mengambilnya (lihat pendingApprovalFor) dan approve()/reject()
            // akan overwrite approver_id ke aktor sebenarnya.
            if ($users->isEmpty()) {
                throw new RuntimeException("Step '{$step->name}': tidak ada user dengan role approver.");
            }

            Approval::create([
                'letter_request_id' => $request->id,
                'workflow_step_id' => $step->id,
                'approver_id' => $users->first()->id,
                'action' => ApprovalAction::Pending,
            ]);

            return;
        }

        throw new RuntimeException("Step '{$step->name}' tidak punya approver_user_id atau approver_role_id.");
    }

    protected function stepIsComplete(LetterRequest $request, WorkflowStep $step): bool
    {
        return ! $request->approvals()
            ->where('workflow_step_id', $step->id)
            ->where('action', ApprovalAction::Pending)
            ->exists();
    }

    protected function advance(LetterRequest $request, WorkflowStep $currentStep): void
    {
        $nextStep = WorkflowStep::where('workflow_id', $currentStep->workflow_id)
            ->where('step_order', '>', $currentStep->step_order)
            ->orderBy('step_order')
            ->first();

        if ($nextStep) {
            $this->seedApprovalsForStep($request, $nextStep);
            $request->current_step_id = $nextStep->id;
            $request->save();

            return;
        }

        $request->status = LetterRequestStatus::Approved;
        $request->current_step_id = null;
        $request->save();
    }
}
