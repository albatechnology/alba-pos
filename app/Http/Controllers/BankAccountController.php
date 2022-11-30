<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Models\BankAccount;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BankAccountController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:bank_accounts_access', ['only' => 'index']);
        $this->middleware('permission:bank_accounts_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:bank_accounts_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:bank_accounts_delete', ['only' => ['destroy', 'massDestroy']]);
    }

    public function index($id, Request $request)
    {
        $type = $request->route()->parameterNames()[0];

        if ($request->ajax()) {
            $data = BankAccount::whereId($id)->whereType('App\Models\\' . ucfirst($type))->select(sprintf('%s.*', (new BankAccount())->table));

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('actions', function ($row) use ($id, $type) {
                    $prefixRoute = $id;
                    $viewGate      = 'bank_accounts_show';
                    $editGate      = 'bank_accounts_edit';
                    $deleteGate    = 'bank_accounts_delete';
                    $crudRoutePart = $type . 's.bank-accounts';
                    return view('layouts.includes.nestedDatatablesActions', compact('row', 'viewGate', 'editGate', 'deleteGate', 'crudRoutePart', 'prefixRoute'));
                    // , 'editGate', 'deleteGate', 'crudRoutePart'
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('bankAccounts.index', ['id' => $id, 'type' => $type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, Request $request)
    {
        $type = $request->route()->parameterNames()[0];

        return view('bankAccounts.create', ['id' => $id, 'type' => $type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankAccountRequest $request, $id)
    {
        BankAccount::create([
            'bank_accountable_id' => $id,
            'bank_accountable_type' => 'App\Models\Supplier',
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name
        ]);
        alert()->success('Success', 'Data created successfully');
        return redirect('suppliers/' . $id . '/bank-accounts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show($id, BankAccount $bankAccount, Request $request)
    {
        $type = $request->route()->parameterNames()[0];

        return view('bankAccounts.show', ['bankAccount' => $bankAccount, 'id' => $id, 'type' => $type]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit($id, BankAccount $bankAccount, Request $request)
    {
        $type = $request->route()->parameterNames()[0];

        return view('bankAccounts.edit', ['bankAccount' => $bankAccount, 'id' => $id, 'type' => $type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateBankAccountRequest $request, BankAccount $bankAccount)
    {
        $bankAccount->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('suppliers/' . $id . '/bank-accounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            BankAccount::destroy($id);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:bank_accounts,id',
        ]);

        BankAccount::tenanted()->whereIn('id', $request->ids)->delete();
        alert()->success('Success', 'Data deleted successfully');
        return response(null, 204);
    }
}
