<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

if (!function_exists('updateEnvValues')) {
    function updateEnvValues(array $values): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath) || !is_writable($envPath)) {
            return;
        }

        $env = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $key = strtoupper($key);
            $escapedKey = preg_quote($key, '/');

            if (is_bool($value)) {
                $valueString = $value ? 'true' : 'false';
            } elseif ($value === null) {
                $valueString = 'null';
            } else {
                $valueString = (string) $value;

                if (strpbrk($valueString, " \t#") !== false) {
                    $valueString = '"' . str_replace('"', '\\"', $valueString) . '"';
                }
            }

            $pattern = "/^{$escapedKey}=.*$/m";
            $replacement = $key . '=' . $valueString;

            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, $replacement, $env);
            } else {
                $env .= PHP_EOL . $replacement;
            }
        }

        file_put_contents($envPath, $env);
    }
}

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
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');
Route::post('/register', [App\Http\Controllers\LandingController::class, 'storeRegistration'])
    ->name('landing.register')
    ->middleware('rate.limit.registration');

// Admin routes group
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard - accessible to all authenticated users with any role
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    // Member Management - requires admin or staff roles
    Route::prefix('members')->name('members.')->middleware('permission:members.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MemberController::class, 'index'])->name('index');
        Route::get('/print', [App\Http\Controllers\Admin\MemberController::class, 'print'])->name('print');
        Route::get('/pdf', [App\Http\Controllers\Admin\MemberController::class, 'pdf'])->name('pdf');
        Route::middleware('permission:members.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\MemberController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\MemberController::class, 'store'])->name('store');
            Route::get('/register', [App\Http\Controllers\Admin\MemberController::class, 'register'])->name('register');
        });
        Route::get('/{member}', [App\Http\Controllers\Admin\MemberController::class, 'show'])->name('show');
        Route::middleware('permission:members.edit')->group(function () {
            Route::get('/{member}/edit', [App\Http\Controllers\Admin\MemberController::class, 'edit'])->name('edit');
            Route::put('/{member}', [App\Http\Controllers\Admin\MemberController::class, 'update'])->name('update');
            Route::post('/{member}/verify', [App\Http\Controllers\Admin\MemberController::class, 'verify'])->name('verify');
            Route::put('/{member}/status', [App\Http\Controllers\Admin\MemberController::class, 'updateStatus'])->name('update-status');
        });
        Route::middleware('permission:members.delete')->group(function () {
            Route::delete('/{member}', [App\Http\Controllers\Admin\MemberController::class, 'destroy'])->name('destroy');
        });
    });

    // Savings Management - requires finance permissions
    Route::prefix('savings')->name('savings.')->middleware('permission:savings.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SavingsController::class, 'index'])->name('index');
        Route::get('/print', [App\Http\Controllers\Admin\SavingsController::class, 'print'])->name('print');
        Route::get('/pdf', [App\Http\Controllers\Admin\SavingsController::class, 'pdf'])->name('pdf');
        Route::middleware('permission:savings.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\SavingsController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\SavingsController::class, 'store'])->name('store');
        });
        Route::get('/{saving}', [App\Http\Controllers\Admin\SavingsController::class, 'show'])->name('show');
        Route::middleware('permission:savings.approve')->group(function () {
            Route::post('/{saving}/approve', [App\Http\Controllers\Admin\SavingsController::class, 'approve'])->name('approve');
            Route::post('/{saving}/withdraw', [App\Http\Controllers\Admin\SavingsController::class, 'withdraw'])->name('withdraw');
        });
        Route::get('/report', [App\Http\Controllers\Admin\SavingsController::class, 'report'])->name('report');
    });

    // Loan Management - requires loan permissions
    Route::prefix('loans')->name('loans.')->middleware('permission:loans.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\LoanController::class, 'index'])->name('index');
        Route::middleware('permission:loans.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\LoanController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\LoanController::class, 'store'])->name('store');
        });
        Route::get('/{loan}', [App\Http\Controllers\Admin\LoanController::class, 'show'])->name('show');
        Route::middleware('permission:loans.approve')->group(function () {
            Route::post('/{loan}/approve', [App\Http\Controllers\Admin\LoanController::class, 'approve'])->name('approve');
            Route::post('/{loan}/reject', [App\Http\Controllers\Admin\LoanController::class, 'reject'])->name('reject');
        });
        Route::post('/{loan}/payment', [App\Http\Controllers\Admin\LoanController::class, 'payment'])->name('payment');
        Route::get('/report', [App\Http\Controllers\Admin\LoanController::class, 'report'])->name('report');
    });

    // Savings & Loans Management - combined routes
    Route::prefix('savings-loans')->name('savings-loans.')->middleware('permission:savings.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SavingsLoanController::class, 'index'])->name('index');
        Route::get('/{savingsLoan}', [App\Http\Controllers\Admin\SavingsLoanController::class, 'show'])->name('show');
    });

    // Business Units - requires unit permissions
    Route::prefix('units')->name('units.')->middleware('permission:units.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BusinessUnitController::class, 'index'])->name('index');
        Route::middleware('permission:units.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\BusinessUnitController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\BusinessUnitController::class, 'store'])->name('store');
        });
        Route::get('/{unit}', [App\Http\Controllers\Admin\BusinessUnitController::class, 'show'])->name('show');
        Route::middleware('permission:units.edit')->group(function () {
            Route::get('/{unit}/edit', [App\Http\Controllers\Admin\BusinessUnitController::class, 'edit'])->name('edit');
            Route::put('/{unit}', [App\Http\Controllers\Admin\BusinessUnitController::class, 'update'])->name('update');
        });
        Route::middleware('permission:units.delete')->group(function () {
            Route::delete('/{unit}', [App\Http\Controllers\Admin\BusinessUnitController::class, 'destroy'])->name('destroy');
        });
        Route::post('/{unit}/transaction', [App\Http\Controllers\Admin\BusinessUnitController::class, 'transaction'])->name('transaction');
        Route::get('/{unit}/transaction', function () {
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

    // Transactions - requires transaction permissions
    Route::prefix('transactions')->name('transactions.')->middleware('permission:transactions.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('index');
        Route::middleware('permission:transactions.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\TransactionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\TransactionController::class, 'store'])->name('store');
        });
        Route::get('/daily', [App\Http\Controllers\Admin\TransactionController::class, 'daily'])->name('daily');
        Route::get('/monthly', [App\Http\Controllers\Admin\TransactionController::class, 'monthly'])->name('monthly');
        Route::post('/export', [App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('export');
        Route::get('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [App\Http\Controllers\Admin\TransactionController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'destroy'])->name('destroy');
    });

    // Reports - requires report permissions
    Route::prefix('reports')->name('reports.')->middleware('permission:reports.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        Route::middleware('permission:reports.financial')->group(function () {
            Route::get('/financial', [App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('financial');
        });
        Route::middleware('permission:reports.members')->group(function () {
            Route::get('/members', [App\Http\Controllers\Admin\ReportController::class, 'members'])->name('members');
        });
        Route::middleware('permission:reports.units')->group(function () {
            Route::get('/units', [App\Http\Controllers\Admin\ReportController::class, 'units'])->name('units');
        });
        Route::middleware('permission:reports.generate')->group(function () {
            Route::get('/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('generate');
            Route::post('/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('generate.store');
        });
        Route::get('/{report}', [App\Http\Controllers\Admin\ReportController::class, 'show'])->name('show');
    });

    // Users Management - requires user management permissions
    Route::prefix('users')->name('users.')->middleware('permission:users.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::middleware('permission:users.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        });
        Route::get('/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
        Route::middleware('permission:users.edit')->group(function () {
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
            Route::put('/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/assign-roles', [App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('assign-roles');
        });
        Route::middleware('permission:users.delete')->group(function () {
            Route::delete('/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        });
    });

    // Roles Management - requires role management permissions
    Route::prefix('roles')->name('roles.')->middleware('permission:roles.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('index');
        Route::middleware('permission:roles.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('store');
        });
        Route::get('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'show'])->name('show');
        Route::middleware('permission:roles.edit')->group(function () {
            Route::get('/{role}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('update');
            Route::post('/{role}/assign-permissions', [App\Http\Controllers\Admin\RoleController::class, 'assignPermissions'])->name('assign-permissions');
        });
        Route::middleware('permission:roles.delete')->group(function () {
            Route::delete('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('destroy');
        });
    });

    // Permissions Management - requires permission management permissions
    Route::prefix('permissions')->name('permissions.')->middleware('permission:permissions.view')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('index');
        Route::middleware('permission:permissions.create')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\PermissionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\PermissionController::class, 'store'])->name('store');
        });
        Route::get('/{permission}', [App\Http\Controllers\Admin\PermissionController::class, 'show'])->name('show');
        Route::middleware('permission:permissions.edit')->group(function () {
            Route::get('/{permission}/edit', [App\Http\Controllers\Admin\PermissionController::class, 'edit'])->name('edit');
            Route::put('/{permission}', [App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('update');
            Route::post('/{permission}/assign-roles', [App\Http\Controllers\Admin\PermissionController::class, 'assignRoles'])->name('assign-roles');
            Route::delete('/{permission}/remove-role/{role}', [App\Http\Controllers\Admin\PermissionController::class, 'removeRole'])->name('remove-role');
        });
        Route::middleware('permission:permissions.delete')->group(function () {
            Route::delete('/{permission}', [App\Http\Controllers\Admin\PermissionController::class, 'destroy'])->name('destroy');
        });
    });

    // Export & Reporting - requires report permissions
    Route::prefix('export')->name('export.')->middleware('permission:reports.generate')->group(function () {
        Route::get('/members', [App\Http\Controllers\Admin\ExportController::class, 'members'])->name('members');
        Route::get('/financial', [App\Http\Controllers\Admin\ExportController::class, 'financialReport'])->name('financial');
        Route::get('/summary', [App\Http\Controllers\Admin\ExportController::class, 'summaryReport'])->name('summary');
    });

    // Village Settings - requires system permissions
    Route::prefix('village-settings')->name('village-settings.')->middleware('permission:settings.system')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\VillageSettingsController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\VillageSettingsController::class, 'update'])->name('update');
        Route::delete('/logo', [App\Http\Controllers\Admin\VillageSettingsController::class, 'removeLogo'])->name('remove-logo');
    });

    // Settings - accessible to all authenticated users with permissions
    Route::prefix('settings')->name('settings.')->middleware('permission:settings.view')->group(function () {
        Route::get('/profile', function () {
            return view('admin.settings.profile');
        })->name('profile');

        Route::middleware('permission:settings.system')->group(function () {
            Route::get('/system', function () {
                return view('admin.settings.system');
            })->name('system');
        });

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

            $debugEnabled = $request->boolean('debug_mode');
            $maintenanceEnabled = $request->boolean('maintenance_mode');

            if ($maintenanceEnabled) {
                Artisan::call('down');
            } else {
                Artisan::call('up');
            }

            updateEnvValues([
                'APP_NAME' => $validated['app_name'],
                'APP_URL' => $validated['app_url'],
                'APP_DEBUG' => $debugEnabled,
                'APP_TIMEZONE' => $validated['timezone'],
                'APP_LOCALE' => $validated['locale'],
                'APP_MAINTENANCE' => $maintenanceEnabled,
            ]);

            config([
                'app.name' => $validated['app_name'],
                'app.url' => $validated['app_url'],
                'app.debug' => $debugEnabled,
                'app.timezone' => $validated['timezone'],
                'app.locale' => $validated['locale'],
            ]);

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

            $envValues = [
                'MAIL_MAILER' => $validated['mail_driver'],
                'MAIL_HOST' => $validated['mail_host'],
                'MAIL_PORT' => $validated['mail_port'],
                'MAIL_USERNAME' => $validated['mail_username'] ?? null,
                'MAIL_ENCRYPTION' => $validated['mail_encryption'] !== '' ? $validated['mail_encryption'] : null,
                'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
                'MAIL_FROM_NAME' => $validated['mail_from_name'],
            ];

            if (!empty($validated['mail_password'])) {
                $envValues['MAIL_PASSWORD'] = $validated['mail_password'];
            }

            updateEnvValues($envValues);

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

            updateEnvValues([
                'BACKUP_FREQUENCY' => $validated['backup_frequency'],
                'BACKUP_TIME' => $validated['backup_time'],
                'BACKUP_AUTO' => $request->boolean('auto_backup'),
            ]);

            return back()->with('success', 'Jadwal backup berhasil diperbarui');
        })->name('system.backup.schedule');

        Route::post('/system/maintenance', function (Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'enable' => 'required|boolean',
                'message' => 'nullable|string|max:1000',
            ]);

            $enable = $validated['enable'];
            $message = isset($validated['message']) ? trim($validated['message']) : '';

            if ($enable) {
                $options = [];
                if (!empty($validated['message'])) {
                    $options['--message'] = $validated['message'];
                }
                Artisan::call('down', $options);
            } else {
                Artisan::call('up');
            }

            $envValues = [
                'APP_MAINTENANCE' => $enable,
            ];

            if ($message !== '') {
                $envValues['APP_MAINTENANCE_MESSAGE'] = $message;
            }

            updateEnvValues($envValues);

            return response()->json([
                'status' => 'ok',
                'maintenance' => $enable,
            ]);
        })->name('system.maintenance.toggle');
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