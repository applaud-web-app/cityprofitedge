<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AngelApiInstrument;
use App\Models\AngleHistoricalApi;
use App\Http\Helpers\helpers;
use App\Traits\AngelApiAuth;
use Carbon\Carbon;

class AngelHistorical extends Command
{
    use AngelApiAuth;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'angleHistorical:every_minute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);
        $threeDays = Carbon::now()->subDays(3);
        AngleHistoricalApi::whereDate('timestamp','<',$threeDays)->delete();

        $jwtToken =  $this->generate_access_token();
        
        $tables = allTradeSymbols();
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
    }
}
