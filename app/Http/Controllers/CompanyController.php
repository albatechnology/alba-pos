<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:companies_access', ['only' => 'index']);
        $this->middleware('permission:companies_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:companies_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:companies_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::orderByDesc('id');
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'company-edit';
                    $deleteGate    = 'company-delete';
                    $crudRoutePart = 'companies';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('companies.index');
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies,name',
        ]);

        Company::create(['name' => $request->name]);

        return redirect('companies')->withStatus($this->flash_data('success', __('global.created_successfully')));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', ['company' => $company]);
    }

    public function update(Request $request, Company $company)
    {
        $request = $request->validate([
            'name' => 'required|unique:companies,name,' . $company->id,
        ]);
        $company->update($request);

        return redirect('companies')->withStatus($this->flash_data('success', __('global.updated_successfully')));
    }

    public function destroy(Company $company)
    {
        try {
            $company->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess(__('global.deleted_successfully'));
    }

    public function setActiveCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id'
        ]);

        if($validator->fails()){
            session()->forget('active-company');
        } else {
            $company = Company::findOrFail($request->company_id);
            tenancy()->setActiveCompany($company);
        }

        return redirect()->back();
    }
}
