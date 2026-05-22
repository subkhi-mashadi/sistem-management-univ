<?php

namespace App\Models;

use App\Concerns\HasAuditable;
use App\Enums\Finance\InstallmentPlan;
use App\Enums\Finance\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use HasAuditable, LogsActivity, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'student_id',
        'semester_id',
        'virtual_account',
        'bank_code',
        'issue_date',
        'due_date',
        'subtotal',
        'discount_amount',
        'fine_amount',
        'scholarship_amount',
        'total_amount',
        'paid_amount',
        'status',
        'installment_plan',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'installment_plan' => InstallmentPlan::class,
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'scholarship_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = self::generateInvoiceNumber($invoice);
            }
            if (empty($invoice->virtual_account) && $invoice->student_id && $invoice->semester_id) {
                $invoice->virtual_account = self::generateVirtualAccount($invoice);
            }
            if (empty($invoice->issue_date)) {
                $invoice->issue_date = now()->toDateString();
            }
        });
    }

    public static function generateVirtualAccount(self $invoice): string
    {
        // Format: 88 + semester_code (5 char) + student_id (7 digit) = 14 digit total.
        // Unik per invoice karena (student_id, semester_id) kombinasi unik.
        $semesterCode = Semester::find($invoice->semester_id)?->code ?? '00000';

        return '88'.$semesterCode.str_pad((string) $invoice->student_id, 7, '0', STR_PAD_LEFT);
    }

    public static function generateInvoiceNumber(self $invoice): string
    {
        $semesterCode = $invoice->semester_id
            ? (Semester::find($invoice->semester_id)?->code ?? 'XXX')
            : now()->format('Ym');

        $lastSeq = (int) self::query()
            ->where('invoice_number', 'like', "INV-{$semesterCode}-%")
            ->withTrashed()
            ->get()
            ->map(fn (self $i) => (int) substr($i->invoice_number, strrpos($i->invoice_number, '-') + 1))
            ->max();

        return 'INV-'.$semesterCode.'-'.str_pad((string) ($lastSeq + 1), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Recalculate semua nominal turunan: subtotal, discount, scholarship, fine, total.
     * Dipanggil oleh observer (InvoiceItem, Discount, Fine, ScholarshipRecipient).
     */
    public function recalculateTotals(): void
    {
        $subtotal = (float) $this->items()->sum('total');
        $discount = (float) $this->discounts()->sum('amount');
        $fine = (float) $this->fines()->where('waived', false)->sum('amount');

        // Scholarship: sum granted_amount dari ScholarshipRecipient untuk (student, semester) ini.
        $scholarship = (float) ScholarshipRecipient::query()
            ->where('student_id', $this->student_id)
            ->where('semester_id', $this->semester_id)
            ->where('status', 'Active')
            ->sum('granted_amount');

        $total = max(0, $subtotal - $discount - $scholarship + $fine);

        $this->updateQuietly([
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'scholarship_amount' => $scholarship,
            'fine_amount' => $fine,
            'total_amount' => $total,
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('invoices');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }
}
