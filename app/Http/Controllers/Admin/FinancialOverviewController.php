<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Models\StockPortfolio;
use App\Imports\LedgerDataImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\LedgerTemplateExport;
use App\Imports\PortfolioTopLoserDataImport;
use App\Exports\PortfolioTopLoserTemplateExport;
use App\Exports\StockPortfolioTemplateExport;
use App\Imports\StockPortfolioDataImport;

class FinancialOverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allLedger()
    {
        $pageTitle = 'All Ledger';
        $ledgers = Ledger::with(['user', 'poolingAccountPortfolio'])->paginate(getPaginate());
        return view('admin.financial.ledger.all', compact('pageTitle', 'ledgers'));
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateLedgerDownload()
    {
        $fileName = 'ledger_excel_template.xlsx';

        return Excel::download(new LedgerTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadLedger(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new LedgerDataImport, $file);

            $notify[] = ['success', 'Ledger data imported successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allStockPortfolio()
    {
        $pageTitle = 'All Stock Portfolio';
        $stockPortfolios = StockPortfolio::with(['user', 'poolingAccountPortfolio'])->paginate(getPaginate());
        return view('admin.financial.stock_portfolio.all', compact('pageTitle', 'stockPortfolios'));
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateStockPortfolioDownload()
    {
        $fileName = 'stock_portfolio_excel_template.xlsx';

        return Excel::download(new StockPortfolioTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadStockPortfolio(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new StockPortfolioDataImport, $file);

            $notify[] = ['success', 'Stock Portfolio data imported successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
