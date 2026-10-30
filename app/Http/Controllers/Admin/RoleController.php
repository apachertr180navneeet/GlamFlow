<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator, Exception, DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view roles')->only(['index', 'show']);
        $this->middleware('permission:create roles')->only(['create', 'store']);
        $this->middleware('permission:edit roles')->only(['edit', 'update']);
        $this->middleware('permission:delete roles')->only('destroy');
    }

    public function index()
    {
        $roles = Role::with('permissions')->latest()->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            $name = strtolower($perm->name);
            if (str_contains($name, 'report') || str_contains($name, 'ledger') || str_contains($name, 'package usage')) {
                return 'reports';
            }
            $parts = explode(' ', $perm->name);
            return count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'general';
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name',
                'permissions' => 'required|array',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permissions);
            DB::commit();

            logActivity('Created', 'Role', "Created role {$role->name}");
            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy(function ($perm) {
            $name = strtolower($perm->name);
            if (str_contains($name, 'report') || str_contains($name, 'ledger') || str_contains($name, 'package usage')) {
                return 'reports';
            }
            $parts = explode(' ', $perm->name);
            return count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'general';
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name,' . $id,
                'permissions' => 'required|array',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();
            $role->update(['name' => $request->name]);
            $role->syncPermissions($request->permissions);
            DB::commit();

            logActivity('Updated', 'Role', "Updated role {$role->name}");
            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            logActivity('Deleted', 'Role', "Deleted role {$role->name}");
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
