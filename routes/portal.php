<?php

use App\Http\Controllers\Admin\Portal\DailyWorkUpdateController;
use App\Http\Controllers\Admin\Portal\DashboardController;
use App\Http\Controllers\Admin\Portal\DepartmentController;
use App\Http\Controllers\Admin\Portal\EmployeeController;
use App\Http\Controllers\Admin\Portal\FileController;
use App\Http\Controllers\Admin\Portal\FollowupUpdateController;
use App\Http\Controllers\Admin\Portal\MyWorkController;
use App\Http\Controllers\Admin\Portal\OemFollowupController;
use App\Http\Controllers\Admin\Portal\TaskController;
use App\Http\Controllers\Admin\Portal\TaskUpdateController;
use App\Http\Controllers\Admin\Portal\TenderActivityController;
use App\Http\Controllers\Admin\Portal\TenderClarificationController;
use App\Http\Controllers\Admin\Portal\TenderController;
use App\Http\Controllers\Admin\Portal\TenderDocumentController;
use App\Http\Controllers\Admin\Portal\TenderEligibilityController;
use App\Http\Controllers\Admin\Portal\TenderOemController;
use App\Http\Controllers\Admin\Portal\TenderPreparationController;
use App\Http\Controllers\Admin\Portal\TenderResultController;
use App\Http\Controllers\Admin\Portal\TenderSubmissionController;
use App\Http\Controllers\Admin\Portal\TenderTaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Internal Operations Portal
|--------------------------------------------------------------------------
|
| Mounted at /admin/portal from routes/admin.php. Guarded by `portal`, not
| `admin`: portal access comes from `users.portal_role` alone, so an employee
| works here without ever gaining website CMS rights.
|
*/

Route::prefix('portal')->name('portal.')->middleware(['auth', 'portal'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('my-work', MyWorkController::class)->name('my-work');

    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/updates', [TaskUpdateController::class, 'store'])->name('tasks.updates.store');

    Route::resource('daily-work', DailyWorkUpdateController::class)
        ->parameters(['daily-work' => 'daily_work'])
        ->except(['show']);

    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class)->except(['show']);

    /*
    | Tender workspace (§7–§14, §27). One tender, eleven tabs — each tab owns
    | its own writes so no screen can silently overwrite another's work.
    | scopeBindings() ties every child to the tender in the URL, so a document
    | from another bid 404s instead of loading.
    */
    Route::resource('tenders', TenderController::class);

    Route::prefix('tenders/{tender}')->name('tenders.')->scopeBindings()->group(function () {
        Route::get('eligibility', [TenderEligibilityController::class, 'index'])->name('eligibility.index');
        Route::post('eligibility', [TenderEligibilityController::class, 'store'])->name('eligibility.store');
        Route::put('eligibility/{checklist_item}', [TenderEligibilityController::class, 'update'])->name('eligibility.update');
        Route::delete('eligibility/{checklist_item}', [TenderEligibilityController::class, 'destroy'])->name('eligibility.destroy');
        Route::put('eligibility-override', [TenderEligibilityController::class, 'override'])->name('eligibility.override');
        Route::delete('eligibility-override', [TenderEligibilityController::class, 'clearOverride'])->name('eligibility.override.clear');

        Route::get('documents', [TenderDocumentController::class, 'index'])->name('documents.index');
        Route::post('documents', [TenderDocumentController::class, 'store'])->name('documents.store');
        Route::put('documents/{document}', [TenderDocumentController::class, 'update'])->name('documents.update');
        Route::put('documents/{document}/verify', [TenderDocumentController::class, 'verify'])->name('documents.verify');
        Route::delete('documents/{document}', [TenderDocumentController::class, 'destroy'])->name('documents.destroy');

        Route::get('oem', [TenderOemController::class, 'index'])->name('oem.index');
        Route::post('oem', [TenderOemController::class, 'store'])->name('oem.store');

        Route::get('tasks', [TenderTaskController::class, 'index'])->name('tasks.index');
        Route::post('tasks', [TenderTaskController::class, 'store'])->name('tasks.store');
        Route::post('tasks/apply-template', [TenderTaskController::class, 'applyTemplate'])->name('tasks.template');

        Route::get('clarifications', [TenderClarificationController::class, 'index'])->name('clarifications.index');
        Route::post('clarifications', [TenderClarificationController::class, 'store'])->name('clarifications.store');
        Route::put('clarifications/{clarification}', [TenderClarificationController::class, 'update'])->name('clarifications.update');
        Route::delete('clarifications/{clarification}', [TenderClarificationController::class, 'destroy'])->name('clarifications.destroy');

        Route::get('technical', [TenderPreparationController::class, 'technical'])->name('technical');
        Route::get('commercial', [TenderPreparationController::class, 'commercial'])->name('commercial');

        Route::get('submission', [TenderSubmissionController::class, 'index'])->name('submission.index');
        Route::put('submission/{submission_check}', [TenderSubmissionController::class, 'update'])->name('submission.update');
        Route::post('submission/mark-submitted', [TenderSubmissionController::class, 'markSubmitted'])->name('submission.mark');

        Route::get('result', [TenderResultController::class, 'index'])->name('result.index');
        Route::put('result', [TenderResultController::class, 'store'])->name('result.store');

        Route::get('activity', [TenderActivityController::class, 'index'])->name('activity.index');
    });

    // The cross-tender chase queue: what nobody has followed up today (§11).
    Route::resource('oem-followups', OemFollowupController::class)
        ->parameters(['oem-followups' => 'oem_followup']);
    Route::post('oem-followups/{oem_followup}/updates', [FollowupUpdateController::class, 'store'])
        ->name('oem-followups.updates.store');

    // The single authorized read path for confidential portal documents.
    Route::get('files/{attachment}', [FileController::class, 'show'])->name('files.show');
});
