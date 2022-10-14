<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Models\BankAccount;
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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BankAccount::select(sprintf('%s.*', (new BankAccount())->table));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('placeholder', '&nbsp;')
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->updated_at));
                })
                ->addColumn('actions', function ($row) {
                    // $viewGate      = 'bank_accounts_show';
                    $editGate      = 'bank_accounts_edit';
                    $deleteGate    = 'bank_accounts_delete';
                    $crudRoutePart = 'bank-accounts';
                    return view('layouts.includes.datatablesActions', compact('row', 'editGate', 'deleteGate', 'crudRoutePart'));
                })
                ->rawColumns(['placeholder', 'actions'])
                ->make(true);
        }
        return view('bankAccounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bankAccounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankAccountRequest $request)
    {
        BankAccount::create($request->validated());
        alert()->success('Success', 'Data created successfully');
        return redirect('bank-accounts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
        return view('bankAccounts.edit', ['bankAccount' => $bankAccount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankAccountRequest $request, BankAccount $bankAccount)
    {
        $bankAccount->update($request->validated());

        alert()->success('Success', 'Data updated successfully');
        return redirect('customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        try {
            $bankAccount->delete();
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess('Data deleted successfully');
    }
}
