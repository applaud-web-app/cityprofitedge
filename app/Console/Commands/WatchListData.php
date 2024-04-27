<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\AngelApiAuth;
use App\Models\WatchList;
use App\Models\WishlistData;
use Illuminate\Support\Facades\DB;

class WatchListData extends Command
{
    use AngelApiAuth;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch-list-data:every_minute';

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
        $symbolArr = allTradeSymbols();
        $todayDate = date("Y-m-d");
        
        $MCXpayload = [];
        $NFOpayload = [];

        foreach ($symbolArr as $key => $v) {

            $extraTable = ['FII DII PRO','LTP'];

            if(in_array($v,$extraTable)){
                continue;
            }

            $data = \DB::connection('mysql_rm')->table($v)->select('ce as symbol_ce','pe as symbol_pe','ce_token as token_ce','pe_token as token_pe','exchange')->orderBy('id','DESC')->get(); 

            foreach ($data as $key => $value) {
                if($value->exchange == "MCX"){
                    array_push($MCXpayload,$value->token_ce);
                    array_push($MCXpayload,$value->token_pe);
                }else if($value->exchange == "NFO"){
                    array_push($NFOpayload,$value->token_ce);
                    array_push($NFOpayload,$value->token_pe);
                }
            }
        }

        $payload = [
            'MCX'=>$MCXpayload,
            'NFO'=>$NFOpayload
        ];

        $chunk['MCX'] = array_chunk($payload['MCX'],50,true);
        $chunk['NFO'] = array_chunk($payload['NFO'],50,true);

        $responseData = [];
        $index = 0;

        foreach ($chunk as $key => $value) {
            $finalpayLoad = [
                $key=>array_map('json_encode', $value[$index])
            ];
            $payload = json_encode($finalpayLoad,true);
            $respond = $this->getWatchListRecords($payload);
            if(isset($respond)){
                if($respond['status'] == true){
                    //$responseData = $respond['data']['fetched'];
                    array_push($responseData,$respond['data']['fetched']);
                }
            }
            sleep(1);
        }     

        // Insert Data To Watchlist
        if(count($responseData)){
            foreach ($responseData as $key => $respond) {
                foreach ($respond as $key => $value) {
                    $wishlist = new WishlistData;
                    $wishlist->symbol_name = $value['tradingSymbol'];
                    $wishlist->symbolToken = $value['symbolToken'];
                    $wishlist->exchange = $value['exchange'];
                    $wishlist->ltp = $value['ltp'];
                    $wishlist->open = $value['open'];
                    $wishlist->high = $value['high'];
                    $wishlist->low = $value['low'];
                    $wishlist->close = $value['close'];
                    $wishlist->lastTradeQty = $value['lastTradeQty'];
                    $wishlist->exchFeedTime = $value['exchFeedTime'];
                    $wishlist->exchTradeTime = $value['exchTradeTime'];
                    $wishlist->netChange = $value['netChange'];
                    $wishlist->percentChange = $value['percentChange'];
                    $wishlist->avgPrice = $value['avgPrice'];
                    $wishlist->tradeVolume = $value['tradeVolume'];
                    $wishlist->opnInterest = $value['opnInterest'];
                    $wishlist->lowerCircuit = $value['lowerCircuit'];
                    $wishlist->upperCircuit = $value['upperCircuit'];
                    $wishlist->totBuyQuan = $value['totBuyQuan'];
                    $wishlist->totSellQuan = $value['totSellQuan'];
                    $wishlist->WeekLow52 = $value['52WeekLow'];
                    $wishlist->WeekHigh52 = $value['52WeekHigh'];
                    $wishlist->save();
                }
            }  
        }   

    }
}
