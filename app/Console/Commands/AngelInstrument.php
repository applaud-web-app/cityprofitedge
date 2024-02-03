<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;
class AngelInstrument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'angel_instrument:daily_update';

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
        $source = 'https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json';
        $data = [];
        LazyCollection::fromJson($source)->each(function (string $value, string $key) {
           $d = json_decode($value,true);
           \DB::table('angel_api_instruments')->insert([
                'token'=>$d['token'],
                'symbol_name'=>$d['symbol'],
                'name'=>$d['name'],
                'strike'=>$d['strike'],
                'lotsize'=>$d['lotsize'],
                'instrumenttype'=>$d['instrumenttype'],
                'exch_seg'=>$d['exch_seg'],
                'expiry'=>$d['expiry'],
                'tick_size'=>$d['tick_size']
           ]);
        });

        // {"token":"12048","symbol":"UTIRAP35P1-MF","name":"UTIRAP35P1","expiry":"","strike":"-1.000000","lotsize":"1","instrumenttype":"","exch_seg":"NSE","tick_size":"1.000000"}
    }
}
