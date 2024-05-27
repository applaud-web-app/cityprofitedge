<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Traits\AngelApiAuth;
use App\Models\WatchList;
use App\Models\WishlistData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
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
        // Artisan::call('queue:work');
        set_time_limit(0);
        $symbolArr = allTradeSymbols();
        $todayDate = date("Y-m-d");
        $responseData = [];
        $error = [];
        // dd($symbolArr);
        $arr = [];
        foreach ($symbolArr as $key => $v) {
            $MCXpayload = [];
            $NFOpayload = [];
            $extraTable = ['FII DII PRO','LTP','IV Theta Sentiments','MATCH-DELTA','MATCH-THETA','MATCH-PREMIUM','MATCH-IV','Paper Trade','Predictions'];
            if(in_array($v,$extraTable)){
                continue;
            }
            $todayData = date('Y-m-d');
            $data = \DB::connection('mysql_rm')->table($v)->select('ce as symbol_ce','pe as symbol_pe','ce_token as token_ce','pe_token as token_pe','exchange')->where('date',$todayData)->orderBy('id','DESC')->distinct('symbol_ce','symbol_pe')->get();
            foreach ($data as $key => $value) {
                if($value->exchange == "MCX"){
                    array_push($MCXpayload,$value->token_ce);
                    array_push($MCXpayload,$value->token_pe);
                }else if($value->exchange == "NFO"){
                    array_push($NFOpayload,$value->token_ce);
                    array_push($NFOpayload,$value->token_pe);
                }
            }
            $payload = [
                'MCX'=>$MCXpayload,
                'NFO'=>$NFOpayload
            ];
            $chunk['MCX'] = array_chunk($payload['MCX'],50,true);
            $chunk['NFO'] = array_chunk($payload['NFO'],50,true);
            
            foreach ($chunk as $key => $value) {
                foreach ($value as $tokens) {
                    $finalpayLoad = [ $key=>array_map('json_encode',$tokens)];
                    $payload = json_encode($finalpayLoad,true);
                    $respond = $this->getWatchListRecords($payload);

                   
                    // $arr[] = $respond;
                    if(isset($respond)){
                        if($respond['status'] == true){
                            array_push($responseData,$respond['data']['fetched']);
                        }
                    }
                    array_push($error,$respond);
                    sleep(1);
                }
            }
        }


        
       

        // dd($arr);
        // dd($responseData,$error,$error2);
        // $payload = [
        //     'MCX'=>$MCXpayload,
        //     'NFO'=>$NFOpayload
        // ];
        // $chunk['MCX'] = array_chunk($payload['MCX'],50,true);
        // $chunk['NFO'] = array_chunk($payload['NFO'],50,true);
        // $responseData = [];
        // $error = [];
        // $error2 = [];
        // $index = 0;
        // foreach ($chunk as $key => $value) {
        //     foreach ($value as $tokens) {
        //         $finalpayLoad = [ $key=>array_map('json_encode',$tokens)];
        //         $payload = json_encode($finalpayLoad,true);
        //         $respond = $this->getWatchListRecords($payload);
        //         if(isset($respond)){
        //             if($respond['status'] == true){
        //                 array_push($responseData,$respond['data']['fetched']);
        //             }
        //         }
        //         array_push($error,$payload);
        //         array_push($error2,$respond);
        //     }
        // }
        // dd($responseData,$error,$error2);
        // Insert Data To Watchlist
        // dd($responseData);
        if(count($responseData)){
            foreach ($responseData as $key => $respond) {
                foreach ($respond as $key => $value) {

                    try{
                        WishlistData::updateOrCreate(
                            ['symbol_name'=>$value['tradingSymbol'],'ins_date'=>$todayDate],[
                                'symbol_name' => $value['tradingSymbol'],
                                'symbolToken' => $value['symbolToken'],
                                'symbol' => $v,
                                'ins_date' => $todayDate,
                                'exchange' => $value['exchange'],
                                'ltp' => $value['ltp'],
                                'open' => $value['open'],
                                'high' => $value['high'],
                                'low' => $value['low'],
                                'close' => $value['close'],
                                'lastTradeQty' => $value['lastTradeQty'],
                                'exchFeedTime' => $value['exchFeedTime'],
                                'exchTradeTime' => $value['exchTradeTime'],
                                'netChange' => $value['netChange'],
                                'percentChange' => $value['percentChange'],
                                'avgPrice' => $value['avgPrice'],
                                'tradeVolume' => $value['tradeVolume'],
                                'opnInterest' => $value['opnInterest'],
                                'lowerCircuit' => $value['lowerCircuit'],
                                'upperCircuit' => $value['upperCircuit'],
                                'totBuyQuan' => $value['totBuyQuan'],
                                'totSellQuan' => $value['totSellQuan'],
                                'WeekLow52' => $value['52WeekLow'],
                                'WeekHigh52' => $value['52WeekHigh'],
                            ]
                        );
                        $type = substr($value['tradingSymbol'],-2,2);
                        if($type == "CE"){
                            // FOR MATCH DELTA CE SYMBOLS
                            $matchDelta = \DB::connection('mysql_rm')->table('MATCH-DELTA')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
                            // FOR MATCH THETA CE SYMBOLS
                            $matchTheta = \DB::connection('mysql_rm')->table('MATCH-THETA')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
                            // FOR MATCH PREMIUM CE SYMBOLS
                            $matchPremium = \DB::connection('mysql_rm')->table('MATCH-PREMIUM')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
                            // FOR MATCH IV CE SYMBOLS
                            $matchPremium = \DB::connection('mysql_rm')->table('MATCH-IV')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
                        }else if($type == "PE"){
                            // FOR MATCH DELTA PE SYMBOLS
                            $matchDelta = \DB::connection('mysql_rm')->table('MATCH-DELTA')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
                            // FOR MATCH THETA CE SYMBOLS
                            $matchTheta = \DB::connection('mysql_rm')->table('MATCH-THETA')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
                            // FOR MATCH PREMIUM CE SYMBOLS
                            $matchPremium = \DB::connection('mysql_rm')->table('MATCH-PREMIUM')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
                            // FOR MATCH IV CE SYMBOLS
                            $matchPremium = \DB::connection('mysql_rm')->table('MATCH-IV')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
                        }
                    }catch(\Exception $e){
                        // \DB::table('test')->insert(['name'=>$e->getMessage()]);
                    }
                    



                    // $wishlist = new WishlistData;
                    // $wishlist->symbol_name = $value['tradingSymbol'];
                    // $wishlist->symbolToken = $value['symbolToken'];
                    // $wishlist->exchange = $value['exchange'];
                    // $wishlist->ltp = $value['ltp'];
                    // $wishlist->open = $value['open'];
                    // $wishlist->high = $value['high'];
                    // $wishlist->low = $value['low'];
                    // $wishlist->close = $value['close'];
                    // $wishlist->lastTradeQty = $value['lastTradeQty'];
                    // $wishlist->exchFeedTime = $value['exchFeedTime'];
                    // $wishlist->exchTradeTime = $value['exchTradeTime'];
                    // $wishlist->netChange = $value['netChange'];
                    // $wishlist->percentChange = $value['percentChange'];
                    // $wishlist->avgPrice = $value['avgPrice'];
                    // $wishlist->tradeVolume = $value['tradeVolume'];
                    // $wishlist->opnInterest = $value['opnInterest'];
                    // $wishlist->lowerCircuit = $value['lowerCircuit'];
                    // $wishlist->upperCircuit = $value['upperCircuit'];
                    // $wishlist->totBuyQuan = $value['totBuyQuan'];
                    // $wishlist->totSellQuan = $value['totSellQuan'];
                    // $wishlist->WeekLow52 = $value['52WeekLow'];
                    // $wishlist->WeekHigh52 = $value['52WeekHigh'];
                    // $wishlist->save();
                   
                }
            }
        }
    }
}