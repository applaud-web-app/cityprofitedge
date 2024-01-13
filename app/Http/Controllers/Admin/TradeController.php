<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function tradeDeskSignal(Request $request){
        $pageTitle = 'Trade Desk Signal';
        $symbolArr = allTradeSymbols();
        $timeFrame = $request->time_frame ?: 5;
        $symbol = $request->symbol ?: 'CRUDEOIL';
        $todayDate = date("Y-m-d");
        try{
            $data = \DB::connection('mysql_rm')->table($symbol)->select('*')->where(['date'=>$todayDate,'timeframe'=>$timeFrame])->get();
        }catch(\Exception $e){
            $data = [];
        }
        return view('admin.trade.trade-desk-signal', compact('pageTitle','symbolArr','data','timeFrame','symbol'));
     
    }
    public function tradePosition(){
        $pageTitle = 'Trade Position';
        return view('admin.trade.trade-position', compact('pageTitle'));
     
    }
    public function brokerDetails(){
        $pageTitle = 'Broker Details';
        return view('admin.trade.broker-details', compact('pageTitle'));
     
    }
    public function orderBook(){
        $pageTitle = 'Order book';
        return view('admin.trade.order-book', compact('pageTitle'));
     
    }
    public function omsConfig(){
        $pageTitle = 'OMS Config';
        return view('admin.trade.oms-config', compact('pageTitle'));
     
    }
}
