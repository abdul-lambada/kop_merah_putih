<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissions.view')->only(['index', 'show']);
        $this->middleware('permission:permissions.create')->only(['create', 'store']);
        $this->middleware('permission:permissions.edit')->only(['edit', 'update']);
        $this->middleware('permission:permissions.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $permissions = Permission::with('roles')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->group, function ($query, $group) {
                $query->where('name', 'like', "{$group}.%");
            })
            ->orderBy('name')
            ->paginate(10);

        $groups = $this->getPermissionGroups();
        
        return view('admin.permissions.index', compact('permissions', 'groups'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $groups = $this->getPermissionGroups();
        
        return view('admin.permissions.create', compact('roles', 'groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $permission = Permission::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);

            if (!empty($validated['roles'])) {
                $permission->syncRoles($validated['roles']);
            }

            DB::commit();
            
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error creating permission: ' . $e->getMessage());
        }
    }

    public function show(Permission $permission)
    {
        $permission->load('roles');
        
        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $permission->load('roles');
        $roles = Role::orderBy('name')->get();
        $groups = $this->getPermissionGroups();
        
        return view('admin.permissions.edit', compact('permission', 'roles', 'groups'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions')->ignore($permission->id),
            ],
            'description' => 'nullable|string|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $permission->update([
                'name' => $validated['name'],
            ]);

            $permission->syncRoles($validated['roles'] ?? []);

            DB::commit();
            
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error updating permission: ' . $e->getMessage());
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting permission: ' . $e->getMessage());
        }
    }

    public function assignRoles(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            $permission->syncRoles($validated['roles']);
            
            return redirect()
                ->back()
                ->with('success', 'Roles assigned successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error assigning roles: ' . $e->getMessage());
        }
    }

    public function removeRole(Permission $permission, Role $role)
    {
        try {
            $permission->removeRole($role);
            
            return redirect()
                ->back()
                ->with('success', 'Role removed successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error removing role: ' . $e->getMessage());
        }
    }

    private function getPermissionGroups()
    {
        $permissions = Permission::pluck('name')->toArray();
        $groups = [];
        
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            if (count($parts) > 1) {
                $groups[] = $parts[0];
            }
        }
        
        return array_unique($groups);
    }
}
