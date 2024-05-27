<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Traits\AngelApiAuth;
// use App\Models\WatchList;
// use App\Models\WishlistData;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Artisan;


// class WatchListData extends Command
// {
//     use AngelApiAuth;
//     /**
//      * The name and signature of the console command.
//      *
//      * @var string
//      */
//     protected $signature = 'watch-list-data:every_minute';

//     /**
//      * The console command description.
//      *
//      * @var string
//      */
//     protected $description = 'Command description';

//     /**
//      * Execute the console command.
//      *
//      * @return int
//      */
//     public function handle()
//     {
//         // Artisan::call('queue:work');
//         set_time_limit(0);
//         $symbolArr = allTradeSymbols();
//         $todayDate = date("Y-m-d");
        
        

//         foreach ($symbolArr as $key => $v) {
//             $MCXpayload = [];
//             $NFOpayload = [];
//             $extraTable = ['FII DII PRO','LTP','IV Theta Sentiments','MATCH-DELTA','MATCH-THETA','MATCH-PREMIUM','MATCH-IV','Paper Trade','Predictions'];
//             if(in_array($v,$extraTable)){
//                 continue;
//             }
//             $data = \DB::connection('mysql_rm')->table($v)->select('ce as symbol_ce','pe as symbol_pe','ce_token as token_ce','pe_token as token_pe','exchange')->orderBy('id','DESC')->get(); 

//             foreach ($data as $key => $value) {
//                 if($value->exchange == "MCX"){
//                     array_push($MCXpayload,$value->token_ce);
//                     array_push($MCXpayload,$value->token_pe);
//                 }else if($value->exchange == "NFO"){
//                     array_push($NFOpayload,$value->token_ce);
//                     array_push($NFOpayload,$value->token_pe);
//                 }
//             }

//             if(!empty($MCXpayload)){
//                 $mcxChunkData = array_chunk($MCXpayload,50,true);
//                 foreach($mcxChunkData as $val){
//                     $responseData = [];
//                     $finalpayLoad = [
//                         'MCX'=>array_map('json_encode', $val)
//                     ];
//                     $payload = json_encode($finalpayLoad,true);
//                     $respond = $this->getWatchListRecords($payload);
//                     if(isset($respond)){
//                         if($respond['status'] == true){
//                             $responseData = $respond['data']['fetched'];
//                              if(count($responseData)){
//                                 foreach ($responseData as $key => $value) {

//                                         WishlistData::updateOrCreate(
//                                             ['symbol_name'=>$value['tradingSymbol'],'ins_date'=>$todayDate],[
//                                                 'symbol_name' => $value['tradingSymbol'],
//                                                 'symbolToken' => $value['symbolToken'],
//                                                 'symbol' => $v,
//                                                 'ins_date' => $todayDate,
//                                                 'exchange' => $value['exchange'],
//                                                 'ltp' => $value['ltp'],
//                                                 'open' => $value['open'],
//                                                 'high' => $value['high'],
//                                                 'low' => $value['low'],
//                                                 'close' => $value['close'],
//                                                 'lastTradeQty' => $value['lastTradeQty'],
//                                                 'exchFeedTime' => $value['exchFeedTime'],
//                                                 'exchTradeTime' => $value['exchTradeTime'],
//                                                 'netChange' => $value['netChange'],
//                                                 'percentChange' => $value['percentChange'],
//                                                 'avgPrice' => $value['avgPrice'],
//                                                 'tradeVolume' => $value['tradeVolume'],
//                                                 'opnInterest' => $value['opnInterest'],
//                                                 'lowerCircuit' => $value['lowerCircuit'],
//                                                 'upperCircuit' => $value['upperCircuit'],
//                                                 'totBuyQuan' => $value['totBuyQuan'],
//                                                 'totSellQuan' => $value['totSellQuan'],
//                                                 'WeekLow52' => $value['52WeekLow'],
//                                                 'WeekHigh52' => $value['52WeekHigh'],
//                                             ]
//                                         );
                                        
//                                         $type = substr($value['tradingSymbol'],-2,2);
//                                         if($type == "CE"){
    
//                                             // FOR MATCH DELTA CE SYMBOLS
//                                             $matchDelta = \DB::connection('mysql_rm')->table('MATCH-DELTA')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH THETA CE SYMBOLS
//                                             $matchTheta = \DB::connection('mysql_rm')->table('MATCH-THETA')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH PREMIUM CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-PREMIUM')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH IV CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-IV')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
    
//                                         }else if($type == "PE"){
//                                             // FOR MATCH DELTA PE SYMBOLS
//                                             $matchDelta = \DB::connection('mysql_rm')->table('MATCH-DELTA')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH THETA CE SYMBOLS
//                                             $matchTheta = \DB::connection('mysql_rm')->table('MATCH-THETA')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH PREMIUM CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-PREMIUM')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH IV CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-IV')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                         }
//                                 }  
//                             }   
//                         }
//                     }
//                     sleep(1);
//                 }
    
//             }
    
//             if(!empty($NFOpayload)){
//                 $nfoChunkData = array_chunk($NFOpayload,50,true);
//                 foreach($nfoChunkData as $val){
//                     $responseData = [];
//                         $finalpayLoad = [
//                         'NFO'=>array_map('json_encode', $val)
//                     ];
//                     $payload = json_encode($finalpayLoad,true);
//                     $respond = $this->getWatchListRecords($payload);
//                     if(isset($respond)){
//                         if($respond['status'] == true){
//                             $responseData = $respond['data']['fetched'];
//                              if(count($responseData)){
//                                 foreach ($responseData as $key => $value) {
//                                         WishlistData::updateOrCreate(
//                                             ['symbol_name'=>$value['tradingSymbol'],'ins_date'=>$todayDate],[
//                                                 'symbol_name' => $value['tradingSymbol'],
//                                                 'symbolToken' => $value['symbolToken'],
//                                                 'symbol' => $v,
//                                                 'ins_date' => $todayDate,
//                                                 'exchange' => $value['exchange'],
//                                                 'ltp' => $value['ltp'],
//                                                 'open' => $value['open'],
//                                                 'high' => $value['high'],
//                                                 'low' => $value['low'],
//                                                 'close' => $value['close'],
//                                                 'lastTradeQty' => $value['lastTradeQty'],
//                                                 'exchFeedTime' => $value['exchFeedTime'],
//                                                 'exchTradeTime' => $value['exchTradeTime'],
//                                                 'netChange' => $value['netChange'],
//                                                 'percentChange' => $value['percentChange'],
//                                                 'avgPrice' => $value['avgPrice'],
//                                                 'tradeVolume' => $value['tradeVolume'],
//                                                 'opnInterest' => $value['opnInterest'],
//                                                 'lowerCircuit' => $value['lowerCircuit'],
//                                                 'upperCircuit' => $value['upperCircuit'],
//                                                 'totBuyQuan' => $value['totBuyQuan'],
//                                                 'totSellQuan' => $value['totSellQuan'],
//                                                 'WeekLow52' => $value['52WeekLow'],
//                                                 'WeekHigh52' => $value['52WeekHigh'],
//                                             ]
//                                         );
//                                         $type = substr($value['tradingSymbol'],-2,2);
//                                         if($type == "CE"){
    
//                                             // FOR MATCH DELTA CE SYMBOLS
//                                             $matchDelta = \DB::connection('mysql_rm')->table('MATCH-DELTA')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH THETA CE SYMBOLS
//                                             $matchTheta = \DB::connection('mysql_rm')->table('MATCH-THETA')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH PREMIUM CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-PREMIUM')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH IV CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-IV')->where('ce',$value['tradingSymbol'])->update(['ce_ltp'=>$value['ltp']]);

    
//                                         }else if($type == "PE"){
//                                             // FOR MATCH DELTA PE SYMBOLS
//                                             $matchDelta = \DB::connection('mysql_rm')->table('MATCH-DELTA')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH THETA CE SYMBOLS
//                                             $matchTheta = \DB::connection('mysql_rm')->table('MATCH-THETA')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH PREMIUM CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-PREMIUM')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                             // FOR MATCH IV CE SYMBOLS
//                                             $matchPremium = \DB::connection('mysql_rm')->table('MATCH-IV')->where('pe',$value['tradingSymbol'])->update(['pe_ltp'=>$value['ltp']]);
    
//                                         }
//                                 }  
//                             }   
//                         }
//                     }
//                     sleep(1);
//                 }
                
    
//             }
//         }

        

//     }
// }
