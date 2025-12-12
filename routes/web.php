<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing page
Route::get('/', function () {
    return view('landing');
});

// Admin routes group
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard - accessible to all authenticated users with any role
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->middleware('role:super-admin,ketua-koperasi,manager-keuangan,manager-unit,staff-administrasi,bendahara-unit,anggota')
        ->name('dashboard');
    
    // Member Management - requires admin or staff roles
    Route::prefix('members')->name('members.')->middleware('role:super-admin,ketua-koperasi,manager-keuangan,staff-administrasi')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MemberController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\MemberController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\MemberController::class, 'store'])->name('store');
        Route::get('/register', [App\Http\Controllers\Admin\MemberController::class, 'register'])->name('register');
        Route::get('/{member}', [App\Http\Controllers\Admin\MemberController::class, 'show'])->name('show');
        Route::get('/{member}/edit', [App\Http\Controllers\Admin\MemberController::class, 'edit'])->name('edit');
        Route::put('/{member}', [App\Http\Controllers\Admin\MemberController::class, 'update'])->name('update');
        Route::delete('/{member}', [App\Http\Controllers\Admin\MemberController::class, 'destroy'])->name('destroy');
        Route::post('/{member}/verify', [App\Http\Controllers\Admin\MemberController::class, 'verify'])->name('verify');
        Route::put('/{member}/status', [App\Http\Controllers\Admin\MemberController::class, 'updateStatus'])->name('update-status');
    });
    
    // Savings Management - requires finance or admin roles
    Route::prefix('savings')->name('savings.')->middleware('role:super-admin,ketua-koperasi,manager-keuangan')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SavingsController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\SavingsController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\SavingsController::class, 'store'])->name('store');
        Route::get('/{saving}', [App\Http\Controllers\Admin\SavingsController::class, 'show'])->name('show');
        Route::post('/{saving}/approve', [App\Http\Controllers\Admin\SavingsController::class, 'approve'])->name('approve');
        Route::post('/{saving}/withdraw', [App\Http\Controllers\Admin\SavingsController::class, 'withdraw'])->name('withdraw');
        Route::get('/report', [App\Http\Controllers\Admin\SavingsController::class, 'report'])->name('report');
    });
    
    // Loan Management - requires finance or admin roles
    Route::prefix('loans')->name('loans.')->middleware('role:super-admin,ketua-koperasi,manager-keuangan')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\LoanController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\LoanController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\LoanController::class, 'store'])->name('store');
        Route::get('/{loan}', [App\Http\Controllers\Admin\LoanController::class, 'show'])->name('show');
        Route::post('/{loan}/approve', [App\Http\Controllers\Admin\LoanController::class, 'approve'])->name('approve');
        Route::post('/{loan}/reject', [App\Http\Controllers\Admin\LoanController::class, 'reject'])->name('reject');
        Route::post('/{loan}/payment', [App\Http\Controllers\Admin\LoanController::class, 'payment'])->name('payment');
        Route::get('/report', [App\Http\Controllers\Admin\LoanController::class, 'report'])->name('report');
    });
    
    // Business Units - requires unit manager or admin roles
    Route::prefix('units')->name('units.')->middleware('role:super-admin,ketua-koperasi,manager-unit')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BusinessUnitController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\BusinessUnitController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\BusinessUnitController::class, 'store'])->name('store');
        Route::get('/{unit}', [App\Http\Controllers\Admin\BusinessUnitController::class, 'show'])->name('show');
        Route::get('/{unit}/edit', [App\Http\Controllers\Admin\BusinessUnitController::class, 'edit'])->name('edit');
        Route::put('/{unit}', [App\Http\Controllers\Admin\BusinessUnitController::class, 'update'])->name('update');
        Route::delete('/{unit}', [App\Http\Controllers\Admin\BusinessUnitController::class, 'destroy'])->name('destroy');
        Route::post('/{unit}/transaction', [App\Http\Controllers\Admin\BusinessUnitController::class, 'transaction'])->name('transaction');
        Route::get('/{unit}/report', [App\Http\Controllers\Admin\BusinessUnitController::class, 'report'])->name('report');
        
        // Specific unit types
        Route::get('/type/sembako', [App\Http\Controllers\Admin\BusinessUnitController::class, 'sembako'])->name('sembako');
        Route::get('/type/apotek', [App\Http\Controllers\Admin\BusinessUnitController::class, 'apotek'])->name('apotek');
        Route::get('/type/klinik', [App\Http\Controllers\Admin\BusinessUnitController::class, 'klinik'])->name('klinik');
        Route::get('/type/logistik', [App\Http\Controllers\Admin\BusinessUnitController::class, 'logistik'])->name('logistik');
    });
    
    // Transactions - accessible to all operational roles
    Route::prefix('transactions')->name('transactions.')->middleware('role:super-admin,ketua-koperasi,manager-keuangan,manager-unit,staff-administrasi,bendahara-unit')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\TransactionController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\TransactionController::class, 'store'])->name('store');
        Route::get('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [App\Http\Controllers\Admin\TransactionController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'destroy'])->name('destroy');
        Route::get('/daily', [App\Http\Controllers\Admin\TransactionController::class, 'daily'])->name('daily');
        Route::get('/monthly', [App\Http\Controllers\Admin\TransactionController::class, 'monthly'])->name('monthly');
        Route::post('/export', [App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('export');
    });
    
    // Reports - accessible to management roles
    Route::prefix('reports')->name('reports.')->middleware('role:super-admin,ketua-koperasi,manager-keuangan,manager-unit')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        Route::get('/financial', [App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('financial');
        Route::get('/members', [App\Http\Controllers\Admin\ReportController::class, 'members'])->name('members');
        Route::get('/units', [App\Http\Controllers\Admin\ReportController::class, 'units'])->name('units');
        Route::get('/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('generate');
        Route::post('/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('generate.store');
        Route::get('/{report}', [App\Http\Controllers\Admin\ReportController::class, 'show'])->name('show');
    });
    
    // Settings - accessible to all authenticated users
    Route::prefix('settings')->name('settings.')->middleware('role:super-admin,ketua-koperasi,manager-keuangan,manager-unit,staff-administrasi,bendahara-unit,anggota')->group(function () {
        Route::get('/profile', function () {
            return view('admin.settings.profile');
        })->name('profile');
        Route::get('/system', function () {
            return view('admin.settings.system');
        })->name('system');
    });
});

// Auth routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', function () {
        // TODO: Implement login logic
        return redirect()->route('admin.dashboard');
    });
    Route::post('/logout', function () {
        // TODO: Implement logout logic
        return redirect()->route('auth.login');
    })->name('logout');
    
    // Registration
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', function () {
        // TODO: Implement registration logic
        return redirect()->route('auth.login');
    });
    
    // Password reset
    Route::get('/password/reset', function () {
        return view('auth.password.reset');
    })->name('password.request');
    Route::post('/password/email', function () {
        // TODO: Implement password reset email
        return back()->with('success', 'Password reset link sent');
    })->name('password.email');
    Route::get('/password/reset/{token}', function () {
        return view('auth.password.reset-form');
    })->name('password.reset');
    Route::post('/password/reset', function () {
        // TODO: Implement password reset logic
        return redirect()->route('auth.login');
    })->name('password.update');
});
