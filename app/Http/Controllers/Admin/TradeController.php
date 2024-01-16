<?php

namespace App\Http\Controllers\Admin;

require_once app_path('kiteconnect/autoload.php');

use KiteConnect\KiteConnect;
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
        

        // Initialise.
        $kite = new KiteConnect("your_api_key");

        // Assuming you have obtained the `request_token`
        // after the auth flow redirect by redirecting the
        // user to $kite->login_url()
        try {
            $user = $kite->generateSession("request_token_obtained", "your_api_secret");
            echo "Authentication successful. \n";
            print_r($user);
            $kite->setAccessToken($user->access_token);
        } catch(Exception $e) {
            echo "Authentication failed: ".$e->getMessage();
            throw $e;
        }

        echo $user->user_id." has logged in";

        // Get the list of positions.
        echo "Positions: \n";
        print_r($kite->getPositions());

        // Place order.
        $order = $kite->placeOrder("regular", [
            "tradingsymbol" => "INFY",
            "exchange" => "NSE",
            "quantity" => 1,
            "transaction_type" => "BUY",
            "order_type" => "MARKET",
            "product" => "NRML"
        ]);

        echo "Order id is ".$order->order_id;
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
