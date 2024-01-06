<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PortfolioTopGainerTemplateExport;
use App\Exports\PortfolioTopLoserTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\PortfolioTopGainerDataImport;
use App\Imports\PortfolioTopLoserDataImport;
use App\Models\PortfolioTopGainer;
use App\Models\PortfolioTopLoser;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PortfolioInsightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTopGainers()
    {
        $pageTitle = 'All Portfolio Top Gainers';
        $portfolioTopGainers = PortfolioTopGainer::paginate(getPaginate());
        return view('admin.insights.top_gainer.all', compact('pageTitle', 'portfolioTopGainers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addTopGainers()
    {
        $pageTitle = 'Add Portfolio Top Gainer';
        return view('admin.insights.top_gainer.add', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSubmitTopGainers(Request $request)
    {
        // dd($request);
        $request->validate([
            'stock_name' => 'required|max:250',
            'avg_buy_price' => 'required',
            'cmp' => 'required',
            'change_percentage' => 'required',
        ]);

        $portfolioTopGainer = new PortfolioTopGainer();
        $portfolioTopGainer->stock_name = $request->stock_name;
        $portfolioTopGainer->avg_buy_price = $request->avg_buy_price;
        $portfolioTopGainer->cmp = $request->cmp;
        $portfolioTopGainer->change_percentage = $request->change_percentage;
        $portfolioTopGainer->save();

        $notify[] = ['success', 'Portfolio Top Gainer added successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateTopGainersDownload()
    {
        $fileName = 'portfolio_top_gainer_excel_template.xlsx';

        return Excel::download(new PortfolioTopGainerTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadTopGainers(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new PortfolioTopGainerDataImport, $file);

            $notify[] = ['success', 'Portfolio Top Gainers data imported successfully'];
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
    public function allTopLosers()
    {
        $pageTitle = 'All Portfolio Top Losers';
        $portfolioTopLosers = PortfolioTopLoser::paginate(getPaginate());
        return view('admin.insights.top_loser.all', compact('pageTitle', 'portfolioTopLosers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addTopLosers()
    {
        $pageTitle = 'Add Portfolio Top Loser';
        return view('admin.insights.top_loser.add', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSubmitTopLosers(Request $request)
    {
        // dd($request);
        $request->validate([
            'stock_name' => 'required|max:250',
            'avg_buy_price' => 'required',
            'cmp' => 'required',
            'change_percentage' => 'required',
        ]);

        $portfolioTopLoser = new PortfolioTopLoser();
        $portfolioTopLoser->stock_name = $request->stock_name;
        $portfolioTopLoser->avg_buy_price = $request->avg_buy_price;
        $portfolioTopLoser->cmp = $request->cmp;
        $portfolioTopLoser->change_percentage = $request->change_percentage;
        $portfolioTopLoser->save();

        $notify[] = ['success', 'Portfolio Top Gainer added successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateTopLosersDownload()
    {
        $fileName = 'portfolio_top_loser_excel_template.xlsx';

        return Excel::download(new PortfolioTopLoserTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadTopLosers(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new PortfolioTopLoserDataImport, $file);

            $notify[] = ['success', 'Portfolio Top Gainers data imported successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
