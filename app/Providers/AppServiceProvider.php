<?php

namespace App\Providers;

use App\Models\Faculty;
use App\Models\Grade;
use App\Models\InvoiceItem;
use App\Models\KrsItem;
use App\Models\Lecturer;
use App\Models\Payment;
use App\Models\StudyProgram;
use App\Observers\FacultyObserver;
use App\Observers\GradeObserver;
use App\Observers\InvoiceItemObserver;
use App\Observers\KrsItemObserver;
use App\Observers\LecturerObserver;
use App\Observers\PaymentObserver;
use App\Observers\StudyProgramObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        InvoiceItem::observe(InvoiceItemObserver::class);
        Payment::observe(PaymentObserver::class);
        KrsItem::observe(KrsItemObserver::class);
        Grade::observe(GradeObserver::class);
        Lecturer::observe(LecturerObserver::class);
        Faculty::observe(FacultyObserver::class);
        StudyProgram::observe(StudyProgramObserver::class);
    }
}
