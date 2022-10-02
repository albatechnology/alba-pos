<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles_access', ['only' => 'index']);
        $this->middleware('permission:roles_view', ['only' => 'show']);
        $this->middleware('permission:roles_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::tenanted()->with('company')->select(sprintf('%s.*', (new Role)->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->addColumn('company_name', function ($row) {
                    return $row->company?->name ?? '';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
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
                ->rawColumns(['placeholder', 'actions', 'permissions'])
                ->make(true);
        }
        return view('roles.index');
    }

    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        $permissions = Permission::whereNull('parent_id')->get();

        return view('roles.create', ['companies' => $companies, 'permissions' => $permissions]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'company_id' => [function ($attribute, $value, $fail) {
                if (!auth()->user()->is_super_admin) {
                    if (!$value) $fail('Company ID is required');
                    if (Company::where('id', $value)->doesntExist()) $fail('The selected company id is invalid');
                }
            }]
        ]);

        $role = Role::create(['name' => $request->name, 'company_id' => $request->company_id]);
        $role->syncPermissions($request->permissions ?? []);
        alert()->success('Success', 'Data created successfully');
        return redirect('roles');
    }

    public function edit(Role $role)
    {
        $companies = Company::tenanted()->pluck('name', 'id')->prepend('- Select Company -', '');
        $rolePermissions = $role->permissions->pluck('id')->all();
        $permissions = Permission::whereNull('parent_id')->get();

        return view('roles.edit', ['role' => $role, 'companies' => $companies, 'permissions' => $permissions, 'rolePermissions' => $rolePermissions]);
    }

    public function update(Request $request, $id)
    {
        // dd(!auth()->user()->is_super_admin);
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'company_id' => [function ($attribute, $value, $fail) {
                if (!auth()->user()->is_super_admin) {
                    if (!$value) $fail('Company ID is required');
                    if (Company::where('id', $value)->doesntExist()) $fail('The selected company id is invalid');
                }
            }]
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->company_id = $request->company_id;
        $role->save();
        $role->syncPermissions($request->permissions ?? []);
        alert()->success('Success', 'Data updated successfully');
        return redirect('roles');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->id, [1, 2])) return $this->ajaxError('This role can not deleted!');
        try {
            $role->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:companies,id',
        ]);

        Role::whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }
}
