<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function tradeDeskSignal(){
        $pageTitle = 'Trade Desk Signal';
        return view('admin.trade.trade-desk-signal', compact('pageTitle'));
     
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
