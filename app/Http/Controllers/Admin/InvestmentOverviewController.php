<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FOPortfolioHedgingTemplateExport;
use App\Exports\GlobalStockPortfolioTemplateExport;
use App\Exports\MetalsPortfolioTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\ThematicPortfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ThematicPortfolioTemplateExport;
use App\Imports\FOPortfolioHedgingDataImport;
use App\Imports\GlobalStockPortfolioDataImport;
use App\Imports\MetalsPortfolioDataImport;
use App\Imports\ThematicPortfolioDataImport;
use App\Models\FOPortfolios;
use App\Models\GlobalStockPortfolio;
use App\Models\MetalsPortfolio;

class InvestmentOverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allThematicPortfolios()
    {
        $pageTitle = 'All Thematic Portfolios';
        $thematicPortfolios = ThematicPortfolio::paginate(getPaginate());
        return view('admin.investments.thematic.all', compact('pageTitle', 'thematicPortfolios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addThematicPortfolios()
    {
        $pageTitle = 'Add Thematic Portfolios';
        return view('admin.investments.thematic.add', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSubmitThematicPortfolios(Request $request)
    {
        // dd($request);
        $request->validate([
            'stock_name' => 'required|max:250',
            'reco_date' => 'required',
            'buy_price' => 'required',
            'cmp' => 'required',
            'pnl' => 'required',
            'sector' => 'required',
        ]);

        $thematicPortfolio = new ThematicPortfolio();
        $thematicPortfolio->stock_name = $request->stock_name;
        $thematicPortfolio->reco_date = $request->reco_date;
        $thematicPortfolio->buy_price = $request->buy_price;
        $thematicPortfolio->cmp = $request->cmp;
        $thematicPortfolio->pnl = $request->pnl;
        $thematicPortfolio->sector = $request->sector;
        $thematicPortfolio->save();

        $notify[] = ['success', 'Thematic Portfolio added successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateThematicPortfolioDownload()
    {
        $fileName = 'thematic_portfolio_excel_template.xlsx';

        return Excel::download(new ThematicPortfolioTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadThematicPortfolios(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new ThematicPortfolioDataImport, $file);

            $notify[] = ['success', 'Thematic Portfolio data imported successfully'];
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
    public function allFoPortfolioHedging()
    {
        $pageTitle = 'All F&O Portfolio Hedging';
        $foPortFolioHedgings = FOPortfolios::with(['user', 'poolingAccountPortfolio'])->paginate(getPaginate());
        return view('admin.investments.foprortfolio.all', compact('pageTitle', 'foPortFolioHedgings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addFoPortfolioHedging()
    {
        $pageTitle = 'Add F&O Portfolio Hedging';
        return view('admin.investments.foprortfolio.add', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSubmitFoPortfolioHedging(Request $request)
    {
        // dd($request);
        $request->validate([
            'stock_name' => 'required|max:250',
            'reco_date' => 'required',
            'buy_price' => 'required',
            'cmp' => 'required',
            'pnl' => 'required',
            'sector' => 'required',
        ]);

        $thematicPortfolio = new FOPortfolios();
        $thematicPortfolio->stock_name = $request->stock_name;
        $thematicPortfolio->reco_date = $request->reco_date;
        $thematicPortfolio->buy_price = $request->buy_price;
        $thematicPortfolio->cmp = $request->cmp;
        $thematicPortfolio->pnl = $request->pnl;
        $thematicPortfolio->sector = $request->sector;
        $thematicPortfolio->save();

        $notify[] = ['success', 'F&O Portfolio Hedging added successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateFoPortfolioHedgingDownload()
    {
        $fileName = 'fo_portfolio_excel_template.xlsx';

        return Excel::download(new FOPortfolioHedgingTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadFoPortfolioHedging(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new FOPortfolioHedgingDataImport, $file);

            $notify[] = ['success', 'F&O Portfolio Hedging data imported successfully'];
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
    public function allMetalsPortfolios()
    {
        $pageTitle = 'All Metals Portfolios (Gold & Silver)';
        $metalsPortfolios = MetalsPortfolio::with(['user', 'poolingAccountPortfolio'])->paginate(getPaginate());
        return view('admin.investments.metals.all', compact('pageTitle', 'metalsPortfolios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addMetalsPortfolios()
    {
        $pageTitle = 'Add Metals Portfolios';
        return view('admin.investments.metals.add', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSubmitMetalsPortfolios(Request $request)
    {
        // dd($request);
        $request->validate([
            'stock_name' => 'required|max:250',
            'reco_date' => 'required',
            'buy_price' => 'required',
            'cmp' => 'required',
            'pnl' => 'required',
            'sector' => 'required',
        ]);

        $thematicPortfolio = new MetalsPortfolio();
        $thematicPortfolio->stock_name = $request->stock_name;
        $thematicPortfolio->reco_date = $request->reco_date;
        $thematicPortfolio->buy_price = $request->buy_price;
        $thematicPortfolio->cmp = $request->cmp;
        $thematicPortfolio->pnl = $request->pnl;
        $thematicPortfolio->sector = $request->sector;
        $thematicPortfolio->save();

        $notify[] = ['success', 'Metals Portfolio added successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateMetalsPortfolioDownload()
    {
        $fileName = 'metals_portfolio_excel_template.xlsx';

        return Excel::download(new MetalsPortfolioTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadMetalsPortfolios(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new MetalsPortfolioDataImport, $file);

            $notify[] = ['success', 'Metals Portfolio data imported successfully'];
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
    public function allGlobalStockPortfolios()
    {
        $pageTitle = 'All Global Stock Portfolios';
        $globalStockPortfolios = GlobalStockPortfolio::with(['user', 'poolingAccountPortfolio'])->paginate(getPaginate());
        return view('admin.investments.global.all', compact('pageTitle', 'globalStockPortfolios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addGlobalStockPortfolios()
    {
        $pageTitle = 'Add Global Stock  Portfolios';
        return view('admin.investments.global.add', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSubmitGlobalStockPortfolios(Request $request)
    {
        // dd($request);
        $request->validate([
            'stock_name' => 'required|max:250',
            'reco_date' => 'required',
            'buy_price' => 'required',
            'cmp' => 'required',
            'pnl' => 'required',
            'sector' => 'required',
        ]);

        $thematicPortfolio = new GlobalStockPortfolio();
        $thematicPortfolio->stock_name = $request->stock_name;
        $thematicPortfolio->reco_date = $request->reco_date;
        $thematicPortfolio->buy_price = $request->buy_price;
        $thematicPortfolio->cmp = $request->cmp;
        $thematicPortfolio->pnl = $request->pnl;
        $thematicPortfolio->sector = $request->sector;
        $thematicPortfolio->save();

        $notify[] = ['success', 'Global Stock Portfolio added successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Download the template for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function templateGlobalStockPortfolioDownload()
    {
        $fileName = 'global_stock_portfolio_excel_template.xlsx';

        return Excel::download(new GlobalStockPortfolioTemplateExport, $fileName);
    }

    /**
     * Upload the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadGlobalStockPortfolios(Request $request)
    {
        $request->validate([
            'xlsFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('xlsFile');

        try {
            Excel::import(new GlobalStockPortfolioDataImport, $file);

            $notify[] = ['success', 'Global Stock Portfolio data imported successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function deleteThematicPortfolio(Request $request)
    {
        $request->validate([
            'id' => 'required|required',
        ]);

        $thematicPortfolio = ThematicPortfolio::findOrFail($request->id);
        $thematicPortfolio->delete();

        $notify[] = ['success', 'Record deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function deleteFoPortfolioHedging(Request $request)
    {
        $request->validate([
            'id' => 'required|required',
        ]);

        $foPortfolioHedging = FOPortfolios::findOrFail($request->id);
        $foPortfolioHedging->delete();

        $notify[] = ['success', 'Record deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function deleteMetalsPortfolio(Request $request)
    {
        $request->validate([
            'id' => 'required|required',
        ]);

        $metalsPortfolio = MetalsPortfolio::findOrFail($request->id);
        $metalsPortfolio->delete();

        $notify[] = ['success', 'Record deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function deleteGlobalStockPortfolio(Request $request)
    {
        $request->validate([
            'id' => 'required|required',
        ]);

        $globalStockPortfolio = GlobalStockPortfolio::findOrFail($request->id);
        $globalStockPortfolio->delete();

        $notify[] = ['success', 'Record deleted Successfully'];
        return back()->withNotify($notify);
    }
}
