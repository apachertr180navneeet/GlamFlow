<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Hash, Validator, Exception, DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view users')->only(['index', 'show']);
        $this->middleware('permission:create users')->only(['create', 'store']);
        $this->middleware('permission:edit users')->only(['edit', 'update']);
        $this->middleware('permission:delete users')->only('destroy');
    }

    public function index()
    {
        $users = User::with(['roles', 'store', 'branch'])->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $stores = \App\Models\Store::where('status', true)->get();
        $branches = \App\Models\Branch::where('status', true)->get();
        return view('admin.users.create', compact('roles', 'stores', 'branches'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'store_id' => 'required|exists:stores,id',
                'branch_id' => 'required|exists:branches,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'password' => 'required|min:6',
                'role' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();
            $user = User::create([
                'store_id' => $request->store_id,
                'branch_id' => $request->branch_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'slug' => \Str::slug($request->first_name . '-' . $request->last_name),
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country ?? '',
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);
            $user->assignRole($request->role);
            DB::commit();

            logActivity('Created', 'User', "Created user {$user->full_name}");
            return redirect()->route('admin.users.index')->with('success', 'User created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $stores = \App\Models\Store::where('status', true)->get();
        $branches = \App\Models\Branch::where('status', true)->get();
        return view('admin.users.edit', compact('user', 'roles', 'stores', 'branches'));
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'store_id' => 'required|exists:stores,id',
                'branch_id' => 'required|exists:branches,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'required|unique:users,phone,' . $id,
                'role' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();
            $user->update([
                'store_id' => $request->store_id,
                'branch_id' => $request->branch_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status ?? $user->status,
            ]);

            if ($request->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $user->syncRoles([$request->role]);
            DB::commit();

            logActivity('Updated', 'User', "Updated user {$user->full_name}");
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            logActivity('Deleted', 'User', "Deleted user {$user->full_name}");
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
