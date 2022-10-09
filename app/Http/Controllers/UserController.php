<?php

namespace App\Http\Controllers;

use App\Enums\UserLevelEnum;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Traits\Media;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $query = User::tenanted()->where('id', '!=', user()->id)->with(['companies', 'tenants', 'roles']);
            $table = Datatables::eloquent($query);
            $table->addColumn('companies', function ($row) {
                $html = '';
                if ($row->companies->count() > 0) {
                    foreach ($row->companies as $company) {
                        $html .= '<div class="badge badge-info">' . $company->name . '</div><br>';
                    }
                }
                return $html;
            });
            $table->addColumn('tenants', function ($row) {
                $html = '';
                if ($row->tenants->count() > 0) {
                    foreach ($row->tenants as $tenant) {
                        $html .= '<div class="badge badge-info">' . $tenant->name . '</div><br>';
                    }
                }
                return $html;
            });
            $table->addColumn('roles', function ($row) {
                $roles = DB::table('model_has_roles')->join('roles', 'roles.id', '=', 'model_has_roles.role_id')->where('model_type', 'App\Models\User')->where('model_id', $row->id)->get();
                $html = '';
                if ($roles->count() > 0) {
                    foreach ($roles as $role) {
                        $html .= '<div class="badge badge-info">' . $role->name . '</div><br>';
                    }
                }
                return $html;
            });
            $table->addColumn('placeholder', '&nbsp;')->editColumn('actions', function ($row) {
                $viewGate      = 'users_view';
                $editGate      = 'users_edit';
                $deleteGate    = 'users_delete';
                $crudRoutePart = 'users';

                return view('layouts.includes.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->rawColumns(['placeholder', 'actions', 'companies', 'tenants', 'roles']);

            return $table->make(true);
        }
        return view('users.index');
    }

    public function create()
    {
        $companies = Company::tenanted()->pluck('name', 'id');
        $roles = Role::tenanted()->pluck('name', 'id')->prepend('- Select Role -', '');
        return view('users.create', ['companies' => $companies, 'roles' => $roles]);
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
            'tenant_ids' => 'nullable|array',
            'tenant_ids.*' => 'integer|exists:tenants,id',
            'role_id' => 'required|integer|exists:roles,id',
            'level' => ['nullable', new EnumKey(UserLevelEnum::class)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => in_array(1, $request->company_ids) ? UserLevelEnum::SUPER_ADMIN : ($request->level ? $request->level : UserLevelEnum::ADMIN),
        ]);

        if ($file = $request->file('image')) {
            $user
                ->addMedia($file)
                ->usingName(str_replace(' ', '-', $request->name))
                ->toMediaCollection('users');
        }

        $user->companies()->sync($request->company_ids ?? []);
        $user->tenants()->sync($request->tenant_ids ?? []);
        foreach ($request->company_ids as $company_id) {
            $user->roles()->syncWithPivotValues($request->role_id, ['company_id' => $company_id]);
        }

        alert()->success('Success', 'Data created successfully');
        return redirect('users');
    }

    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        $companies = Company::tenanted()->pluck('name', 'id');
        $userCompanies = $user->companies()->pluck('id')->all();

        $tenants = tenancy()->getTenants()->pluck('name', 'id');
        $userTenants = $user->tenants()->pluck('id')->all();

        $roles = Role::tenanted()->pluck('name', 'id')->prepend('- Select Role -', '');
        $userRole = DB::table('model_has_roles')->where('model_type', 'App\Models\User')->where('model_id', $user->id)->first();

        return view('users.edit', ['user' => $user, 'companies' => $companies, 'userCompanies' => $userCompanies, 'tenants' => $tenants, 'userTenants' => $userTenants, 'roles' => $roles, 'userRole' => $userRole]);
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
            'tenant_ids' => 'nullable|array',
            'tenant_ids.*' => 'integer|exists:tenants,id',
            'role_id' => 'required|integer|exists:roles,id',
            'level' => ['nullable', new EnumKey(UserLevelEnum::class)],
        ]);

        if ($file = $request->file('image')) {
            $user
                ->addMedia($file)
                ->usingName(str_replace(' ', '-', $request->name))
                ->toMediaCollection('users');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->level = in_array(1, $request->company_ids) ? UserLevelEnum::SUPER_ADMIN : ($request->level ? $request->level : UserLevelEnum::ADMIN);
        if ($request->password) $user->password = bcrypt($request->password);
        $user->save();

        $user->companies()->sync($request->company_ids ?? []);
        $user->tenants()->sync($request->tenant_ids ?? []);

        DB::table('model_has_roles')->where('model_type', 'App\Models\User')->where('model_id', $user->id)->delete();
        foreach ($request->company_ids as $company_id) {
            $user->roles()->syncWithPivotValues($request->role_id, ['company_id' => $company_id]);
        }

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
