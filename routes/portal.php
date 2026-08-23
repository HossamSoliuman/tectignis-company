<?php

use App\Http\Controllers\Admin\Portal\DailyWorkUpdateController;
use App\Http\Controllers\Admin\Portal\DashboardController;
use App\Http\Controllers\Admin\Portal\DepartmentController;
use App\Http\Controllers\Admin\Portal\EmployeeController;
use App\Http\Controllers\Admin\Portal\FileController;
use App\Http\Controllers\Admin\Portal\MyWorkController;
use App\Http\Controllers\Admin\Portal\TaskController;
use App\Http\Controllers\Admin\Portal\TaskUpdateController;
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

    // The single authorized read path for confidential portal documents.
    Route::get('files/{attachment}', [FileController::class, 'show'])->name('files.show');
});
