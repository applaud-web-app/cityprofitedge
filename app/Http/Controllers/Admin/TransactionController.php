<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransactionTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\TransactionDataImport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTransactions()
    {
        $pageTitle = 'All Transactions';
        $transactions = Transaction::with(['user', 'poolingAccountPortfolio'])->paginate(getPaginate());
        return view('admin.transaction.all', compact('pageTitle', 'transactions'));
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateTransactionDownload()
    {
        $fileName = 'transaction_excel_template.xlsx';

        return Excel::download(new TransactionTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadTransaction(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new TransactionDataImport, $file);

            $notify[] = ['success', 'Transactions data imported successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
