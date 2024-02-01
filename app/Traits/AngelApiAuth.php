<?php
namespace App\Traits;

use App\Models\AngelApiInstrument;
use App\Models\AngleHistoricalApi;

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

    public function fetchGreeksApi(){
        $jwtToken =  $this->generate_access_token();
        $errData = [];
        if($jwtToken!=null){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/marketData/v1/optionGreek',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "name": "CRUDEOIL",
                "expirydate": "14FEB2024"
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
            // dd($errData);
            return $errData;
        }
        return $errData;
    }


    
}