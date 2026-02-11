<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Petugas\ApprovalController;
use App\Http\Controllers\Petugas\ReportController;
use App\Http\Controllers\Peminjam\CatalogController;
use App\Http\Controllers\Peminjam\LoanRequestController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function (): void {
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::resource('tools', AdminToolController::class)->except(['show']);
        Route::resource('loans', AdminLoanController::class)->except(['show']);
        Route::resource('returns', AdminReturnController::class)
            ->except(['show'])
            ->parameters(['returns' => 'toolReturn']);
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    Route::prefix('petugas')->name('petugas.')->middleware('role:petugas')->group(function (): void {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{loan}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{loan}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/{loan}/accept-return', [ApprovalController::class, 'acceptReturn'])->name('approvals.accept-return');
        Route::post('/approvals/{loan}/reject-return', [ApprovalController::class, 'rejectReturn'])->name('approvals.reject-return');
        Route::get('/returns', [ApprovalController::class, 'monitorReturns'])->name('returns.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::prefix('peminjam')->name('peminjam.')->middleware('role:peminjam')->group(function (): void {
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/loans', [LoanRequestController::class, 'index'])->name('loans.index');
        Route::get('/loans/create', [LoanRequestController::class, 'create'])->name('loans.create');
        Route::post('/loans', [LoanRequestController::class, 'store'])->name('loans.store');
        Route::post('/loans/{loan}/request-return', [LoanRequestController::class, 'requestReturn'])->name('loans.request-return');
    });
});
