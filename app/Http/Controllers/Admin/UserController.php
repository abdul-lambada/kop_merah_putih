<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->orderBy('name')
            ->paginate(10);

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Debug: Log request data
        Log::info('User creation attempt', [
            'request_data' => $request->all(),
            'user_authenticated' => auth()->check(),
            'current_user' => auth()->user()?->name
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'nullable|integer|exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            if (!empty($validated['roles'])) {
                // Filter out any invalid role IDs before assigning
                $validRoleIds = Role::whereIn('id', $validated['roles'])->pluck('id')->toArray();
                if (!empty($validRoleIds)) {
                    $user->syncRoles($validRoleIds);
                    Log::info('Roles assigned', ['user_id' => $user->id, 'role_ids' => $validRoleIds]);
                }
            }

            DB::commit();
            
            Log::info('User created successfully', ['user_id' => $user->id, 'user_name' => $user->name]);
            
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        $roles = Role::orderBy('name')->get();
        
        return view('admin.users.show', compact('user', 'roles'));
    }

    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::orderBy('name')->get();
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }

            $user->syncRoles($validated['roles'] ?? []);

            DB::commit();
            
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function assignRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            $user->syncRoles($validated['roles']);
            
            return redirect()
                ->back()
                ->with('success', 'Roles assigned successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error assigning roles: ' . $e->getMessage());
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            $currentStatus = $user->status ?? 'active';
            $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';
            
            $user->update(['status' => $newStatus]);
            
            return redirect()
                ->back()
                ->with('success', 'User status updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error updating user status: ' . $e->getMessage());
        }
    }
}
