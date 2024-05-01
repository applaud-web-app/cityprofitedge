<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StengthTb;

class StoreStrengthData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store_strength_data:every_fifteen_minute';

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

    function market_sentiment($atm_minus_1_ce_iv, $atm_minus_1_pe_iv, $atm_minus_1_ce_delta, $atm_minus_1_pe_delta, $atm_minus_1_ce_theta, $atm_minus_1_pe_theta, $atm_minus_1_ce_vega, $atm_minus_1_pe_vega, $atm_minus_1_ce_gamma, $atm_minus_1_pe_gamma, $atm_ce_iv, $atm_pe_iv, $atm_ce_delta, $atm_pe_delta, $atm_ce_theta, $atm_pe_theta, $atm_ce_vega, $atm_pe_vega, $atm_ce_gamma, $atm_pe_gamma, $atm_plus_1_ce_iv, $atm_plus_1_pe_iv, $atm_plus_1_ce_delta, $atm_plus_1_pe_delta, $atm_plus_1_ce_theta, $atm_plus_1_pe_theta, $atm_plus_1_ce_vega, $atm_plus_1_pe_vega, $atm_plus_1_ce_gamma, $atm_plus_1_pe_gamma){

        // Determine market sentiment based on the provided criteria for each strike
        $sentiment_atm_minus_1 = $this->calculate_sentiment($atm_minus_1_ce_iv, $atm_minus_1_pe_iv, $atm_minus_1_ce_delta, $atm_minus_1_pe_delta, $atm_minus_1_ce_theta, $atm_minus_1_pe_theta, $atm_minus_1_ce_vega, $atm_minus_1_pe_vega, $atm_minus_1_ce_gamma, $atm_minus_1_pe_gamma);
        $sentiment_atm = $this->calculate_sentiment($atm_ce_iv, $atm_pe_iv, $atm_ce_delta, $atm_pe_delta, $atm_ce_theta, $atm_pe_theta, $atm_ce_vega, $atm_pe_vega, $atm_ce_gamma, $atm_pe_gamma);
        $sentiment_atm_plus_1 = $this->calculate_sentiment($atm_plus_1_ce_iv, $atm_plus_1_pe_iv, $atm_plus_1_ce_delta, $atm_plus_1_pe_delta, $atm_plus_1_ce_theta, $atm_plus_1_pe_theta, $atm_plus_1_ce_vega, $atm_plus_1_pe_vega, $atm_plus_1_ce_gamma, $atm_plus_1_pe_gamma);

        // Calculate strength based on the difference between CE and PE values
        $strength_atm_minus_1 = $this->calculate_strength($atm_minus_1_ce_iv, $atm_minus_1_pe_iv);
        $strength_atm = $this->calculate_strength($atm_ce_iv, $atm_pe_iv);
        $strength_atm_plus_1 = $this->calculate_strength($atm_plus_1_ce_iv, $atm_plus_1_pe_iv);

        // Combine sentiments and strengths for all three strikes
        $market_sentiment = array(
            'ATM-1' => array_merge($sentiment_atm_minus_1, ['Strength' => $strength_atm_minus_1]),
            'ATM' => array_merge($sentiment_atm, ['Strength' => $strength_atm]),
            'ATM+1' => array_merge($sentiment_atm_plus_1, ['Strength' => $strength_atm_plus_1])
        );

        return $market_sentiment;
    }

    function calculate_sentiment($ce_iv, $pe_iv, $ce_delta, $pe_delta, $ce_theta, $pe_theta, $ce_vega, $pe_vega, $ce_gamma, $pe_gamma) {
        // Calculate the overall Delta, Theta, Vega for CE and PE
        $overall_delta = $ce_delta + $pe_delta;
        $overall_theta = $ce_theta + $pe_theta;
        $overall_vega = $ce_vega + $pe_vega;
        $overall_gamma = $ce_gamma + $pe_gamma;
        
        // Determine sentiment based on the provided criteria
        $sentiment = '';        
        if ($ce_iv > $pe_iv) {
            $sentiment = "Bullish";
        } elseif ($ce_iv < $pe_iv) {
            $sentiment = "Bearish";
        } else {
            $sentiment = "Neutral";
        }
        
        if ($overall_delta > 0) {
            $sentiment .= " Bullish";
        } elseif ($overall_delta < 0) {
            $sentiment .= " Bearish";
        } else {
            $sentiment .= " Neutral";
        }
        
        if ($overall_theta > 0) {
            $sentiment .= " Bullish";
        } elseif ($overall_theta < 0) {
            $sentiment .= " Bearish";
        } else {
            $sentiment .= " Neutral";
        }
        
        if ($overall_vega > 0) {
            $sentiment .= " Bullish";
        } elseif ($overall_vega < 0) {
            $sentiment .= " Bearish";
        } else {
            $sentiment .= " Neutral";
        }

        if ($overall_gamma > 0) {
            $sentiment .= " Bullish";
        } elseif ($overall_gamma < 0) {
            $sentiment .= " Bearish";
        } else {
            $sentiment .= " Neutral";
        }
        
        return array(
            'IV' => array('CE' => $ce_iv, 'PE' => $pe_iv),
            'Delta' => array('CE' => $ce_delta, 'PE' => $pe_delta),
            'Theta' => array('CE' => $ce_theta, 'PE' => $pe_theta),
            'Vega' => array('CE' => $ce_vega, 'PE' => $pe_vega),
            'Gamma' => array('CE' => $ce_gamma, 'PE' => $pe_gamma),
            'Sentiment' => $sentiment
        );
    }

    // Function to calculate strength based on the difference between CE and PE values
    function calculate_strength($ce_iv, $pe_iv) {
        if ($ce_iv > $pe_iv) {
            return "Bullish";
        } elseif ($ce_iv < $pe_iv) {
            return "Bearish";
        } else {
            return "Neutral";
        }
    }

    public function handle()
    {
        $todayDate = date("Y-m-d",strtotime('-1Day'));
        $timeframe = 15;
        $symbolArr = ['CRUDEOIL','BANKNIFTY','FINNIFTY','NIFTY','MIDCPNIFTY','NATURALGAS']; // REMOVED GOLD,SILVER 
        foreach ($symbolArr as $table) {
            $data =  \DB::connection('mysql_rm')->table($table)->select('*')->where(['date' => $todayDate, 'timeframe' => $timeframe])->whereIn('id', function ($query) use($todayDate,$timeframe,$table) {
                $query->selectRaw('MAX(id)')->from($table)->where(['date' => $todayDate, 'timeframe' => $timeframe])->groupBy('atm');
            })->get();
    
            if(count($data)){
                $atm_min_1_data = [];
                $atm_data = [];
                $atm_plus_1_data = [];
                foreach ($data as $key => $value) {
                    if($value->atm == "ATM"){
                        $atm_data['ce_symbol'] = $value->ce;
                        $atm_data['pe_symbol'] = $value->pe;
                        $atm_data['ce_iv'] = $value->ce_iv;
                        $atm_data['pe_iv'] = $value->pe_iv;
                        $atm_data['ce_delta'] = $value->ce_delta;
                        $atm_data['pe_delta'] = $value->pe_delta;
                        $atm_data['ce_theta'] = $value->ce_theta;
                        $atm_data['pe_theta'] = $value->pe_theta;
                        $atm_data['ce_vega'] = $value->ce_vega;
                        $atm_data['pe_vega'] = $value->pe_vega;
                        $atm_data['ce_gamma'] = $value->ce_gamma;
                        $atm_data['pe_gamma'] = $value->pe_gamma;
                    }else if($value->atm == "ATM-1"){
                        $atm_min_1_data['ce_symbol'] = $value->ce;
                        $atm_min_1_data['pe_symbol'] = $value->pe;
                        $atm_min_1_data['ce_iv'] = $value->ce_iv;
                        $atm_min_1_data['pe_iv'] = $value->pe_iv;
                        $atm_min_1_data['ce_delta'] = $value->ce_delta;
                        $atm_min_1_data['pe_delta'] = $value->pe_delta;
                        $atm_min_1_data['ce_theta'] = $value->ce_theta;
                        $atm_min_1_data['pe_theta'] = $value->pe_theta;
                        $atm_min_1_data['ce_vega'] = $value->ce_vega;
                        $atm_min_1_data['pe_vega'] = $value->pe_vega;
                        $atm_min_1_data['ce_gamma'] = $value->ce_gamma;
                        $atm_min_1_data['pe_gamma'] = $value->pe_gamma;
                    }else if($value->atm == "ATM+1"){
                        $atm_plus_1_data['ce_symbol'] = $value->ce;
                        $atm_plus_1_data['pe_symbol'] = $value->pe;
                        $atm_plus_1_data['ce_iv'] = $value->ce_iv;
                        $atm_plus_1_data['pe_iv'] = $value->pe_iv;
                        $atm_plus_1_data['ce_delta'] = $value->ce_delta;
                        $atm_plus_1_data['pe_delta'] = $value->pe_delta;
                        $atm_plus_1_data['ce_theta'] = $value->ce_theta;
                        $atm_plus_1_data['pe_theta'] = $value->pe_theta;
                        $atm_plus_1_data['ce_vega'] = $value->ce_vega;
                        $atm_plus_1_data['pe_vega'] = $value->pe_vega;
                        $atm_plus_1_data['ce_gamma'] = $value->ce_gamma;
                        $atm_plus_1_data['pe_gamma'] = $value->pe_gamma;
                    }
                }
    
                $response = $this->market_sentiment($atm_min_1_data['ce_iv'],$atm_min_1_data['pe_iv'],$atm_min_1_data['ce_delta'],$atm_min_1_data['pe_delta'],$atm_min_1_data['ce_theta'],$atm_min_1_data['pe_theta'],$atm_min_1_data['ce_vega'],$atm_min_1_data['pe_vega'],$atm_min_1_data['ce_gamma'],$atm_min_1_data['pe_gamma'],$atm_data['ce_iv'],$atm_data['pe_iv'],$atm_data['ce_delta'],$atm_data['pe_delta'],$atm_data['ce_theta'],$atm_data['pe_theta'],$atm_data['ce_vega'],$atm_data['pe_vega'],$atm_data['ce_gamma'],$atm_data['pe_gamma'],$atm_plus_1_data['ce_iv'],$atm_plus_1_data['pe_iv'],$atm_plus_1_data['ce_delta'],$atm_plus_1_data['pe_delta'],$atm_plus_1_data['ce_theta'],$atm_plus_1_data['pe_theta'],$atm_plus_1_data['ce_vega'],$atm_plus_1_data['pe_vega'],$atm_plus_1_data['ce_gamma'],$atm_plus_1_data['pe_gamma']);

    
                if(count($response)){
                   try {
                        foreach ($response as $key => $value) {
                            $val = explode(" ",$value['Sentiment']);
                            // dd($val[0],$val[1],$val[2],$val[3],$val[4],$value['Strength']);
                            $ce_symbol = "";
                            $pe_symbol = "";
                            if($key == "ATM"){
                                $ce_symbol = $atm_data['ce_symbol'];
                                $pe_symbol = $atm_data['pe_symbol'];
                            }else if($key == "ATM+1"){
                                $ce_symbol = $atm_plus_1_data['ce_symbol'];
                                $pe_symbol = $atm_plus_1_data['pe_symbol'];
                            }else if($key == "ATM-1"){
                                $ce_symbol = $atm_min_1_data['ce_symbol'];
                                $pe_symbol = $atm_min_1_data['pe_symbol'];
                            }
                            $addStrength = StengthTb::create([
                                'symbol_name'=>$table,
                                'ce_symbol'=>$ce_symbol,
                                'pe_symbol'=>$pe_symbol,
                                'atm'=>$key,
                                'timeframe'=>15,
                                'iv'=>$val[0],
                                'delta'=>$val[1],
                                'theta'=>$val[2],
                                'vega'=>$val[3],
                                'gamma'=>$val[4],
                                'strength'=>$value['Strength']
                            ]);
                        }
                   } catch (\Throwable $th) {
                        dd($th->getMessage());
                   }
                }
                
            }
        }

    }

}
