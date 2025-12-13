<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
    
    // Savings & Loans Management - combined routes
    Route::prefix('savings-loans')->name('savings-loans.')->middleware('role:super-admin,ketua-koperasi,manager-keuangan')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SavingsLoanController::class, 'index'])->name('index');
        Route::get('/{savingsLoan}', [App\Http\Controllers\Admin\SavingsLoanController::class, 'show'])->name('show');
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
        Route::get('/{unit}/transaction', function() {
            return redirect()->back()->with('error', 'Akses transaksi hanya melalui form submission');
        });
        Route::get('/{unit}/report', [App\Http\Controllers\Admin\BusinessUnitController::class, 'report'])->name('report');
        Route::get('/{unit}/report/pdf', [App\Http\Controllers\Admin\BusinessUnitController::class, 'reportPDF'])->name('report.pdf');
        
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
        Route::get('/daily', [App\Http\Controllers\Admin\TransactionController::class, 'daily'])->name('daily');
        Route::get('/monthly', [App\Http\Controllers\Admin\TransactionController::class, 'monthly'])->name('monthly');
        Route::post('/export', [App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('export');
        Route::get('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [App\Http\Controllers\Admin\TransactionController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'destroy'])->name('destroy');
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
        
        // Profile update routes
        Route::put('/profile', function (Illuminate\Http\Request $request) {
            // Update user profile logic
            /** @var User $user */
            $user = auth()->user();
            $validated = $request->validate([
                'full_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'birth_date' => 'nullable|date',
                'address' => 'nullable|string|max:500',
                'email_notifications' => 'boolean',
            ]);
            
            $user->full_name = $validated['full_name'] ?? $user->full_name;
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? $user->phone;
            $user->birth_date = $validated['birth_date'] ?? $user->birth_date;
            $user->address = $validated['address'] ?? $user->address;
            $user->email_notifications = $validated['email_notifications'] ?? $user->email_notifications;
            $user->save();
            
            return back()->with('success', 'Profil berhasil diperbarui');
        })->name('profile.update');
        
        Route::put('/password', function (Illuminate\Http\Request $request) {
            // Update password logic
            $validated = $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            if (!Hash::check($validated['current_password'], auth()->user()->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }
            
            /** @var User $user */
            $user = auth()->user();
            $user->password = Hash::make($validated['password']);
            $user->save();
            
            return back()->with('success', 'Password berhasil diperbarui');
        })->name('password.update');
        
        Route::put('/preferences', function (Illuminate\Http\Request $request) {
            // Update user preferences logic
            $validated = $request->validate([
                'theme' => 'required|in:light,dark',
                'language' => 'required|in:id,en',
                'timezone' => 'required|string',
                'notification_email' => 'boolean',
                'notification_push' => 'boolean',
                'notification_sms' => 'boolean',
            ]);
            
            /** @var User $user */
            $user = auth()->user();
            $user->theme = $validated['theme'];
            $user->language = $validated['language'];
            $user->timezone = $validated['timezone'];
            $user->notification_email = $request->has('notification_email');
            $user->notification_push = $request->has('notification_push');
            $user->notification_sms = $request->has('notification_sms');
            $user->save();
            
            return back()->with('success', 'Preferensi berhasil diperbarui');
        })->name('preferences.update');
        
        Route::post('/avatar', function (Illuminate\Http\Request $request) {
            // Update avatar logic
            $validated = $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatars'), $avatarName);
            
            /** @var User $user */
            $user = auth()->user();
            $user->avatar = $avatarName;
            $user->save();
            
            return back()->with('success', 'Foto profil berhasil diperbarui');
        })->name('avatar.update');
        
        // System settings routes
        Route::put('/system/general', function (Illuminate\Http\Request $request) {
            // Update system general settings logic
            $validated = $request->validate([
                'app_name' => 'required|string|max:255',
                'app_url' => 'required|url',
                'timezone' => 'required|string',
                'locale' => 'required|string',
                'debug_mode' => 'boolean',
                'maintenance_mode' => 'boolean',
            ]);
            
            // Update .env file or settings table
            // This is a placeholder - implement actual settings storage
            
            return back()->with('success', 'Pengaturan umum sistem berhasil diperbarui');
        })->name('system.general.update');
        
        Route::put('/system/email', function (Illuminate\Http\Request $request) {
            // Update email settings logic
            $validated = $request->validate([
                'mail_driver' => 'required|string',
                'mail_host' => 'required|string',
                'mail_port' => 'required|integer',
                'mail_username' => 'nullable|string',
                'mail_password' => 'nullable|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'required|email',
                'mail_from_name' => 'required|string',
            ]);
            
            // Update mail configuration
            // This is a placeholder - implement actual settings storage
            
            return back()->with('success', 'Pengaturan email berhasil diperbarui');
        })->name('system.email.update');
        
        Route::put('/system/backup/schedule', function (Illuminate\Http\Request $request) {
            // Update backup schedule logic
            $validated = $request->validate([
                'backup_frequency' => 'required|string',
                'backup_time' => 'required|string',
                'auto_backup' => 'boolean',
            ]);
            
            // Update backup schedule
            // This is a placeholder - implement actual backup scheduling
            
            return back()->with('success', 'Jadwal backup berhasil diperbarui');
        })->name('system.backup.schedule');
    });
});

// Standard Laravel auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Debug: Log the attempt
    // \Log::info('Login attempt for: ' . $credentials['email']);
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        // \Log::info('Login successful for: ' . $credentials['email']);
        return redirect()->intended(route('admin.dashboard'));
    }

    // \Log::info('Login failed for: ' . $credentials['email']);
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Additional auth routes with prefix
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', function () {
        return redirect()->route('login');
    })->name('login');
    
    Route::post('/login', function () {
        // TODO: Implement login logic
        return redirect()->route('admin.dashboard');
    });
    Route::post('/logout', function () {
        // TODO: Implement logout logic
        return redirect()->route('login');
    })->name('logout');
    
    // Registration
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', function () {
        // TODO: Implement registration logic
        return redirect()->route('login');
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
        return redirect()->route('login');
    })->name('password.update');
});
