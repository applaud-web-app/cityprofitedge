<?php
namespace App\Helpers;
use App\Models\ZerodhaInstrument;
use App\Models\OmsConfig;
use App\Helpers\KiteConnectCls;
use App\Models\OrderBook;
use App\Models\AngelApiInstrument;

class OmsConfigCron{
    
    public function __construct()
    {
        set_time_limit(0);
    }

    public function calculateTickSize($price,$tickSize){
        // echo $price.'--'.$tickSize;die;
        // $mul = $tickSize/100;
        // if($mul > 0){
        //     return ceil($price / $mul) * $mul; 
        // }
        // return $price;  
        $roundedPrice = round($price / $tickSize) * $tickSize;
        return $roundedPrice; 
    }

    public function getCeLimitPrice($high,$low,$per,$type,$closePrice,$tickSize){
        // echo $high.'--'.$low.'--'.$per.'--'.$closePrice;die;
        $diff = ($high - $low) * ($per/100);
        if($type=="BUY"){
            $price = $closePrice - $diff;
        }else{
            $price = $closePrice + $diff;
        }
        $finalPrice = round($price,2);
        return $this->calculateTickSize($finalPrice,$tickSize);
    }

    public function getPeLimitPrice($high,$low,$per,$type,$closePrice,$tickSize){
        $diff = ($high - $low) * ($per/100);
        if($type=="BUY"){
            $price = $closePrice - $diff;
        }else{
            $price = $closePrice + $diff;
        }
        $finalPrice = round($price,2);
        return $this->calculateTickSize($finalPrice,$tickSize);
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
            // $pythonScript = '/home/forge/cityprofitedge.com/public/kite_login/app.py -u '.$broker->account_user_name;
            // $command = 'python3 ' . $pythonScript; 
            // exec($command, $output, $exitCode);
            // $tokenArr =  explode("=",implode("\n", $output));
            // $token =  $tokenArr[1];
            // return rand(1,9999999);
            $token = $broker->request_token;
            $kite = $kiteObj->generateSessionManual($token);
            return $kite;
        });
        try{
            if(is_string($kite)){
                \Cache::forget('KITE_AUTH_'.$broker->account_user_name);
                return 0;
            }
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
            $bookOBj->price = $apiData['price'];
            $bookOBj->quantity = $lastD[0]->quantity;
            $bookOBj->status_message = $lastD[0]->status_message;
            $bookOBj->order_datetime = $lastD[0]->order_timestamp->format('Y-m-d H:i:s');
            $bookOBj->user_id = $broker->user_id;
            $bookOBj->save();
            return 1;
        }catch(\Exception $e){
            echo $e->getMessage();
            \Cache::forget('KITE_AUTH_'.$broker->account_user_name);
        }
    }

    public function getZerodhaSymLotSize($symbol){
        $lotSizeDat = ZerodhaInstrument::select('lot_size','tick_size')->where('trading_symbol',$symbol)->first();
        if($lotSizeDat){
            return [
                'lot_size'=>$lotSizeDat->lot_size,
                'tick_size'=>$lotSizeDat->tick_size
            ];
        }
        return [
            'lot_size'=>null,
            'tick_size'=>null
        ];
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
            $strategyArr = array_slice($data['Strategy_name'],-1);
            // $highCEArr = array_reverse(array_slice($data['high_CE'],-1));
            // $lowCEArr = array_reverse(array_slice($data['low_CE'],-1));
            // $highPEArr = array_reverse(array_slice($data['high_PE'],-1));
            // $lowPEArr = array_reverse(array_slice($data['low_PE'],-1));
            $highCEArr = array_reverse($data['high_CE']);
            $lowCEArr = array_reverse($data['low_CE']);
            $highPEArr = array_reverse($data['high_PE']);
            $lowPEArr = array_reverse($data['low_PE']);

            $CEALL  = array_reverse($data['CE']);
            $PEALL  = array_reverse($data['PE']);


            $buyActionArr = array_slice($data['BUY_Action'],-1);
            $sellActionArr = array_slice($data['SELL_Action'],-1);
            $closeCEArr = array_reverse($data['close_CE']);
            $closePEArr = array_reverse($data['close_PE']);
            foreach($strategyArr as $key=>$v){
                // if(strtolower($v)==strtolower($omsData->strategy_name)){
                if((strtolower($buyActionArr[$key])==strtolower($omsData->strategy_name)) || (strtolower($sellActionArr[$key])==strtolower($omsData->strategy_name))){
                    // $high = $highCEArr[$key];
                    // $low = $lowCEArr[$key];
                    // $closePrice = $closeCEArr[$key];

                    $high = 0;
                    $low = 0;
                    $closePrice = 0;


                    $fData["tradingsymbol"] = $omsData->ce_symbol_name;

                    if(!is_null($omsData->ce_symbol_name)){
                        $kky = 0;
                        foreach($CEALL as $kc=>$cvl){
                            if($omsData->ce_symbol_name==$cvl){
                                $kky = $kc;
                            }
                        }
                        $high = $highCEArr[$kky];
                        $low = $lowCEArr[$kky];
                        $closePrice = $closeCEArr[$kky];

                    }


                    $lotSizeArr = $this->getZerodhaSymLotSize($omsData->ce_symbol_name);
                    $lotSize = $lotSizeArr['lot_size'];
                    $tickSize = $lotSizeArr['tick_size'];
                    $updateDb = 1;
                    if(!is_null($omsData->ce_pyramid_1)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getCeLimitPrice($high,$low,38.20,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                       
                       $fData['quantity'] = $omsData->ce_pyramid_1;
                       $updateDb = $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->ce_pyramid_2)){
                        //50%
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getCeLimitPrice($high,$low,50,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        $fData['quantity'] = $omsData->ce_pyramid_2;
                        $updateDb = $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->ce_pyramid_3)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getCeLimitPrice($high,$low,61.80,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        $fData['quantity'] = $omsData->ce_pyramid_3;
                        $updateDb = $this->postPlaceOrder($omsData->broker,$fData);
                    }

                    //
                    $fData["tradingsymbol"] = $omsData->pe_symbol_name;
                    
                    $lotSizeArr = $this->getZerodhaSymLotSize($omsData->pe_symbol_name);
                    $lotSize = $lotSizeArr['lot_size'];
                    $tickSize = $lotSizeArr['tick_size'];


                    $high = 0;
                    $low = 0;
                    $closePrice = 0;

                    if(!is_null($omsData->pe_symbol_name)){
                        $kky = 0;
                        foreach($PEALL as $kc=>$cvl){
                            if($omsData->pe_symbol_name==$cvl){
                                $kky = $kc;
                            }
                        }
                        $high = $highPEArr[$kky];
                        $low = $lowPEArr[$kky];
                        $closePrice = $closePEArr[$kky];
                    }

                    if(!is_null($omsData->pe_pyramid_1)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getPeLimitPrice($high,$low,38.20,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        $fData['quantity'] = $omsData->pe_pyramid_1;
                        $updateDb = $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->pe_pyramid_2)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getPeLimitPrice($high,$low,50,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        $fData['quantity'] = $omsData->pe_pyramid_2;
                        $updateDb = $this->postPlaceOrder($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->pe_pyramid_3)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getPeLimitPrice($high,$low,61.80,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        $fData['quantity'] = $omsData->pe_pyramid_3;
                        $updateDb = $this->postPlaceOrder($omsData->broker,$fData);
                    }

                    if($updateDb==1){
                        OmsConfig::where("id",$omsData->id)->update([
                            'is_api_pushed'=>1
                        ]);
                        $breakForeach = 1;
                        break;
                    }
                    
                }
            }
        }
        OmsConfig::where("id",$omsData->id)->update([
            'cron_run_at'=>date("Y-m-d H:i:s",strtotime($omsData->cron_run_at.'+ '.$omsData->pyramid_freq.'minutes'))
        ]);
    }

    //angel api start

    public function postPlaceOrderAngel(object $broker,array $apiData){
        // dd($apiData);
        $params = [
            'accountUserName'=>$broker->account_user_name,
            'apiKey'=>$broker->api_key,
            'pin'=>$broker->security_pin,
            'totp_secret'=>$broker->totp,
        ];
        
        $angelTokenArrObj = new AngelConnectCls($params);
        $angelTokenArr = $angelTokenArrObj->generate_access_token();


        if(is_null($angelTokenArr)){
            \Cache::forget('ANGEL_API_TOKEN_'.$broker->account_user_name);
        }else{
            $tokenA = $angelTokenArr['token'];
            $clientLocalIp = $angelTokenArr['clientLocalIp'];
            $clientPublicIp = $angelTokenArr['clientPublicIp'];
            $macAddress = $angelTokenArr['macAddress'];
            $httpHeaders = array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$broker->api_key,
                'X-ClientLocalIP: '.$clientLocalIp,
                'X-ClientPublicIP: '.$clientPublicIp,
                'X-MACAddress: '.$macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$tokenA
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/order/v1/placeOrder',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($apiData),
                CURLOPT_HTTPHEADER => $httpHeaders,
            ));    
            $response = curl_exec($curl);
            // echo $response;die;
            $err = curl_error($curl);
            curl_close($curl);
            if ($err || $response=="") {
                \Cache::forget('ANGEL_API_TOKEN_'.$broker->account_user_name);
            }else{
                $response = json_decode($response,true);
                
                if($response['status']==true){
                    $orderId = $response['data']['uniqueorderid'];
                    sleep(3);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/order/v1/details/'.$orderId,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => $httpHeaders,
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    
                    $response = json_decode($response,true);
                    if($response['status']==true){
                        $lastD = $response['data'];

                        $bookOBj = new OrderBook();
                        $bookOBj->broker_username = $broker->account_user_name;
                        $bookOBj->order_id = $orderId;
                        $bookOBj->status = $lastD['status'];
                        $bookOBj->trading_symbol = $lastD['tradingsymbol'];
                        $bookOBj->order_type =  $lastD['ordertype'];
                        $bookOBj->transaction_type = $lastD['transactiontype'];
                        $bookOBj->product = $lastD['producttype'];
                        $bookOBj->price = $apiData['price'];
                        $bookOBj->quantity = $lastD['quantity'];
                        $bookOBj->status_message = $lastD['text'];
                        $bookOBj->order_datetime = date("Y-m-d H:i:s",strtotime($lastD['updatetime']));
                        $bookOBj->user_id = $broker->user_id;
                        $bookOBj->save();
                    }

                }else{
                    \Cache::forget('ANGEL_API_TOKEN_'.$broker->account_user_name);
                }
            }
            
        }

    }

    public function getTokenBySymbolName($symbName){
       $data =  AngelApiInstrument::select('trading_symbol','zi.exchange_token','lotsize','symbol_name','angel_api_instruments.tick_size')->join('zerodha_instruments as zi','zi.exchange_token','token')->where('trading_symbol',$symbName)->first();
       if($data){
            return [
                'symbol'=> $data->symbol_name,
                'token'=> $data->exchange_token,
                'lot_size'=>$data->lotsize,
                'tick_size'=>$data->tick_size
            ];
           
       }
       return [
        'symbol'=>null,
        'token'=>null,
        'lot_size'=>null,
        'tick_size'=>null,
       ];
    }

    public function callAngelApi($signalData,object $omsData){
        $mcxSymArr = ['CRUDEOIL','NATURALGAS','GOLD','SILVER'];
        $txnType = $omsData->txn_type;
        $extType = in_array($omsData->symbol_name,$mcxSymArr) ? "MCX" : "NFO";
        $fData = [
            'variety'=>'NORMAL',
            "exchange" => $extType,//crude oil,naturalgas,gold,silver--mcx .. remaining --NFO
            "transactiontype" => $txnType,
            "ordertype" => $omsData->order_type,
            // "producttype" => $omsData->product,
            "producttype" => 'INTRADAY',
            'duration'=>'DAY',
            'squareoff'=>0,
            'stoploss'=>0
        ];

        $breakForeach = 0;
        foreach($signalData as $vvl){
            if($breakForeach == 1){
                break;
            }
            $data = json_decode($vvl->data,true);
            $strategyArr = array_slice($data['Strategy_name'],-1);
            // $highCEArr = array_slice($data['high_CE'],-1);
            // $lowCEArr = array_slice($data['low_CE'],-1);
            // $highPEArr = array_slice($data['high_PE'],-1);
            // $lowPEArr = array_slice($data['low_PE'],-1);

            $highCEArr = array_reverse($data['high_CE']);
            $lowCEArr = array_reverse($data['low_CE']);
            $highPEArr = array_reverse($data['high_PE']);
            $lowPEArr = array_reverse($data['low_PE']);

            $CEALL  = array_reverse($data['CE']);
            $PEALL  = array_reverse($data['PE']);

            $buyActionArr = array_slice($data['BUY_Action'],-1);
            $sellActionArr = array_slice($data['SELL_Action'],-1);
            $sellActionArr = array_slice($data['SELL_Action'],-1);
            $sellActionArr = array_slice($data['SELL_Action'],-1);
            $closeCEArr = array_reverse($data['close_CE']);
            $closePEArr = array_reverse($data['close_PE']);

            foreach($strategyArr as $key=>$v){
                // if(strtolower($v)==strtolower($omsData->strategy_name)){
                if((strtolower($buyActionArr[$key])==strtolower($omsData->strategy_name)) || (strtolower($sellActionArr[$key])==strtolower($omsData->strategy_name))){
                    // $high = $highCEArr[$key];
                    // $low = $lowCEArr[$key];
                    // $closePrice = $closeCEArr[$key];

                    $high = 0;
                    $low = 0;
                    $closePrice = 0;


                    $fData["tradingsymbol"] = $omsData->ce_symbol_name;

                    if(!is_null($omsData->ce_symbol_name)){
                        $kky = 0;
                        foreach($CEALL as $kc=>$cvl){
                            if($omsData->ce_symbol_name==$cvl){
                                $kky = $kc;
                            }
                        }
                        $high = $highCEArr[$kky];
                        $low = $lowCEArr[$kky];
                        $closePrice = $closeCEArr[$kky];

                    }

                    $symArr =  $this->getTokenBySymbolName($omsData->ce_symbol_name);
                    
                    $fData["tradingsymbol"] = $symArr['symbol'];
                    $fData['symboltoken'] = $symArr['token'];
                    $tickSize = $symArr['tick_size'];
                    
                    // $lotSize = $extType=='MCX' ? ($symArr['lot_size']/100) : $symArr['lot_size'];
                    $lotSize = $symArr['lot_size'];

                    if(!is_null($omsData->ce_pyramid_1)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getCeLimitPrice($high,$low,38.20,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                       
                    //     $fData['quantity'] = $omsData->ce_pyramid_1;

                           $fData['quantity'] = $lotSize * $omsData->ce_pyramid_1;
                       
                       $this->postPlaceOrderAngel($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->ce_pyramid_2)){
                        //50%
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getCeLimitPrice($high,$low,50,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        // $fData['quantity'] = $omsData->ce_pyramid_2;
                        $fData['quantity'] = $lotSize * $omsData->ce_pyramid_2;
                        $this->postPlaceOrderAngel($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->ce_pyramid_3)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getCeLimitPrice($high,$low,61.80,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        // $fData['quantity'] = $omsData->ce_pyramid_3;
                        $fData['quantity'] = $lotSize * $omsData->ce_pyramid_3;
                        $this->postPlaceOrderAngel($omsData->broker,$fData);
                    }

                    //

                    $symArr =  $this->getTokenBySymbolName($omsData->pe_symbol_name);
                    // dd($symArr);
                    $fData["tradingsymbol"] = $symArr['symbol'];
                    $fData['symboltoken'] = $symArr['token'];
                    $tickSize = $symArr['tick_size'];
                    // $high = $highPEArr[$key];
                    // $low = $lowPEArr[$key];
                    // $closePrice = $closePEArr[$key];

                    $high = 0;
                    $low = 0;
                    $closePrice = 0;

                    if(!is_null($omsData->pe_symbol_name)){
                        $kky = 0;
                        foreach($PEALL as $kc=>$cvl){
                            if($omsData->pe_symbol_name==$cvl){
                                $kky = $kc;
                            }
                        }
                        $high = $highPEArr[$kky];
                        $low = $lowPEArr[$kky];
                        $closePrice = $closePEArr[$kky];
                    }

                    // $lotSize = $extType=='MCX' ? ($symArr['lot_size']/100) : $symArr['lot_size'];
                    $lotSize = $symArr['lot_size'];

                    if(!is_null($omsData->pe_pyramid_1)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getPeLimitPrice($high,$low,38.20,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        // $fData['quantity'] = $omsData->pe_pyramid_1;
                        $fData['quantity'] = $lotSize *  $omsData->pe_pyramid_1;
                        $this->postPlaceOrderAngel($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->pe_pyramid_2)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getPeLimitPrice($high,$low,50,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        // $fData['quantity'] = $omsData->pe_pyramid_2;
                        $fData['quantity'] = $lotSize * $omsData->pe_pyramid_2;
                        $this->postPlaceOrderAngel($omsData->broker,$fData);
                    }
                    if(!is_null($omsData->pe_pyramid_3)){
                        if($omsData->order_type=="LIMIT"){ 
                            $price =  $this->getPeLimitPrice($high,$low,61.80,$txnType,$closePrice,$tickSize);
                            $fData['price'] = $price;
                        }
                        // $fData['quantity'] = $omsData->pe_pyramid_3;
                        $fData['quantity'] = $lotSize * $omsData->pe_pyramid_3;
                        $this->postPlaceOrderAngel($omsData->broker,$fData);
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

    // angel api ends

    public function placeOrder(){
        $todayDate=date("Y-m-d");
        $startDateTime = strtotime(date("Y-m-d 15:30:00"));
        $endDateTime = strtotime(date("Y-m-d 23:30:00"));
        $currentDateTime = strtotime(date("Y-m-d H:i:s"));
        // $todayDate="2024-02-02";
        $omsDt = OmsConfig::select('*')->with('broker')
        ->where('is_api_pushed',0);
        if($currentDateTime > $startDateTime && $currentDateTime < $endDateTime){
            $omsDt->where('symbol_name','CRUDEOIL');
        }
        $omsDt->chunk(100, function($omgData) use($todayDate){
            foreach ($omgData as $val) {
                $signalData = \DB::connection('mysql_rm')->table($val->symbol_name)->select('*')->where(['date'=>$todayDate,'timeframe'=>$val->signal_tf])->get();
               
                $pFreq = "-".$val->pyramid_freq." minutes";
                $nextRun = strtotime(date("Y-m-d H:i:s",strtotime($pFreq)));
                $lstRun = strtotime($val->cron_run_at);
                if($nextRun > $lstRun){
                    if(count($signalData)){
                        $fffData = [];
                       
                        foreach($signalData as $vvvl){
                            if(isset($vvvl->atm) && $vvvl->atm=="ATM"){
                                $omsData = $val;
                                $fffData[] = $vvvl;
                                if($omsData->broker->client_type=="Zerodha"){
                                    $this->callKiteApi($fffData,$omsData);
                                }     
                                elseif($omsData->broker->client_type=="Angel"){
                                    $this->callAngelApi($fffData,$omsData);
                                }  
                            }
                            
                        }       
                        
                    }
                }
            }
        });
    }
}