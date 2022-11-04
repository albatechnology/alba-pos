<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{

    public function index()
    {
        $company = Company::simplePaginate();
        return response()->json($company);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies,name',
        ]);

        $company = Company::create(['name' => $request->name]);

        return response()->json($company, 201);
    }

    public function update(Request $request, Company $company)
    {
        $request = $request->validate([
            'name' => 'required|unique:companies,name,' . $company->id,
        ]);
        $company->update($request);

        return response()->json($company);
    }

    public function destroy(Company $company)
    {

        if ($company->id == 1) return response()->json([
            'errors' => "This company can't be deleted",
        ], 422);

        $company->delete();
        return response()->json(['message' => 'Company Deleted'], 200);
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
