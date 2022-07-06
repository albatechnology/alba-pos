<?php

namespace App\Http\Controllers;

use App\Exports\PermissionsExport;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyUserOfCompletedExport;
use App\Models\Export;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:permissions_access', ['only' => 'index']);
        $this->middleware('permission:permissions_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permissions_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permissions_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::orderByDesc('id');
            return DataTables::of($data)->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return date('d-m-Y H:i', strtotime($row->created_at));
            })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'permission-edit';
                    $deleteGate    = 'permission-delete';
                    $crudRoutePart = 'permissions';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('permissions.index');
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect('permissions')->withStatus($this->flash_data('success', __('global.created_successfully')));
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request = $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);
        $permission->update($request);

        return redirect('permissions')->withStatus($this->flash_data('success', __('global.updated_successfully')));
    }

    public function destroy($id)
    {
        try {
            Permission::destroy($id);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess(__('global.deleted_successfully'));
    }

    public function export()
    {
        $dir = 'public/exports/';
        $fileName = 'permissions-' . date('dmyhis') . '.csv';
        $export = Export::create([
            'created_by' => auth()->user()->id,
            'file_name' => $fileName,
            'file_url' => $dir . $fileName,
            'description' => 'okeoke bos',
        ]);

        (new PermissionsExport($export))->store($dir . $fileName)->chain([
            new NotifyUserOfCompletedExport(auth()->user(), $export),
        ]);

        // return Excel::download(new PermissionsExport, 'permissions.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
