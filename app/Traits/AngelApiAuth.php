<?php
namespace App\Traits;

use App\Models\AngelApiInstrument;
use App\Models\AngleHistoricalApi;
use App\Models\LTP_ROUNDOFF;
use App\Models\TradeDeskSignal;

use Illuminate\Support\LazyCollection;

require app_path('Libraries/vendor/autoload.php');
use OTPHP\TOTP;
trait AngelApiAuth
{
    // private $accountUserName = 'R834343';
    // private $accountPassword = 'city@123';
    // private $totp_secret = 'M46VHZKUIVRYBWO3CBF4B4BGLM';
    // private $apiKey = 'fAIucOM2';
    // private $pin = '1234';
    // private $apiSecret = '1abcbf62-552f-4022-b073-d76bad9a06a5';
    // private $clientLocalIp = '192.168.1.31';
    // private $clientPublicIp = '122.161.67.85';
    // private $macAddress = '14-85-7F-92-D0-B0';

    private $accountUserName =  "P713842";
    private $accountPassword = "city@123";
    private $apiSecret = "1a8f300a-8d49-4581-9b1e-4ac78f6c997a";
    private $apiKey = "WGgAXzmi";
    private $pin = "1234";
    private $totp_secret = "JINWAFYURA5IDPO6X5D2UWUWXE";
    private $clientLocalIp = '192.168.1.31';
    private $clientPublicIp = '122.161.67.85';
    private $macAddress = '14-85-7F-92-D0-B0';

    public function get_totp_token()
    {
        $totp = TOTP::create($this->totp_secret);
        return $totp->now();
    }

    public function generate_access_token()
    {
        try {
            $data = \Cache::remember('ANGEL_API_TOKEN', 72000, function () {
                $postFields = [
                    "clientcode"=>$this->accountUserName,
                    "password"=>$this->pin,
                    "totp"=>$this->get_totp_token(),
                ];
    
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/auth/angelbroking/user/v1/loginByPassword',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>json_encode($postFields),
                CURLOPT_HTTPHEADER => array(
                    'X-UserType: USER',
                    'X-SourceID: WEB',
                    'X-PrivateKey: '.$this->apiKey,
                    'X-ClientLocalIP: '.$this->clientLocalIp,
                    'X-ClientPublicIP: '.$this->clientPublicIp,
                    'X-MACAddress: '.$this->macAddress,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ),
                ));
    
                $response = curl_exec($curl);
                $err = curl_error($curl);
                // echo $response;die;
                curl_close($curl);
                if ($err) {
                    return null;
                }
                $dataArr = json_decode($response);
                return $dataArr->data->jwtToken;
            });
            return $data;
           
        } catch (Exception $ex) {
           return null;
        }
    }

    public function getMarketDataResp(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "mode": "FULL",
            "exchangeTokens": {
            "NSE": ["99926000","99926009","99926037"]
            }
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $dataArr = json_decode($response,true);
            if($dataArr['status']===true){
                $dtt = $dataArr['data'];
                if(isset($dtt['fetched'])){
                    $fData = $dtt['fetched'];
                    $errData = $fData;
                }
            }
            return $errData;
        }
        return $errData;
    }

    public function getTopLoserAngleApiData(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/gainersLosers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "datatype": "PercPriceLosers",
                "expirytype": "NEAR"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }   
    
    public function getTopGainerAngleApiData(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/gainersLosers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "datatype": "PercPriceGainers",
                "expirytype": "NEAR"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }   


    public function getPCRApiDatas(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/putCallRatio',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }

    public function getLongBuildData(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/OIBuildup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "datatype": "Long Built Up",
                "expirytype": "NEAR"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }

    public function getShortBuildData(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/OIBuildup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "datatype": "Short Built Up",
                "expirytype": "NEAR"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }

    public function getShortCoveringData(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/OIBuildup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "datatype": "Short Covering",
                "expirytype": "NEAR"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }

    public function getLongUnwillingData(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/OIBuildup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "datatype": "Long Unwinding",
                "expirytype": "NEAR"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }
    
    function allTradeSymbols(){
        $data = \DB::connection('mysql_rm')->select('SHOW TABLES');
        $arr = [];
        foreach($data as $vl){
            $arr[] = $vl->Tables_in_PMS_Datastore;
        }
        return $arr;
    }

    // Store Historical Data
    public function storeApiFetch(){
        set_time_limit(0);
        // AngleHistoricalApi::truncate();
        $tables = $this->allTradeSymbols();
        $frame = [1,2,5];
        $todayDate = date("Y-m-d");
        $currentDate = date("Y-m-d H:s");
        $previousDate =  date('Y-m-d H:s', strtotime($currentDate. ' - 30 days'));
        
        foreach ($tables as $v) {
          
            foreach ($frame as $tf) {
                $data = \DB::connection('mysql_rm')->table($v)->select('*')->where(['date'=>$todayDate,'timeframe'=>$tf])->get(); 
          
                $atmData = [];
                foreach($data as $vvl){
                    if(isset($vvl->atm) && $vvl->atm == "ATM"){
                        $atmData[] = $vvl;
                    }
                }

                foreach($atmData as $val){
                    
                    $arrData = json_decode($val->data,true);    
                    $CE = array_unique($arrData['CE']);
                    
                    foreach ($CE as $k=>$sym){
                        $getDetails = AngelApiInstrument::Where('symbol_name',$sym)->first();
                        if($getDetails != NULL){
                            $timeFrame = ['ONE_MINUTE','THREE_MINUTE','FIVE_MINUTE'];
    
                            foreach ($timeFrame as $interval) {
                                $exhange = $getDetails['exch_seg'];
                                $token = $getDetails['token'];
                                $jwtToken =  $this->generate_access_token();
                                $errData = [];
                                
                                if($jwtToken!=null){
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/historical/v1/getCandleData',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => '{
                                            "exchange": "'.$exhange.'",
                                            "symboltoken": "'.$token.'",
                                            "interval": "'.$interval.'",
                                            "fromdate": "'.$previousDate.'",
                                            "todate": "'.$currentDate.'"
                                        }',
                                        CURLOPT_HTTPHEADER => array(
                                            'X-UserType: USER',
                                            'X-SourceID: WEB',
                                            'X-PrivateKey: '.$this->apiKey,
                                            'X-ClientLocalIP: '.$this->clientLocalIp,
                                            'X-ClientPublicIP: '.$this->clientPublicIp,
                                            'X-MACAddress: '.$this->macAddress,
                                            'Content-Type: application/json',
                                            'Authorization: Bearer '.$jwtToken
                                        ),
                                    ));
                        
                                    $response = curl_exec($curl);
                                    // dd($response);
                                    $err = curl_error($curl);
                                    curl_close($curl);
    
                                    if ($err) {
                                       return $errData;
                                    }
    
                                    $response = json_decode($response,true);
                                    $data = $response['data'];
                                    
                                    foreach($data as $key => $item){
                                        if($interval == 'ONE_MINUTE'){
                                            $in = 1;
                                        }else if($interval == 'THREE_MINUTE'){
                                            $in = 3;
                                        }else{
                                            $in = 5;
                                        }
                                        $apiData = new AngleHistoricalApi;
                                        $apiData->token = $token;
                                        $apiData->symbol = $sym;
                                        $apiData->time_interval = $in;
                                        $apiData->exchange = $exhange;
                                        $apiData->fromdate = $previousDate;
                                        $apiData->todate = $currentDate;
                                        $apiData->timestamp = $data[$key][0];
                                        $apiData->open = $data[$key][1];
                                        $apiData->high = $data[$key][2];
                                        $apiData->low = $data[$key][3];
                                        $apiData->close = $data[$key][4];
                                        $apiData->volume = $data[$key][5];
                                        $apiData->save();
                                    }
                                }
                            }
                        }
                    }
                    sleep(4);
                }
            }
            
        }  

        return "Data Inserted Successfully";
    }

    // public function fetchGreeksApi(){
    //     $jwtToken =  $this->generate_access_token();
    //     $errData = [];
    //     if($jwtToken!=null){
    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/optionGreek',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => '{
    //             "name": "CRUDEOIL",
    //             "expirydate": "14FEB2024"
    //         }',
    //         CURLOPT_HTTPHEADER => array(
    //             'X-UserType: USER',
    //             'X-SourceID: WEB',
    //             'X-PrivateKey: '.$this->apiKey,
    //             'X-ClientLocalIP: '.$this->clientLocalIp,
    //             'X-ClientPublicIP: '.$this->clientPublicIp,
    //             'X-MACAddress: '.$this->macAddress,
    //             'Content-Type: application/json',
    //             'Authorization: Bearer '.$jwtToken
    //         ),
    //         ));

    //         $response = curl_exec($curl);
    //         // dd($response);
    //         $err = curl_error($curl);
    //         curl_close($curl);
    //         if ($err) {
    //             return $errData;
    //         }
    //         $errData = json_decode($response,true);
    //         // dd($errData);
    //         return $errData;
    //     }
    //     return $errData;
    // }

    // Check Current Time is between given Time Range
    function isBetween915AMto1130PM() {
        date_default_timezone_set("Asia/Calcutta");
        $currentTime = time();
        $startTime = strtotime('9:15 AM');
        $endTime = strtotime('11:30 PM');
        
        if ($currentTime >= $startTime && $currentTime <= $endTime) {
            return true; 
        } else {
            return false; 
        }
    }

    // Get Ltp
    function getLTP($exhange , $symbol , $token){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/order-service/rest/secure/angelbroking/order/v1/getLtpData',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "exchange": "'.$exhange.'",
                "tradingsymbol": "'.$symbol.'",
                "symboltoken": "'.$token.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }

    // Function to get unique strike values
    function getUniqueStrikes($data) {
        $uniqueStrikes = array();
        $newArray = array();
        foreach ($data as $item) {
            if (!in_array($item['strike'], $uniqueStrikes, true)) {
                $uniqueStrikes[] = $item['strike'];
                $newArray[] = $item;

            }
        }
        return $newArray;
    }

    function getStrickData($name , $exhange , $givenLtp , $ce_adjustment, $pe_adjustment){
        $angleData = AngelApiInstrument::Where('name',$name)->where('exch_seg',$exhange)->orderBy('expiry','ASC')->get()->toArray();
        // dd($angleData);

        $angleData = array_map(function ($y) {
            $y['expiry'] = strtotime($y['expiry']);
            return $y;
        }, $angleData);

        array_multisort(array_column($angleData ,'expiry'),SORT_ASC ,$angleData);
        
        // dd(array_unique($angleData));

        $angleData = array_map(function ($y) {
            $y['strike'] = ($y['strike'] / 100);
            return $y;
        }, $angleData);

        // dd($angleData);
        if($name == "NATURALGAS"){
            $angleData = array_values(array_filter($angleData, function($x) {
                return ($x['strike'] % 60 == 0 || $x['strike'] % 70 == 0);
            }));    
        }else{
            $angleData = array_values(array_filter($angleData, function($x) {
                return $x['strike']%100==0;
            }));
        }

        dd($angleData);

        // New Change
        $futComData  = array_filter($angleData,function($x){
            return ($x['instrumenttype'] == 'FUTCOM' && $x['expiry'] >= strtotime(date()));
        });     

        $futComData = array_map(function ($y) {
            $y['ts_len'] = strlen($y['symbol_name']);
            return $y;
        }, $angleData);

        $expiry = [];
        $ts_len = [];

        array_multisort(array_column($futComData ,'expiry'),SORT_ASC,array_column($futComData ,'ts_len'),SORT_ASC,$futComData );
       
        $latest_expriyDate = $futComData[0]['expiry'];
        // dd($latest_expriyDate);
        // dd($futComData[0]);
        $token = $futComData[0]['token'];
        $tradingsymbol = $futComData[0]['symbol_name'];

        if(!$givenLtp){
            $ltpByApi = $this->getLTP($exchangeVal,$nameVal,$tokenVal);
            if($ltpByApi['status'] == true){
                $ltp = $ltpByApi['data']['ltp'];
            }
        }else{
            $ltp = $givenLtp;
        }
        // dd($angleData);

        $strikes = $this->getUniqueStrikes($angleData);
        $strikes = array_values(array_filter($strikes, function($value) {
            return $value !== null;
        }));  


        array_multisort(array_column($strikes, 'strike'),SORT_ASC,$strikes);
        
        $absprc = array_map(function ($y) use($ltp) {
            return abs($y['strike'] - $ltp);
        }, $strikes);

       
        // dd(array_column($strikes, 'strike'));

        $min_index = array_search(min($absprc), $absprc);
        // dd($min_index);

        if($min_index + $ce_adjustment < count($strikes) && $min_index + $pe_adjustment < count($strikes)){
            $closest_strike_ce = $strikes[$min_index + ($ce_adjustment)]['strike'];
            $closest_strike_pe = $strikes[$min_index + ($pe_adjustment)]['strike'];

            // dd($closest_strike_pe);
            if ($closest_strike_ce != 0.0 && $closest_strike_pe != 0.0) {

                $strike_filter_ce  = array_filter($angleData,function($x) use($closest_strike_ce){
                    return $x['strike'] == $closest_strike_ce;
                });  
                
                $strike_filter_pe  = array_filter($angleData,function($x) use($closest_strike_pe){
                    return $x['strike'] == $closest_strike_pe;
                });  

                $ce_instrument = array_values(array_filter($strike_filter_ce, function($x) {
                    return substr($x['symbol_name'],-2) == "CE";
                }));

                $pe_instrument = array_values(array_filter($strike_filter_pe, function($x) {
                    return substr($x['symbol_name'],-2) == "PE";
                }));

                $ce_instrument = $ce_instrument[0];
                $pe_instrument = $pe_instrument[0];
                // dd($ce_instrument);

                $ce_token = $ce_instrument["token"];
                $ce_symbol = $ce_instrument["symbol_name"];
                // dd($ce_token);
    
                $pe_token = $pe_instrument["token"];
                $pe_symbol = $pe_instrument["symbol_name"];

                $instrumenttype = ($exhange == 'MCX') ? 'COMDTY' : 'AMXIDX';
                $index = AngelApiInstrument::Where('name',$name)->where('exch_seg',$exhange)->where('instrumenttype',$instrumenttype)->first()->toArray();

                $index_token = ($index != NULL) ? $index['token'] : null;
                // dd($index_token);

                return  array(array($ce_symbol, $ce_token, $ce_adjustment), array($pe_symbol, $pe_token, $pe_adjustment), $index_token);
            }else{
                return array(array(null, null), array(null, null));
            }
        }else{
            return array(array(null, null), array(null, null));
        }


    }

    public function get_upcoming_expiry($name, $exchange){
        if ($exchange == 'NSE') {
            $exchange = 'NFO';
        }

        $angleData = AngelApiInstrument::Where('name',$name)->where('exch_seg',$exchange)->get()->toArray();
        // dd($angleData);
        $angleData = array_map(function ($y) {
            $y['expiry'] = date("d-m-Y", strtotime($y['expiry']));  
            return $y;
        }, $angleData);

        $expiry_list = array_map(function ($y) {
            return $y['expiry']; 
        }, $angleData);

        $final_expiry = array_unique($expiry_list);
        usort($final_expiry, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        // dd($final_expiry);
        
        $current_date = date('d-m-Y');
        $current_day = date('d');
        $current_month = date('m');
        $current_year = date('Y');
        $upcoming_exp_date = "";
        foreach ($final_expiry as $expiry) {
            $datetime_object = date($expiry);
            if ($datetime_object > $current_date) {
                if ($current_year == date("Y", strtotime($datetime_object))) {
                    if ($current_month == date("m", strtotime($datetime_object))) {
                        $upcoming_exp_date = $datetime_object;
                        break;
                    } else {
                        $next_month = $current_month + 1;
                        if ($next_month == date("m", strtotime($datetime_object))) {
                            $upcoming_exp_date = $datetime_object;
                            break;
                        }
                    }
                }
            }
        }
        $upcoming_exp_date_str = date("d-m-Y", strtotime($upcoming_exp_date));
        $current_date_str = date("d-m-Y", strtotime($current_date));
        return array($current_date_str, $upcoming_exp_date_str);

    }

    public function get_rounded_price($price,$symbol_name, $adjustment){

        $ltp_roundoff = LTP_ROUNDOFF::WHERE('name',$symbol_name)->first();
        return round(($price / $ltp_roundoff->value) + (int)$adjustment) * $ltp_roundoff->value;
    }

    // public function live_market_api_data($exch , $tokens){
    //     $jwtToken =  $this->generate_access_token();
    //     $errData = [];
    //     if($jwtToken!=null){
    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote/',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => '{
    //             "mode": "FULL",
    //             "exchangeTokens": {
    //                 "'.$exch.'": '.$tokens.'
    //             }
    //         }',
    //         CURLOPT_HTTPHEADER => array(
    //             'X-UserType: USER',
    //             'X-SourceID: WEB',
    //             'X-PrivateKey: '.$this->apiKey,
    //             'X-ClientLocalIP: '.$this->clientLocalIp,
    //             'X-ClientPublicIP: '.$this->clientPublicIp,
    //             'X-MACAddress: '.$this->macAddress,
    //             'Content-Type: application/json',
    //             'Authorization: Bearer '.$jwtToken
    //         ),
    //         ));

    //         $response = curl_exec($curl);
    //         // dd($response);
    //         $err = curl_error($curl);
    //         curl_close($curl);
    //         if ($err) {
    //             return $errData;
    //         }
    //         $errData = json_decode($response,true);
    //         // dd($errData);
    //         return $errData;
    //     }
    //     return $errData;
    // }

  

    public function get_atm_strike_symbol_angel($spt_prc, $symbol_name, $nse_symbol, $exchange_name, $expiry_dates, $ce_adjustment, $pe_adjustment){

        $angleData = AngelApiInstrument::Where('name',$symbol_name)->get()->toArray();
       
        $rounded_price_ce = $this->get_rounded_price($spt_prc, $symbol_name, $ce_adjustment);
        $rounded_price_pe = $this->get_rounded_price($spt_prc, $symbol_name, $pe_adjustment);

        $filters = array_map(function ($y) {
            $y['expiry'] = strtotime($y['expiry']);
            return $y;
        }, $angleData);

        try {
            $index_row = AngelApiInstrument::Where('name',$symbol_name)->where('exch_seg','NSE')->get()->toArray();
            $index_token = $index_row[0]['token'];
            // dd($index_token);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        if ($exchange_name == 'NSE') {
            $exchange_name = 'NFO';
        }

        // dd($expiry_dates);

        $filters = array_values(array_filter($filters, function($x) use($symbol_name,$exchange_name,$expiry_dates) {
            if(($x['name'] == $symbol_name) && ($x["exch_seg"] == $exchange_name) && ($x['expiry'] <= strtotime($expiry_dates[1])) && ($x['expiry'] >= strtotime($expiry_dates[0]))){
                return $x;
            }
        }));

        // dd($filters);
       
        $filters = array_map(function ($y) {
            $y['strike'] = ($y['strike'] / 100);
            return $y;
        }, $filters);

        // dd($filters);
        try {
            $ce_filters = array_values(array_filter($filters, function($x) use($expiry_dates,$rounded_price_ce) {
                if(($x['expiry'] == strtotime($expiry_dates[1]))  && (substr($x['symbol_name'],-2) == "CE") && ($x["strike"] == $rounded_price_ce)){
                    return $x;
                }
            }));
         
            $ce_symbol = $ce_filters[0]["symbol_name"];
            $ce_instrument_token = $ce_filters[0]["token"];

            $pe_filters = array_values(array_filter($filters, function($x) use($expiry_dates,$rounded_price_pe) {
                if($x['expiry'] == strtotime($expiry_dates[1]) && (substr($x['symbol_name'],-2) == "PE") && ($x["strike"] == $rounded_price_pe)){
                    return $x;
                }
            }));

            $pe_symbol = $pe_filters[0]["symbol_name"];
            $pe_instrument_token = $pe_filters[0]["token"];

            // dd($pe_filters);

            return array(array($ce_symbol, $ce_instrument_token, $ce_adjustment), array($pe_symbol, $pe_instrument_token, $pe_adjustment), $index_token);

        } catch (IndexError $e) {
            return array(array(null, null), array(null, null));
        }
    }

    public function get_average_price($exchange , $token,$jwtToken){
        // $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "mode": "FULL",
                "exchangeTokens": {
                    "'.$exchange.'": ["'.$token.'"]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }

    public function get_historical_api_data($symbolDetails,$alltoken){
        set_time_limit(0);
        date_default_timezone_set("Asia/Calcutta");
        $jwtToken =  $this->generate_access_token();
        $todayDate = date("Y-m-d");
        $currentDate = date("Y-m-d H:i");
        $current_time = time();
        $past_30_min_time = strtotime('-1440 minutes', $current_time); // Subtract 24hr
        $previousDate =  date('Y-m-d H:i',  $past_30_min_time);
                    
        // dd($alltoken);
        foreach ($symbolDetails as $k => $sym){
            $getDetails = AngelApiInstrument::Where('token',$alltoken[$k])->first();
            $timeFrame = ['ONE_MINUTE','THREE_MINUTE','FIVE_MINUTE'];

            if($getDetails != NULL){
                foreach ($timeFrame as $interval) {
                    $currentSymbol = $sym;
                    $token = $alltoken[$k];
                    $currentExchange =  $getDetails->exch_seg;
                    $errData = [];   
                    if($jwtToken!=null){
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/historical/v1/getCandleData',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                                "exchange": "'.$currentExchange.'",
                                "symboltoken": "'.$token.'",
                                "interval": "'.$interval.'",
                                "fromdate": "'.$previousDate.'",
                                "todate": "'.$currentDate.'"
                            }',
                            CURLOPT_HTTPHEADER => array(
                                'X-UserType: USER',
                                'X-SourceID: WEB',
                                'X-PrivateKey: '.$this->apiKey,
                                'X-ClientLocalIP: '.$this->clientLocalIp,
                                'X-ClientPublicIP: '.$this->clientPublicIp,
                                'X-MACAddress: '.$this->macAddress,
                                'Content-Type: application/json',
                                'Authorization: Bearer '.$jwtToken
                            ),
                        ));
            
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        curl_close($curl);
    
                        if ($err) {
                            return $errData;
                        }

                        $response = json_decode($response,true);
                        
                        try {
                            if($response['data'] != NULL && isset($response['data'])){
                                $data = $response['data'];
                                $res = $this->get_average_price($currentExchange,$token,$jwtToken);
                                if($res['data'] != NULL && isset($res['data'])){
                                    $marketData = $res['data']['fetched'];
                                    $avgprice = $marketData[0]['avgPrice'];
                                    $opnInterest = $marketData[0]['opnInterest'];
                                }else{
                                    $avgprice = NULL;
                                    $opnInterest = NULL;
                                }
                               
                                foreach($data as $key => $item){
                                    if($interval == 'ONE_MINUTE'){
                                        $in = 1;
                                    }else if($interval == 'THREE_MINUTE'){
                                        $in = 3;
                                    }else{
                                        $in = 5;
                                    }
                                    $apiData = new AngleHistoricalApi;
                                    $apiData->token = $token;
                                    $apiData->symbol = $currentSymbol;
                                    $apiData->time_interval = $in;
                                    $apiData->exchange = $currentExchange;
                                    $apiData->fromdate = $previousDate;
                                    $apiData->todate = $currentDate;
                                    $apiData->timestamp = $data[$key][0];
                                    $apiData->open = $data[$key][1];
                                    $apiData->high = $data[$key][2];
                                    $apiData->low = $data[$key][3];
                                    $apiData->close = $data[$key][4];
                                    $apiData->volume = $data[$key][5];
                                    $apiData->avgPrice = $avgprice != NULL ? $avgprice : NULL;
                                    $apiData->opnInterest = $opnInterest != NULL ? $opnInterest : NULL;
                                    // dd($apiData);
                                    $apiData->save();
                                }
                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                sleep(4);
                }
            }
        }
        return $errData;
    }

    public function getTradeDeskData(){
        set_time_limit(0);
        // $timeFrameInterval = 1;
        $symbol_range = 2;
        // $year = 5;
        $acceptedSymbols = ['CRUDEOIL','NIFTY','BANKNIFTY','GOLD','SILVER',];
        $marketHolidays = ["2024-01-22", "2024-01-26", "2024-03-08", "2024-03-25", "2024-03-29", "2024-04-11",
        "2024-04-17", "2024-05-01", "2024-06-17", "2024-07-17", "2024-08-15", "2024-10-02", "2024-11-01", "2024-11-15", "2024-12-25"];

        $currentDate = date('Y-m-d');
        $currentTime = "";

        // Check Today Is Holiday Or Not
        if(!in_array($currentDate,$marketHolidays)){
            // For Current Time is B\w 9:15Am to 11:30pm
            if($this->isBetween915AMto1130PM()){
                // Loop For Symbols List
                foreach ($acceptedSymbols as $key => $symbolName) {
                    $angleApiInstuments = AngelApiInstrument::Where('name',$symbolName)->where(function ($query) {
                        $query->where('instrumenttype', '=', 'AMXIDX')->orWhere('instrumenttype', '=', 'COMDTY');
                    })->first();

                    // dd($angleApiInstuments);

                    // For MCX Exch Records
                    if($angleApiInstuments->exch_seg == "MCX"){
                        // For Loop for symbol_range
                        $allResponse = array();
                        $alltoken = array();
                        for ($i=(-$symbol_range); $i <= $symbol_range ; $i++) { 
                            // getLTP by Angle Api
                            $exchangeVal = $angleApiInstuments->exch_seg;
                            $tokenVal = $angleApiInstuments->token;
                            $nameVal = $angleApiInstuments->name;
                            $ltpByApi = $this->getLTP($exchangeVal,$nameVal,$tokenVal);
                            $givenLtp = $ltpByApi['data']['ltp'];
                            $response = $this->getStrickData($nameVal,$exchangeVal,$givenLtp ,$i , $i);
                            // dd($response);
                            array_push($allResponse,$response[0][0]);
                            array_push($allResponse,$response[1][0]);
                            array_push($alltoken,$response[0][1]);
                            array_push($alltoken,$response[1][1]);
                        }
                        $historicalData = $this->get_historical_api_data($allResponse,$alltoken);
                    }

                    // For NSE Exch Records
                    if($angleApiInstuments->exch_seg == "NSE"){
                        // dd($angleApiInstuments);
                        $exchangeVal = $angleApiInstuments->exch_seg;
                        // dd($exchangeVal);
                        $tokenVal = $angleApiInstuments->token;
                        $nameVal = $angleApiInstuments->name;
                        $ltpByApi = $this->getLTP($exchangeVal,$nameVal,$tokenVal);
                        // dd($ltpByApi);
                        $givenLtp = $ltpByApi['data']['ltp'];
                        $expiry_dates = $this->get_upcoming_expiry($nameVal,$exchangeVal);
                        // dd($expiry_dates);
                        // 0 => "07-02-2024"
                        // 1 => "14-02-2024"
                        $allResponse2 = array();
                        $alltoken2 = array();
                        for ($i=(-$symbol_range); $i <= $symbol_range ; $i++) { 
                            $response = $this->get_atm_strike_symbol_angel($givenLtp ,$nameVal, $nameVal , $exchangeVal , $expiry_dates, $i , $i);
                            array_push($allResponse2,$response[0][0]);
                            array_push($allResponse2,$response[1][0]);
                            array_push($alltoken2,$response[0][1]);
                            array_push($alltoken2,$response[1][1]);
                        }
                        $historicalData2 = $this->get_historical_api_data($allResponse2,$alltoken2);
                    }

                    sleep(4);
                }
                return "Completed";
            }else{
                return null;
            }
        }else{
           return null;
        }   
    }   
    
    
    public function getWatchListRecords($payload){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "mode": "FULL",
                "exchangeTokens": '.$payload.'
            }',
            CURLOPT_HTTPHEADER => array(
                'X-UserType: USER',
                'X-SourceID: WEB',
                'X-PrivateKey: '.$this->apiKey,
                'X-ClientLocalIP: '.$this->clientLocalIp,
                'X-ClientPublicIP: '.$this->clientPublicIp,
                'X-MACAddress: '.$this->macAddress,
                'Content-Type: application/json',
                'Authorization: Bearer '.$jwtToken
            ),
            ));

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $errData;
            }
            $errData = json_decode($response,true);
            return $errData;
        }
        return $errData;
    }

}
