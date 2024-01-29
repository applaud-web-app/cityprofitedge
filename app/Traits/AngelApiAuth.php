<?php
namespace App\Traits;
require app_path('Libraries/vendor/autoload.php');
use OTPHP\TOTP;
trait AngelApiAuth
{
    private $accountUserName = 'R834343';
    private $accountPassword = 'city@123';
    private $totp_secret = 'M46VHZKUIVRYBWO3CBF4B4BGLM';
    private $apiKey = 'fAIucOM2';
    private $pin = '1234';
    private $apiSecret = '1abcbf62-552f-4022-b073-d76bad9a06a5';
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

    public function getAngleApiData($type){
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
                "datatype": "'.$type.'",
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

   
    

}