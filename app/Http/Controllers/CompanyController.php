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
        $this->middleware('permission:companies_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::orderByDesc('id');
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    $editGate      = 'company-edit';
                    $deleteGate    = 'company-delete';
                    $crudRoutePart = 'companies';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
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
        alert()->success('Success', 'Data created successfully');
        return redirect('companies');
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
        alert()->success('Success', 'Data updated successfully');
        return redirect('companies');
    }

    public function destroy(Company $company)
    {
        try {
            $company->delete();
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

        Company::whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }

    public function setActiveCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id'
        ]);

        if ($validator->fails()) {
            session()->forget('active-company');
        } else {
            $company = Company::findOrFail($request->company_id);
            tenancy()->setActiveCompany($company);
        }

        return redirect()->back();
    }
}
