<?php

use App\Http\Controllers\LetterVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verify-letter/{qr_code}', LetterVerificationController::class)->name('letter.verify');
