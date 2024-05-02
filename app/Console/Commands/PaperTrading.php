<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\AngelApiAuth;
use App\Models\WatchList;
use App\Models\WishlistData;
use Illuminate\Support\Facades\DB;

class PaperTrading extends Command
{

    use AngelApiAuth;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paper_trading:every_minute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Will Update the Ltp in paper trade table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);

        $todayDate = date('Y-m-d');
        $paperTrade = \DB::connection('mysql_rm')->table('Paper Trade')->orderbY('id','DESC')->select('*')/*->whereDate('expiry', '>',$todayDate)*/->get();
       
        if(count($paperTrade)){
            $MCX_TOKEN = [];
            $NFO_TOKEN = [];
            foreach ($paperTrade as $key => $trade) {
                if($trade->exchange == "MCX"){
                    array_push($MCX_TOKEN,$trade->ce_exchange_token);
                    array_push($MCX_TOKEN,$trade->pe_exchange_token);
                }else if($trade->exchange == "NFO"){
                    array_push($NFO_TOKEN,$trade->ce_exchange_token);
                    array_push($NFO_TOKEN,$trade->pe_exchange_token);
                }
            }

            // Update LTP of the Upcoming Data
            $payload = [
                'MCX'=>$MCX_TOKEN,
                'NFO'=>$NFO_TOKEN
            ];
    
            $chunk['MCX'] = array_chunk($payload['MCX'],50,true);
            $chunk['NFO'] = array_chunk($payload['NFO'],50,true);
    
            $responseData = [];
            $index = 0;
    
            foreach ($chunk as $key => $value) {
                if(count($value)){
                    $finalpayLoad = [
                        $key => array_map('json_encode', $value[$index])
                    ];
                    $payload = json_encode($finalpayLoad,true);
                    $respond = $this->updatePaperTradeData($payload);
                    if(isset($respond)){
                        if($respond['status'] == true){
                            array_push($responseData,$respond['data']['fetched']);
                        }
                    }
                    sleep(3);
                }
            }     

            dd($responseData);

            // Update LTP DATA FOR TOKENS   
            if(count($responseData)){
                foreach ($responseData as $key => $respond) {
                    foreach ($respond as $key => $value) {
                        // GET CE OR PE 
                        $type = substr($value['tradingSymbol'],-2,2);
                        if($type == "CE"){
                            $paperTrade = \DB::connection('mysql_rm')->table('Paper Trade')->select('*')->where('ce_exchange_token',$value['symbolToken'])->update(['ce_ltp'=>$value['ltp']]);
                        }else if($type == "PE"){
                            $paperTrade = \DB::connection('mysql_rm')->table('Paper Trade')->select('*')->where('pe_exchange_token',$value['symbolToken'])->update(['pe_ltp'=>$value['ltp']]);
                        }
                    }
                }  
            }  
        }
    }
}

// \DB::connection('mysql_rm')
// ->table('Paper Trade')
// ->where('ce_exchange_token', $value['symbolToken'])
// ->orderBy('id', 'DESC')
// ->update([
//     'ce_ltp' => $value['ltp'],
//     'mtm' => \DB::raw("(($value[ltp] + pe_ltp) * lot_size) - combined_premium_ce_pe")
// ]);
// \DB::connection('mysql_rm')
// ->table('Paper Trade')
// ->where('ce_exchange_token', $value['symbolToken'])
// ->orderBy('id', 'DESC')
// ->update([
//     'pe_ltp' => $value['ltp'],
//     'mtm' => \DB::raw("(($value[ltp] + ce_ltp) * lot_size) - combined_premium_ce_pe")
// ]);