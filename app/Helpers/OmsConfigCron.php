<?php
namespace App\Helpers;
use App\Models\OmsConfig;
use App\Helpers\KiteConnectCls;
use App\Models\OrderBook;

class OmsConfigCron{
    
    public function __construct()
    {
        set_time_limit(0);
    }

    public function getCeLimitPrice($high,$low,$per,$type){
        $diff = ($high - $low) * ($per/100);
        if($type=="BUY"){
            $price = $high - $diff;
        }else{
            $price = $high + $diff;
        }
        
        $finalPrice = round($price,2);
        return $finalPrice;
    }

    public function getPeLimitPrice($high,$low,$per,$type){
        $diff = ($high - $low) * ($per/100);
        if($type=="BUY"){
            $price = $high - $diff;
        }else{
            $price = $high + $diff;
        }
        $finalPrice = round($price,2);
        return $finalPrice;
    }

    public function postPlaceOrder(object $broker,array $apiData){
        // dd($apiData);
        $params = [
            'accountUserName'=>$broker->account_user_name,
            'accountPassword'=>$broker->account_password,
            'totpSecret'=>$broker->totp,
            'apiKey'=>$broker->api_key,
            'apiSecret'=>$broker->api_secret_key
        ];
        // dd($params);
        $kiteObj = new KiteConnectCls($params);
        $kite = \Cache::remember('KITE_AUTH_'.$broker->account_user_name, 18000, function () use($kiteObj,$broker) {
            $pythonScript = '/home/forge/cityprofitedge.com/public/kite_login/app.py -u '.$broker->account_user_name;
            $command = 'python3 ' . $pythonScript; 
            exec($command, $output, $exitCode);
            echo "Output:\n" . implode("\n", $output) . "\n";
            echo "Exit Code: $exitCode\n";die;

            $kite = $kiteObj->generateSessionManual($broker->request_token);
            return $kite;
        });
        try{
            $order = $kite->placeOrder("regular", $apiData);
            sleep(3);
            $orderData = $kite->getOrderHistory($order->order_id);
            $lastD = array_slice($orderData,-1);
            
            $bookOBj = new OrderBook();
            $bookOBj->broker_username = $lastD[0]->placed_by;
            $bookOBj->order_id = $lastD[0]->order_id;
            $bookOBj->status = $lastD[0]->status;
            $bookOBj->trading_symbol = $lastD[0]->tradingsymbol;
            $bookOBj->order_type =  $lastD[0]->order_type;
            $bookOBj->transaction_type = $lastD[0]->transaction_type;
            $bookOBj->product = $lastD[0]->product;
            $bookOBj->price = $lastD[0]->price;
            $bookOBj->quantity = $lastD[0]->quantity;
            $bookOBj->status_message = $lastD[0]->status_message;
            $bookOBj->order_datetime = $lastD[0]->order_timestamp->format('Y-m-d H:i:s');
            $bookOBj->user_id = $broker->user_id;
            $bookOBj->save();
        }catch(\Exception $e){
            \Cache::forget('KITE_AUTH_'.$broker->account_user_name);
        }
    }

    public function callKiteApi($signalData,object $omsData){
        $mcxSymArr = ['CRUDEOIL','NATURALGAS','GOLD','SILVER'];
        $txnType = $omsData->txn_type;
        $fData = [
            "exchange" => in_array($omsData->symbol_name,$mcxSymArr) ? "MCX" : "NFO",//crude oil,naturalgas,gold,silver--mcx .. remaining --NFO
            "transaction_type" => $txnType,
            "order_type" => $omsData->order_type,
            "product" => $omsData->product
        ];

        $breakForeach = 0;

        foreach($signalData as $vvl){
            if($breakForeach == 1){
                break;
            }
            $data = json_decode($vvl->data,true);
            $strategyArr = array_reverse(array_slice($data['Strategy_name'],-72));
            $highCEArr = array_reverse(array_slice($data['high_CE'],-72));
            $lowCEArr = array_reverse(array_slice($data['low_CE'],-72));
            $highPEArr = array_reverse(array_slice($data['high_PE'],-72));
            $lowPEArr = array_reverse(array_slice($data['low_PE'],-72));
            foreach($strategyArr as $key=>$v){
                if(strtolower($v)==strtolower($omsData->strategy_name)){
                    $high = $highCEArr[$key];
                    $low = $lowCEArr[$key];
                    $fData["tradingsymbol"] = $omsData->ce_symbol_name;
                    if(!is_null($omsData->ce_pyramid_1)){
                       $price =  $this->getCeLimitPrice($high,$low,38.20,$txnType);
                       $fData['price'] = $price;
                       $fData['quantity'] = $omsData->ce_pyramid_1;
                       $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->ce_pyramid_2)){
                        //50%
                        $price =  $this->getCeLimitPrice($high,$low,50,$txnType);
                        $fData['price'] = $price;
                        $fData['quantity'] = $omsData->ce_pyramid_2;
                        $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->ce_pyramid_3)){
                        $price =  $this->getCeLimitPrice($high,$low,61.80,$txnType);
                        $fData['price'] = $price;
                        $fData['quantity'] = $omsData->ce_pyramid_3;
                        $this->postPlaceOrder($omsData->broker,$fData);
                    }

                    //
                    $fData["tradingsymbol"] = $omsData->pe_symbol_name;
                    $high = $highPEArr[$key];
                    $low = $lowPEArr[$key];
                    if(!is_null($omsData->pe_pyramid_1)){
                        $price =  $this->getPeLimitPrice($high,$low,38.20,$txnType);
                        $fData['price'] = $price;
                        $fData['quantity'] = $omsData->pe_pyramid_1;
                        $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->pe_pyramid_2)){
                        $price =  $this->getPeLimitPrice($high,$low,50,$txnType);
                        $fData['price'] = $price;
                        $fData['quantity'] = $omsData->pe_pyramid_2;
                        $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->pe_pyramid_3)){
                        $price =  $this->getPeLimitPrice($high,$low,61.80,$txnType);
                        $fData['price'] = $price;
                        $fData['quantity'] = $omsData->pe_pyramid_3;
                        $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    OmsConfig::where("id",$omsData->id)->update([
                        'is_api_pushed'=>1
                    ]);
                    $breakForeach = 1;
                    break;
                }
            }
        }
        OmsConfig::where("id",$omsData->id)->update([
            'cron_run_at'=>date("Y-m-d H:i:s",strtotime($omsData->cron_run_at.'+ '.$omsData->pyramid_freq.'minutes'))
        ]);
    }

    public function placeOrder(){
        $todayDate=date("Y-m-d");
        // $todayDate="2024-01-29";
        OmsConfig::select('*')->with('broker')
        ->where('is_api_pushed',0)
        ->chunk(100, function($omgData) use($todayDate){
            foreach ($omgData as $val) {
                $signalData = \DB::connection('mysql_rm')->table($val->symbol_name)->select('*')->where(['date'=>$todayDate,'timeframe'=>$val->signal_tf])->get();
                $pFreq = "-".$val->pyramid_freq." minutes";
                $nextRun = strtotime(date("Y-m-d H:i:s",strtotime($pFreq)));
                $lstRun = strtotime($val->cron_run_at);
                if($nextRun > $lstRun){
                    if(count($signalData)){
                        $omsData = $val;                       
                        $this->callKiteApi($signalData,$omsData);
                    }
                }
            }
        });


    }
}