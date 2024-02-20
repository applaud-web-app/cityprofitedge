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
    public function allTopGainers(Request $request)
    {
        $pageTitle = 'All Portfolio Top Gainers';
        $portfolioTopGainers = PortfolioTopGainer::orderBy('id','ASC');
        $stockName = 'all';

        if(!empty($request->stock_name) && $request->stock_name!='all'){
            $portfolioTopGainers->where('stock_name',$request->stock_name);
            $stockName = $request->stock_name;
        }
        $portfolioTopGainers = $portfolioTopGainers->paginate(getPaginate());
        
        $symbolArray = [];
        foreach ($portfolioTopGainers as $val) {
           array_push($symbolArray , $val['stock_name'].".NS");
        }
        return view('admin.insights.top_gainer.all', compact('pageTitle', 'portfolioTopGainers','stockName','symbolArray'));

    }

    public function getTopGainers(Request $request){
        $term = $request->term;
        $data = [];
        if(!empty($term)){
            $data = PortfolioTopGainer::select('id','stock_name')->where('stock_name','like','%'.$term.'%')->limit(10)->groupBy('stock_name')->get();
        }
        return response()->json($data);
    }

    public function removeTopGainers(Request $request){
        $data = $request->data;
        if(!empty($data)){
            PortfolioTopGainer::whereIn('id',$data)->delete();
        }        
        $notify[] = ['success', 'Portfolio Top Gainers deleted successfully'];
        return back()->withNotify($notify);
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
    public function allTopLosers(Request $request)
    {
        $pageTitle = 'All Portfolio Top Losers';
        $portfolioTopLosers = PortfolioTopLoser::orderBy('id','ASC');
        $stockName = 'all';

        if(!empty($request->stock_name) && $request->stock_name!='all'){
            $portfolioTopLosers->where('stock_name',$request->stock_name);
            $stockName = $request->stock_name;
        }
        
        $portfolioTopLosers = $portfolioTopLosers->paginate(getPaginate());
        
        $symbolArray = [];
        foreach ($portfolioTopLosers as $val) {
           array_push($symbolArray , $val['stock_name'].".NS");
        }

        return view('admin.insights.top_loser.all', compact('pageTitle', 'portfolioTopLosers','stockName','symbolArray'));


    }

    public function getTopLosers(Request $request){
        $term = $request->term;
        $data = [];
        if(!empty($term)){
            $data = PortfolioTopLoser::select('id','stock_name')->where('stock_name','like','%'.$term.'%')->limit(10)->groupBy('stock_name')->get();
        }
        return response()->json($data);
    }

    public function removeTopLosers(Request $request){
        $data = $request->data;
        if(!empty($data)){
            PortfolioTopLoser::whereIn('id',$data)->delete();
        }        
        $notify[] = ['success', 'Portfolio Top Losers deleted successfully'];
        return back()->withNotify($notify);
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

    /**
     * Delete a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteTopGainer(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $portfolioTopGainer = PortfolioTopGainer::findOrFail($request->id);
        $portfolioTopGainer->delete();

        $notify[] = ['success', 'Portfolio Top Gainer deleted successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Delete a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteTopLoser(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $portfolioTopLoser = PortfolioTopLoser::findOrFail($request->id);
        $portfolioTopLoser->delete();

        $notify[] = ['success', 'Portfolio Top Loser deleted successfully'];
        return back()->withNotify($notify);
    }
}
