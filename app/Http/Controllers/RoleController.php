<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDO;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles_access', ['only' => 'index']);
        $this->middleware('permission:roles_view', ['only' => 'show']);
        $this->middleware('permission:roles_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::orderByDesc('id');
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('permissions', function ($row) {
                    $permissions = $row->permissions->map(function ($p) {
                        return '<span class="badge badge-info">' . $p->name . '</span>';
                    })->all();
                    return implode(' ', $permissions);
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'role-edit';
                    $deleteGate    = 'role-delete';
                    $crudRoutePart = 'roles';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['actions', 'permissions'])
                ->make(true);
        }
        return view('roles.index');
    }

    public function create()
    {
        $permissions = Permission::pluck('name', 'id');
        return view('roles.create', ['permissions' => $permissions]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect('roles')->withStatus($this->flash_data('success', __('global.created_successfully')));
    }

    public function edit(Role $role)
    {
        $rolePermissions = $role->permissions->pluck('id')->all();
        $permissions = Permission::pluck('name', 'id');
        return view('roles.edit', ['role' => $role, 'permissions' => $permissions, 'rolePermissions' => $rolePermissions]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permissions ?? []);

        return redirect('roles')->withStatus($this->flash_data('success', __('global.updated_successfully')));
    }

    public function destroy($id)
    {
        try {
            Role::destroy($id);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess(__('global.deleted_successfully'));
    }
}
