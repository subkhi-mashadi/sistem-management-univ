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
