<?php

namespace App\Services\Thesis;

use App\Enums\Thesis\ThesisTopicStatus;
use App\Models\ThesisTopic;
use RuntimeException;

class ThesisTopicService
{
    public function submit(ThesisTopic $topic): ThesisTopic
    {
        if (! in_array($topic->status, [ThesisTopicStatus::Draft, ThesisTopicStatus::Rejected, ThesisTopicStatus::Revision], true)) {
            throw new RuntimeException('Topik ini tidak bisa disubmit dari status saat ini.');
        }

        $topic->status = ThesisTopicStatus::Submitted;
        $topic->review_notes = null;
        $topic->save();

        return $topic->fresh();
    }

    public function approve(ThesisTopic $topic): ThesisTopic
    {
        if ($topic->status !== ThesisTopicStatus::Submitted) {
            throw new RuntimeException('Hanya topik berstatus Submitted yang bisa disetujui.');
        }

        $topic->status = ThesisTopicStatus::Approved;
        $topic->approved_by = auth()->id();
        $topic->approved_at = now();
        $topic->save();

        return $topic->fresh();
    }

    public function reject(ThesisTopic $topic, string $notes): ThesisTopic
    {
        if ($topic->status !== ThesisTopicStatus::Submitted) {
            throw new RuntimeException('Hanya topik berstatus Submitted yang bisa ditolak.');
        }

        $topic->status = ThesisTopicStatus::Rejected;
        $topic->review_notes = $notes;
        $topic->save();

        return $topic->fresh();
    }

    public function requestRevision(ThesisTopic $topic, string $notes): ThesisTopic
    {
        if ($topic->status !== ThesisTopicStatus::Submitted) {
            throw new RuntimeException('Hanya topik berstatus Submitted yang bisa diminta revisi.');
        }

        $topic->status = ThesisTopicStatus::Revision;
        $topic->review_notes = $notes;
        $topic->save();

        return $topic->fresh();
    }
}
