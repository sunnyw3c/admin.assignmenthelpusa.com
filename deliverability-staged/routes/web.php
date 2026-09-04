<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AssignmentServiceController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\EssayWritingController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeworkHelpController;
use App\Http\Controllers\HowItWorksController;
use App\Http\Controllers\LabReportController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LawAssignmentController;
use App\Http\Controllers\LiteratureReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PresentationDesignController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProofreadingEditingController;
use App\Http\Controllers\ResearchPaperController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ThesisDissertationController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap', [SitemapController::class, 'html'])->name('sitemap.html');

Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/programming-assignment-help', [LanguageController::class, 'index'])->name('languages.index');
// Route::get('/programming-assignment-help/{slug}', [LanguageController::class, 'show'])->name('languages.show');

// Programming Services (SEO-friendly hierarchical URLs)
Route::get('/programming-help', [ServiceController::class, 'index'])->name('services.programming.index');
Route::get('/programming-help/{slug}', [ServiceController::class, 'show'])->name('services.programming.show');

Route::get('/services', [AssignmentServiceController::class, 'index'])->name('services.assignment.index');
Route::get('/assignment-help/{slug}', [AssignmentServiceController::class, 'show'])->name('services.assignment.show');
Route::post('/assignment-help/calculate-price', [AssignmentServiceController::class, 'calculatePrice'])->name('services.assignment.calculate-price');

// Individual Assignment Service Routes
Route::get('/essay-writing-help', [EssayWritingController::class, 'index'])->name('services.essay-writing.index');
Route::get('/essay-writing-help/{slug}', [EssayWritingController::class, 'show'])
    ->whereIn('slug', ['argumentative'])
    ->name('services.essay-writing.show');
Route::get('/research-paper-help', [ResearchPaperController::class, 'index'])->name('services.research-paper.index');
Route::get('/case-study-help', [CaseStudyController::class, 'index'])->name('services.case-study.index');
Route::get('/homework-help', [HomeworkHelpController::class, 'index'])->name('services.homework-help.index');
Route::get('/homework-help/math', [HomeworkHelpController::class, 'math'])->name('services.homework-help.math');
Route::get('/homework-help/nursing', [HomeworkHelpController::class, 'nursing'])->name('services.homework-help.nursing');
Route::get('/homework-help/physics', [HomeworkHelpController::class, 'physics'])->name('services.homework-help.physics');
Route::get('/lab-report-help', [LabReportController::class, 'index'])->name('services.lab-report.index');
Route::get('/literature-review-help', [LiteratureReviewController::class, 'index'])->name('services.literature-review.index');
Route::get('/presentation-design-help', [PresentationDesignController::class, 'index'])->name('services.presentation-design.index');
Route::get('/proofreading-editing-help', [ProofreadingEditingController::class, 'index'])->name('services.proofreading-editing.index');
Route::get('/thesis-help', [ThesisDissertationController::class, 'thesis'])->name('services.thesis.index');
Route::get('/dissertation-help', [ThesisDissertationController::class, 'dissertation'])->name('services.dissertation.index');
Route::get('/law-assignment-help', [LawAssignmentController::class, 'index'])->name('services.law-assignment.index');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/how-it-works', [HowItWorksController::class, 'index'])->name('how-it-works');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::get('/experts', [ExpertController::class, 'index'])->name('experts');
Route::get('/privacy-policy', fn () => view('privacy-policy'))->name('privacy-policy');
Route::get('/terms-of-service', fn () => view('terms-of-service'))->name('terms-of-service');
Route::get('/order', [OrderController::class, 'create'])->name('order');
Route::get('/order/success/{assignment}', [OrderController::class, 'success'])->name('order.success')->middleware(['auth']);
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::get('/dashboard', function () {
    return view('dashboard', [
        'header' => 'Student Dashboard',
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/{id}', function ($id) {
    return view('assignment-details', [
        'order_number' => $id,
        'header' => 'Assignment Details',
    ]);
})->middleware(['auth', 'verified'])->name('dashboard.details');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/assignments', [\App\Http\Controllers\Api\AssignmentApiController::class, 'index']);
    Route::post('/assignments/{assignment}/upload-file', [\App\Http\Controllers\Api\AssignmentApiController::class, 'uploadFile']);
    Route::get('/assignments/{assignment}/messages', [\App\Http\Controllers\Api\AssignmentMessageController::class, 'index']);
    Route::post('/assignments/{assignment}/messages', [\App\Http\Controllers\Api\AssignmentMessageController::class, 'store']);
});

require __DIR__.'/auth.php';
