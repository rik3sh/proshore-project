<?php

use Inertia\Inertia;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionnaireController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Questionnaires
    Route::resource('questionnaires', QuestionnaireController::class)->middleware(IsAdmin::class);
    Route::get('show-questions/{qid}', [QuestionnaireController::class, 'show_questions'])->name('show-questions')->middleware(IsAdmin::class);
    Route::post('send/{qid}', [QuestionnaireController::class, 'send_to_students'])->name('send')->middleware(IsAdmin::class);

    // Exam
    Route::get('exam/{code}', [ExamController::class, 'start_exam'])->name('start-exam');
    Route::post('exam', [ExamController::class, 'store'])->name('exam-store');
    Route::get('end', [ExamController::class, 'end'])->name('exam-end');
});

require __DIR__.'/auth.php';
