<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view branches')->only(['index', 'show']);
        $this->middleware('permission:create branches')->only(['create', 'store']);
        $this->middleware('permission:edit branches')->only(['edit', 'update', 'toggleStatus']);
        $this->middleware('permission:delete branches')->only('destroy');
    }

    public function index()
    {
        $branches = \App\Models\Branch::with('store')->orderBy('id')->paginate(10);
        $stores = \App\Models\Store::where('status', 1)->get();
        return view('admin.branches.index', compact('branches', 'stores'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'store_id' => 'required|exists:stores,id',
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $data = $request->all();
            $data['status'] = $request->has('status') ? (in_array($request->status, ['active', '1', 1], true) ? 1 : 0) : 1;
            \App\Models\Branch::create($data);
            logActivity('Created', 'Branch', "Created branch {$request->name}");
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Branch created successfully']);
            }
            return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $branch = \App\Models\Branch::findOrFail($id);
        if (request()->ajax()) {
            return response()->json($branch);
        }
        $stores = \App\Models\Store::where('status', 1)->get();
        return view('admin.branches.edit', compact('branch', 'stores'));
    }

    public function update(Request $request, $id)
    {
        try {
            $branch = \App\Models\Branch::findOrFail($id);
            $validator = \Validator::make($request->all(), [
                'store_id' => 'required|exists:stores,id',
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $data = $request->all();
            if ($request->has('status')) {
                $data['status'] = in_array($request->status, ['active', '1', 1], true) ? 1 : 0;
            }
            $branch->update($data);
            logActivity('Updated', 'Branch', "Updated branch {$branch->name}");
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Branch updated successfully']);
            }
            return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $branch = \App\Models\Branch::findOrFail($id);
            $branch->status = !$branch->status;
            $branch->save();
            logActivity('Updated', 'Branch', "Toggled status of branch {$branch->name}");
            return response()->json(['success' => true, 'status' => $branch->status, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $branch = \App\Models\Branch::findOrFail($id);
            $branch->delete();
            logActivity('Deleted', 'Branch', "Deleted branch {$branch->name}");
            return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
