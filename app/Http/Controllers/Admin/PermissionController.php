<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Validator, Exception;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permissions')->only(['index', 'show']);
        $this->middleware('permission:create permissions')->only(['create', 'store']);
        $this->middleware('permission:edit permissions')->only(['edit', 'update']);
        $this->middleware('permission:delete permissions')->only('destroy');
    }

    public function index()
    {
        $permissions = Permission::latest()->paginate(10);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:permissions,name',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            Permission::create(['name' => $request->name]);
            logActivity('Created', 'Permission', "Created permission {$request->name}");
            return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:permissions,name,' . $id,
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $permission->update(['name' => $request->name]);
            logActivity('Updated', 'Permission', "Updated permission {$permission->name}");
            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            logActivity('Deleted', 'Permission', "Deleted permission {$permission->name}");
            return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
