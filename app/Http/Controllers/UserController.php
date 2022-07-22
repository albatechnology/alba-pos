<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Media;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use Media;

    function __construct()
    {
        $this->middleware('permission:users_access', ['only' => 'index']);
        $this->middleware('permission:users_view', ['only' => 'show']);
        $this->middleware('permission:users_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();
            $table = Datatables::eloquent($query);
            $table->addColumn('placeholder', '&nbsp;')->editColumn('actions', function ($row) {
                $viewGate      = true;
                $editGate      = true;
                $deleteGate    = true;
                $crudRoutePart = 'users';

                return view('layouts.includes.datatablesActions', compact(
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->rawColumns(['placeholder', 'actions']);

            return $table->make(true);
        }
        return view('users.index');
    }

    public function create()
    {
        $companies = tenancy()->getCompanies()->pluck('name','id');
        return view('users.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:1024',
            'company_ids' => 'required|array',
            'company_ids.*' => 'integer|exists:companies,id',
            'tenant_ids' => 'required|array',
            'tenant_ids.*' => 'integer|exists:tenants,id',
        ]);

        if ($file = $request->file('image')) {
            $fileData = $this->uploadFile($file, 'users/');
            $image_url = \App::make('url')->to('/') . '/' . $fileData['filePath'];
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => $image_url ?? null
        ]);
        $user->companies()->sync($request->company_ids ?? []);
        $user->tenants()->sync($request->tenant_ids ?? []);
        alert()->success('Success', 'Data created successfully');
        return redirect('users');
    }

    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        $companies = tenancy()->getCompanies()->pluck('name','id');
        $userCompanies = $user->companies()->pluck('id')->all();

        $tenants = tenancy()->getTenants()->pluck('name','id');
        $userTenants = $user->tenants()->pluck('id')->all();

        return view('users.edit', ['user' => $user, 'companies' => $companies, 'userCompanies' => $userCompanies, 'tenants' => $tenants, 'userTenants' => $userTenants]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:1024',
            'company_ids' => 'required|array',
            'company_ids.*' => 'integer|exists:companies,id',
            'tenant_ids' => 'required|array',
            'tenant_ids.*' => 'integer|exists:tenants,id',
        ]);

        if ($file = $request->file('image')) {
            $fileData = $this->updateFile($user->photo, $file, 'users/');
            $image_url = \App::make('url')->to('/') . '/' . $fileData['filePath'];
            $user->photo = $image_url;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) $user->password = bcrypt($request->password);
        $user->save();
        $user->companies()->sync($request->company_ids ?? []);
        $user->tenants()->sync($request->tenant_ids ?? []);
        alert()->success('Success', 'Data updated successfully');
        return redirect('users');
    }

    public function destroy(User $user)
    {
        try {
            if ($user == user()) {
                return $this->ajaxError('Data failed to delete');
            } else {
                $user->delete();
                $this->removeFile($user->photo);
            }
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

        User::whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }
}
