<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddStudentAndParentController;
use App\Http\Controllers\NewParentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NewStudentController;
use App\Http\Controllers\Superadmin\SuperadminController;
use App\Http\Controllers\Accountant\AccountantController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\StudentSearch;
use App\Http\Controllers\SystemControlsController;
use App\Http\Controllers\ManageBudgetController;
Use App\Http\Controllers\ForgotPassword;

Route::post('/access-security/save', [AuthController::class, 'registerSave'])->name('register.save');

// Login routes
Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');

// Forgot password routes
Route::get('/forgot-password', [ForgotPassword::class, 'forgotPassword'])
    ->name('auth.forgot-password'); // <-- Correct route name

Route::post('/forgot-password', [ForgotPassword::class, 'passwordEmail'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ForgotPassword::class, 'passwordReset'])
    ->name('password.reset');

Route::post('/reset-password', [ForgotPassword::class, 'passwordUpdate'])
    ->name('password.update');

// auth routes
Route::middleware('auth')->group(function () {
    Route::get('/Profile', [PublicController::class, 'profile'])->name('Profile');
    Route::put('/Profile/{id}', [PublicController::class, 'updatePersonal'])->name('updatePersonal');
    Route::put('/update-password', [PublicController::class, 'updatePassword'])->name('updatePassword');
    Route::get('/manuals',[PublicController::class,'manuals'])->name('manuals');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/upload-user-image', [PublicController::class, 'userUploadImage'])->name('upload.user.image');
    Route::patch('/user/{id}/toggle-status', [AuthController::class, 'toggleStatus'])->name('user.toggle-status');

    // student directory
    Route::controller(AddStudentAndParentController::class)->prefix('ManageStudent')->group(function () {
        Route::get('addstudent', 'index')->name('ManageStudent/addstudent');
        Route::post('addstudent', 'store')->name('ManageStudent/addstudent');
    });

    // new student directory
    Route::controller(NewStudentController::class)->prefix('ManageStudent')->group(function () {
        Route::get('/StudentDirectory', 'index')->name('ManageStudent/StudentDirectory');
        Route::post('/save', 'save')->name('ManageStudent.save');
        Route::get('/student/{id}/show', 'show')->name('ManageStudent.show');
        Route::delete('/students/{id}', 'destroy')->name('students.destroy');
        Route::get('/students/{id}/edit', 'edit')->name('ManageStudent.edit');
        Route::put('/students/{id}', 'update')->name('ManageStudent.update');
    });
    Route::post('/upload-student-image', [NewStudentController::class, 'studentUploadImage'])->name('upload.student.image');
    
    // new parent directory
    Route::controller(NewParentController::class)->prefix('ParentDirectory')->group(function () {
        Route::get('', 'index')->name('ParentDirectory');
        Route::post('', 'create')->name('ParentDirectory.create');
        Route::delete('/parents/{id}', 'destroy')->name('parents.destroy');
        Route::get('/parents/{id}/edit', 'edit')->name('ParentDirectory.edit');
        Route::put('/parents/{id}/edit', 'update')->name('ParentDirectory.update');
        Route::get('/parents/{id}/show', 'show')->name('ParentDirectory.show');
    });
    Route::post('/upload-image', [NewParentController::class, 'parentUploadImage'])->name('upload.image');

    // tuition payment
    Route::controller(PaymentController::class)->prefix('Payment')->group(function () {
        Route::get('', 'payment')->name('Payment');
        Route::post('', 'save')->name('PaymentRecords');
        Route::get('/check-payment-period', 'checkPaymentPeriod')->name('check.payment.period');
    });


    // payment records
    Route::controller(PaymentController::class)->prefix('PaymentRecords')->group(function () {
        Route::get('', 'paymentRecords')->name('PaymentRecords');
    });

    // system controls
    Route::controller(SystemControlsController::class)->prefix('SystemControls')->group(function () {
        Route::get('/user/{id}/show', 'show')->name('SystemControls.show');
        Route::get('/user/{id}/edit', 'edit')->name('SystemControls.edit');
        Route::put('/user/{id}/edit', 'update')->name('SystemControls.update');
        Route::get('/AcademicAdvancement', 'academicAdvancement')->name('SystemControls.AcademicAdvancement');
        Route::get('/section', 'addSection')->name('SystemControls.section');
        Route::get('/schoolyear', 'schoolYear')->name('SystemControls.schoolyear');
        Route::get('/discounts', 'discounts')->name('SystemControls.discounts');

        Route::post('/section/create', 'createSection')->name('SystemControls.createSection');
        Route::put('/section/{id}/update', 'updateSection')->name('SystemControls.updateSection');
        Route::delete('/section/{id}/delete', 'deleteSection')->name('SystemControls.deleteSection');
        
        Route::post('/schoolyear/create', 'createSY')->name('SystemControls.createSY');
        Route::put('/schoolyear/{id}/update', 'updateSY')->name('SystemControls.updateSY');
        Route::delete('/schoolyear/{id}/delete', 'deleteSY')->name('SystemControls.deleteSY');

        Route::post('/discounts/store', 'storeDiscount')->name('SystemControls.discounts.store');
        Route::put('/discounts/{id}/update', 'updateDiscount')->name('SystemControls.discounts.update');

        Route::post('/academic-advancement/update', 'updateAcademicAdvancement')->name('academic.advancement.update');
        
    });

    // manage budget
    Route::controller(ManageBudgetController::class)->prefix('ManageBudget')->group(function () {
        Route::get('/AllocateBudget', 'allocate')->name('ManageBudget.AllocateBudget');
        Route::post('/AllocateBudget', 'storeAllocation')->name('ManageBudget.AllocateBudget.store');
        Route::post('/ReplenishExpense', 'storeReplenishment')->name('ManageBudget.ReplenishExpense.store');
        
        Route::get('/UtilizeExpense', 'utilize')->name('ManageBudget.UtilizeExpense');
        Route::post('/UtilizeExpense', 'storeExpense')->name('ManageBudget.UtilizeExpense.store');
    
        Route::get('/ExpenseTracking', 'expense')->name('ManageBudget.ExpenseTracking');
        Route::get('/ExpenseDirectory', 'exdirectory')->name('ManageBudget.ExpenseDirectory');
    });
});

// for payment search
Route::get('/student/search', [StudentSearch::class, 'searchAjax'])->name('student.search.ajax');

// for dashboards
Route::middleware(['auth','role:SuperAdmin'])->group(function() {
    Route::get('SystemControls/access-security', [AuthController::class, 'register'])->name('SystemControls.access-security');
    Route::get('superadmin/dashboard',[SuperadminController::class, 'index'])->name('superadmin.dashboard');
});

Route::middleware(['auth','role:Accountant'])->group(function() {
    Route::get('accountant/dashboard',[AccountantController::class, 'index'])->name('accountant.dashboard');
});

Route::middleware(['auth','role:Teacher'])->group(function() {
    Route::get('teacher/dashboard',[TeacherController::class, 'index'])->name('teacher.dashboard');
});