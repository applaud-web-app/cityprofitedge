@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
  .filter_dropdown *{
    color:black !important;
  }
  .filter_dropdown .btn-sm , .filter_dropdown .btn-sm i{
    color:#fff !important;
  }
  .chart2 .apexcharts-legend-series .apexcharts-legend-marker[rel="1"]{
    background: transparent !important;
    border: 2px solid rgb(0, 255, 0) !important;
    border-radius: 50% !important;
  }
  .chart2 .apexcharts-legend-series .apexcharts-legend-marker[rel="2"]{
    background: transparent !important;
    border: 2px solid rgb(255, 0, 0) !important;
    border-radius: 50% !important;
  }


    .tooltip-inner {
    
       font-size: 12px;
      }
 
      @media (min-width: 768px) {
        .tooltip-inner {
          max-width: 500px !important;
         
        }
      }

</style>
@endpush
<section class="pt-100 pb-100">
    <div class="container-fluid">
        <div class="row">
            {{-- First Graph Start --}}
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title">@lang('Option Analysis 1')</h5>
                        <div class="filter-box d-flex">
                          <form method="GET" class="d-flex align-items-center flex-wrap filter_dropdown">
                            <div class="mx-1">
                              <select name="symbol1" class="form-select" id="symbol1">
                                <option value="" disabled="" selected>Symbol Name</option>
                                @foreach ($symbolArr as $item)
                                  <option value="{{$item}}" {{$item == $table1 ? "selected" : ""}}>{{$item}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="mx-1">
                              <select name="atmRange1" class="form-select" id="atmRange1">
                                <option value="" disabled="" selected>Strike</option>
                                @for ($i = -3; $i <= 3; $i++)
                                  @if ($i == 0)
                                    <option value="ATM" {{$Atmtype1 == "ATM" ? "selected" : ""}} >ATM</option>
                                  @else
                                    <option value="ATM{{$i > 0 ? '+'.$i : $i}}" {{$Atmtype1 == "ATM".($i > 0 ? '+'.$i : $i) ? "selected" : ""}} >ATM {{$i > 0 ? '+'.$i : $i}}</option>
                                  @endif
                                @endfor
                            </select>
                           </div>
                           <div class="mx-1">
                              <select name="timeframe1" class="form-select" id="timeframe1">
                                <option value="" disabled="" selected>Time Frame</option>
                                <option value="1" {{$timeFrame1 == 1 ? 'selected' : ''}}>1</option>
                                <option value="3" {{$timeFrame1 == 3 ? 'selected' : ''}}>3</option>
                                <option value="5" {{$timeFrame1 == 5 ? 'selected' : ''}}>5</option>
                                </select>
                            </div>
                            <div class="mx-1">
                              <button class="btn btn-sm btn--base w-100 py-2" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                            </div>
                            <div class="mx-1">
                              <a href="{{url('/user/option-analysis')}}" class="btn btn-sm btn--base w-100 py-2" ><i class="las la-filter"></i> @lang('Refresh')</a>
                            </div>
                          </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="apex-analysis-chart" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
            {{-- First Graph Start --}}
            {{-- Second Graph Start --}}
            @php
                $atmData2 = [];
                foreach($data2 as $vvl){
                    if(isset($vvl->atm) && $vvl->atm==$Atmtype2){
                        $atmData2[] = $vvl;
                    }
                }
            @endphp
            @php $i=1; @endphp
            @forelse($atmData2 as $val)
              @php
                  $arrData2 = json_decode($val->data,true);   
                  $CE2 = array_slice($arrData2['CE'],-1);
                  $PE2 = array_slice($arrData2['PE'],-1);
                  $Date2 = array_slice($arrData2['Date'],-40);
                  $time2 = array_slice($arrData2['time'],-40);
                  $CE_consolidated2 = array_slice($arrData2['CE_consolidated'],-40);
                  $PE_consolidated2 = array_slice($arrData2['PE_consolidated'],-40);
                  $close_CE2 = array_slice($arrData2['close_CE'],-40);
                  $close_PE2 = array_slice($arrData2['close_PE'],-40); 
              @endphp
            @empty
            @endforelse  
            @php
              $time2 = array_map(function ($k , $y) use($Date2){
                  return date("d-M-Y",($Date2[$k]/1000)).', '.date("g:i a", strtotime($y));
              },array_keys($Date2) , $time2);

              $ceArray2 = array();
              $newArr12 = [];

              foreach($time2 as $i=>$y){
                if(!in_array($CE_consolidated2[$i],$ceArray2)){
                  $ceArray2 = [];
                  array_push($ceArray2,$CE_consolidated2[$i]);
                  $newArr12[] = [
                      'time'=>$y,
                      'price'=>$close_CE2[$i],
                      'text'=>$CE_consolidated2[$i],
                  ];
                }
              }

              $PeArray2 = array();
              $newArr22 = [];

              foreach($time2 as $i=>$y){
                if(!in_array($PE_consolidated2[$i],$PeArray2)){
                  $PeArray2 = [];
                  array_push($PeArray2,$PE_consolidated2[$i]);
                  $newArr22[] = [
                      'time'=>$y,
                      'price'=>$close_PE2[$i],
                      'text'=>$PE_consolidated2[$i],
                  ];
                }
              }
              $mergedArray2 = array_merge($newArr12, $newArr22);
            @endphp
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                          <h5 class="card-title mb-0"  data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right"  title="Open Interest CE/PE Signals indicate trading opportunities based on the open interest of Call Options (CE) and Put Options (PE) contracts. When there's a significant divergence between the open interest of CE and PE, it can signal a potential trading opportunity. A valid trading signal occurs when Call Options (CE) indicate bullish sentiment while Put Options (PE) suggest bearish sentiment. It is opportune to enter a trade when these signals align in this manner.">@lang('Option Analysis - Open Interest CE/PE Signals') </h5>
                          <small class="text-warning">Y- ClosePrice, X - Time</small>
                        </div>
                        <div class="filter-box d-flex">
                          <form method="GET" class="d-flex align-items-center flex-wrap filter_dropdown">
                            <div class="mx-1">
                              <select name="symbol2" class="form-select" id="symbol2">
                                <option value="" disabled="" selected>Symbol Name</option>
                                @foreach ($symbolArr as $item)
                                  <option value="{{$item}}" {{$item == $table2 ? "selected" : ""}}>{{$item}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="mx-1">
                              <select name="atmRange2" class="form-select" id="atmRange2">
                                <option value="" disabled="" selected>Strike</option>
                                @for ($i = -3; $i <= 3; $i++)
                                  @if ($i == 0)
                                    <option value="ATM" {{$Atmtype2 == "ATM" ? "selected" : ""}} >ATM</option>
                                  @else
                                    <option value="ATM{{$i > 0 ? '+'.$i : $i}}" {{$Atmtype2 == "ATM".($i > 0 ? '+'.$i : $i) ? "selected" : ""}} >ATM {{$i > 0 ? '+'.$i : $i}}</option>
                                  @endif
                                @endfor
                            </select>
                           </div>
                           <div class="mx-1">
                              <select name="timeframe2" class="form-select" id="timeframe2">
                                <option value="" disabled="" selected>Time Frame</option>
                                <option value="1" {{$timeFrame2 == 1 ? 'selected' : ''}}>1</option>
                                <option value="3" {{$timeFrame2 == 3 ? 'selected' : ''}}>3</option>
                                <option value="5" {{$timeFrame2 == 5 ? 'selected' : ''}}>5</option>
                                </select>
                            </div>
                            <div class="mx-1">
                              <button class="btn btn-sm btn--base w-100 py-2" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                            </div>
                            <div class="mx-1">
                              <a href="{{url('/user/option-analysis')}}" class="btn btn-sm btn--base w-100 py-2" ><i class="las la-filter"></i> @lang('Refresh')</a>
                            </div>
                          </form>
                        </div>
                    </div>
                    <div class="card-body chart2">
                        <div id="apex-analysis-chart3" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
            {{-- Second Graph End --}}

            {{-- Third Graph Start --}}
            @php
                $atmData3 = [];
                foreach($data3 as $vvl){
                    if(isset($vvl->atm) && $vvl->atm==$Atmtype3){
                        $atmData3[] = $vvl;
                    }
                }
            @endphp
            @php $i=1; @endphp
            @forelse($atmData3 as $val)
              @php
                  $arrData3 = json_decode($val->data,true);    
                  $CE3 = array_slice($arrData3['CE'],-1);
                  $PE3 = array_slice($arrData3['PE'],-1);
                  $Date3 = array_slice($arrData3['Date'],-40);
                  $time3 = array_slice($arrData3['time'],-40);
                  $vwap_CE_signal3 = array_slice($arrData3['vwap_CE_signal'],-40);
                  $vwap_PE_signal3 = array_slice($arrData3['vwap_PE_signal'],-40);
                  $close_CE3 = array_slice($arrData3['close_CE'],-40);
                  $close_PE3 = array_slice($arrData3['close_PE'],-40);
              @endphp
            @empty
            @endforelse 
            @php
              $time3 = array_map(function ($k , $y) use($Date3){
                  return date("d-M-Y",($Date3[$k]/1000)).', '.date("g:i a", strtotime($y));
              },array_keys($Date3) , $time3);

              $ceArray3 = array();
              $newArr13 = [];

              foreach($time3 as $i=>$y){
                if(!in_array($vwap_CE_signal3[$i],$ceArray3)){
                  $ceArray3 = [];
                  array_push($ceArray3,$vwap_CE_signal3[$i]);
                  $newArr13[] = [
                      'time'=>$y,
                      'price'=>$close_CE3[$i],
                      'text'=>$vwap_CE_signal3[$i],
                  ];
                }
              }

              $PeArray3 = array();
              $newArr23 = [];

              foreach($time3 as $i=>$y){
                if(!in_array($vwap_PE_signal3[$i],$PeArray3)){
                  $PeArray3 = [];
                  array_push($PeArray3,$vwap_PE_signal3[$i]);
                  $newArr23[] = [
                      'time'=>$y,
                      'price'=>$close_PE3[$i],
                      'text'=>$vwap_PE_signal3[$i],
                  ];
                }
              }
              $mergedArray3 = array_merge($newArr13, $newArr23);
            @endphp
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                       <div>
                          <h5 class="card-title mb-0"  data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right"  title="VWAP CE/PE Signals refer to trading signals derived from the Volume Weighted Average Price (VWAP) specifically for Call Options (CE) and Put Options (PE) contracts in a given market. VWAP is a technical analysis tool that calculates the average price of a security over a specified period, weighted by trading volume. Traders often use VWAP to identify trends, support, and resistance levels in the market. A valid trading signal occurs when Call Options (CE) indicate bullish sentiment while Put Options (PE) suggest bearish sentiment. It is opportune to enter a trade when these signals align in this manner.">@lang('Option Analysis - VWAP CE/PE Signals') </h5>
                          <small class="text-warning">Y- ClosePrice, X - Time</small>
                       </div>
                        <div class="filter-box d-flex">
                          <form method="GET" class="d-flex align-items-center flex-wrap filter_dropdown">
                            <div class="mx-1">
                              <select name="symbol3" class="form-select" id="symbol3">
                                <option value="" disabled="" selected>Symbol Name</option>
                                @foreach ($symbolArr as $item)
                                  <option value="{{$item}}" {{$item == $table3 ? "selected" : ""}}>{{$item}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="mx-1">
                              <select name="atmRange3" class="form-select" id="atmRange3">
                                <option value="" disabled="" selected>Strike</option>
                                @for ($i = -3; $i <= 3; $i++)
                                  @if ($i == 0)
                                    <option value="ATM" {{$Atmtype3 == "ATM" ? "selected" : ""}} >ATM</option>
                                  @else
                                    <option value="ATM{{$i > 0 ? '+'.$i : $i}}" {{$Atmtype3 == "ATM".($i > 0 ? '+'.$i : $i) ? "selected" : ""}} >ATM {{$i > 0 ? '+'.$i : $i}}</option>
                                  @endif
                                @endfor
                            </select>
                           </div>
                           <div class="mx-1">
                              <select name="timeframe3" class="form-select" id="timeframe3">
                                <option value="" disabled="" selected>Time Frame</option>
                                <option value="1" {{$timeFrame3 == 1 ? 'selected' : ''}}>1</option>
                                <option value="3" {{$timeFrame3 == 3 ? 'selected' : ''}}>3</option>
                                <option value="5" {{$timeFrame3 == 5 ? 'selected' : ''}}>5</option>
                                </select>
                            </div>
                            <div class="mx-1">
                              <button class="btn btn-sm btn--base w-100 py-2" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                            </div>
                            <div class="mx-1">
                              <a href="{{url('/user/option-analysis')}}" class="btn btn-sm btn--base w-100 py-2" ><i class="las la-filter"></i> @lang('Refresh')</a>
                            </div>
                          </form>
                        </div>
                    </div>
                    <div class="card-body chart2">
                        <div id="apex-analysis-chart4" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
            {{-- Third Graph End --}}
            
            {{-- Fourth Graph Start --}}
            @php
              $atmData4 = [];
                foreach($data4 as $vvl){
                    if(isset($vvl->atm) && $vvl->atm==$Atmtype4){
                        $atmData4[] = $vvl;
                    }
                }
            @endphp
            @php $i=1; @endphp
            @forelse($atmData4 as $val)
              @php
                  $arrData4 = json_decode($val->data,true);    
                  $CE4 = array_slice($arrData4['CE'],-1);
                  $PE4 = array_slice($arrData4['PE'],-1);
                  $Date4 = array_slice($arrData4['Date'],-40);
                  $time4 = array_slice($arrData4['time'],-40);
                  $vwap_CE_signal4 = array_slice($arrData4['vwap_CE_signal'],-40);
                  $vwap_PE_signal4 = array_slice($arrData4['vwap_PE_signal'],-40);
                  $close_CE4 = array_slice($arrData4['close_CE'],-40);
                  $close_PE4 = array_slice($arrData4['close_PE'],-40);
                  $OI_CE4 = array_slice([1493500,1480500,1539650,1623700,1667550,1693700,1722450,1718400,1741650,1749000,1756600,1748350,1766850,1777050,1797450,1840500,1908550,1959650,1998650,2026550,2018200,2024050,2008450,2008300,2011900,2005400,2025950,1996100,2010800,2052250,2084850,2102350,2205950,2209950,2271500,2399400,2592850,2803900,2928600,3070650],-40);
                  $OI_PE4 = array_slice([2266450,2293150,2329300,2399700,2462350,2473700,2500550,2508800,2513400,2466700,2447550,2439100,2427600,2415600,2429100,2389700,2407800,2362400,2413650,2450800,2593600,2632300,2634600,2660050,2718550,2781050,2765050,2823400,2794550,2813550,2803150,2826700,2846400,2907450,2913550,2953050,3024900,3292200,3468450,3586400,3679100],-40);
              @endphp
            @empty
            @endforelse 
            @php
            $time4 = array_map(function ($k , $y) use($Date4){
                return date("d-M-Y",($Date4[$k]/1000)).', '.date("g:i a", strtotime($y));
            },array_keys($Date4) , $time4);

            $ceArray4 = array();
            $newArr14 = [];

            foreach($time4 as $i=>$y){
              if(!in_array($vwap_CE_signal4[$i],$ceArray4)){
                $ceArray4 = [];
                array_push($ceArray4,$vwap_CE_signal4[$i]);
                $newArr14[] = [
                    'time'=>$y,
                    'price'=>$OI_CE4[$i],
                    'text'=>$vwap_CE_signal4[$i],
                ];
              }
            }

            $PeArray4 = array();
            $newArr24 = [];

            foreach($time4 as $i=>$y){
              if(!in_array($vwap_PE_signal4[$i],$PeArray4)){
                $PeArray4 = [];
                array_push($PeArray4,$vwap_PE_signal4[$i]);
                $newArr24[] = [
                    'time'=>$y,
                    'price'=>$OI_PE4[$i],
                    'text'=>$vwap_PE_signal4[$i],
                ];
              }
            }
            $mergedArray4 = array_merge($newArr14, $newArr24);
            @endphp
        <div class="col-lg-12 mb-3">
            <div class="custom--card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                   <div>
                      <h5 class="card-title mb-0"  data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right" title="Open Interest CE/PE Crossovers occur when CE surpasses or falls below PE's open interest. It signals shifting sentiment.
                      Interpretation: Bullish: CE crossing above PE indicates bullish sentiment. Bearish: PE surpassing CE suggests bearish sentiment. Confirm with volume, price trends, and market sentiment.
                      A valid trading signal occurs when Call Options (CE) indicate bullish sentiment while Put Options (PE) suggest bearish sentiment. It is opportune to enter a trade when these signals align in this manner.">@lang('Open Interest CE/PE Crossovers') </h5>
                      <small class="text-warning" >Y- Open Interest, X - Time</small>
                   </div>
                    <div class="filter-box d-flex">
                      <form method="GET" class="d-flex align-items-center flex-wrap filter_dropdown">
                        <div class="mx-1">
                          <select name="symbol4" class="form-select" id="symbol4">
                            <option value="" disabled="" selected>Symbol Name</option>
                            @foreach ($symbolArr as $item)
                              <option value="{{$item}}" {{$item == $table4 ? "selected" : ""}}>{{$item}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="mx-1">
                          <select name="atmRange4" class="form-select" id="atmRange4">
                            <option value="" disabled="" selected>Strike</option>
                            @for ($i = -3; $i <= 3; $i++)
                              @if ($i == 0)
                                <option value="ATM" {{$Atmtype4 == "ATM" ? "selected" : ""}} >ATM</option>
                              @else
                                <option value="ATM{{$i > 0 ? '+'.$i : $i}}" {{$Atmtype4 == "ATM".($i > 0 ? '+'.$i : $i) ? "selected" : ""}} >ATM {{$i > 0 ? '+'.$i : $i}}</option>
                              @endif
                            @endfor
                        </select>
                       </div>
                       <div class="mx-1">
                          <select name="timeframe4" class="form-select" id="timeframe4">
                            <option value="" disabled="" selected>Time Frame</option>
                            <option value="1" {{$timeFrame4 == 1 ? 'selected' : ''}}>1</option>
                            <option value="3" {{$timeFrame4 == 3 ? 'selected' : ''}}>3</option>
                            <option value="5" {{$timeFrame4 == 5 ? 'selected' : ''}}>5</option>
                            </select>
                        </div>
                        <div class="mx-1">
                          <button class="btn btn-sm btn--base w-100 py-2" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                        </div>
                        <div class="mx-1">
                          <a href="{{url('/user/option-analysis')}}" class="btn btn-sm btn--base w-100 py-2" ><i class="las la-filter"></i> @lang('Refresh')</a>
                        </div>
                      </form>
                    </div>
                </div>
                <div class="card-body chart2">
                    <div id="apex-analysis-chart5" style="width: 100%;"></div>
                </div>
            </div>
        </div>
         {{-- Fourth Graph End --}}

         {{-- Fifth Graph Start --}}
              @php
              $atmData5 = [];
                foreach($data5 as $vvl){
                    if(isset($vvl->atm) && $vvl->atm==$Atmtype5){
                        $atmData5[] = $vvl;
                    }
                }
            @endphp
            @php $i=1; @endphp
            @forelse($atmData5 as $val)
              @php
                  $arrData5 = json_decode($val->data,true);    
                  $CE5 = array_slice($arrData5['CE'],-1);
                  $PE5 = array_slice($arrData5['PE'],-1);
                  $Date5 = array_slice($arrData5['Date'],-40);
                  $time5 = array_slice($arrData5['time'],-40);
                  $vwap_CE_signal5 = array_slice($arrData5['vwap_CE_signal'],-40);
                  $vwap_PE_signal5 = array_slice($arrData5['vwap_PE_signal'],-40);
                  $close_CE5 = array_slice($arrData5['close_CE'],-40);
                  $close_PE5 = array_slice($arrData5['close_PE'],-40);
                  $CE_NETCHANGE_5 = array_slice([22450, 28900, 21500, -5000, -1700, 5250, 17300, 18750, 1300, -3450, 8550, 0, -4950, 16100, 2700, -14650, 7300, 27650, 13900, 5500, -1650, -11500, -950, 350, 4450, -900, -11700, -9050, 9700, -3750, 11150, 9750, -550, -23750, -6900, -4250, -32750, -3500, 16100, 11050, -1150, 17850, 21950, -1800, 8650, 43700, 3250, 2250, 6150, -9550, -500, 1800, 4950, -6450, -1700, 2350, -2200, 10300, 6600, 7700, 3100],-40);
                  $PE_NETCHANGE_5 = array_slice([11600, 9400, 3850, 29650, 58000, 16600, 25250, 14450, -7550, 44200, 58200, -3200, 43900, 27950, 33350, 105300, 112950, 86650, 18500, 119950, 92700, 17000, 48600, 10600, 58150, 48550, 23600, 10500, 4300, 28900, 62250, 18050, 4700, -75350, -6550, 48150, 39600, 93000, 24000, 34350, 79600, -6850, 12500, 56600, 26700],-40);;
              @endphp
            @empty
            @endforelse 
            @php
            $time5 = array_map(function ($y) {
                return date("g:i a", strtotime($y));
            },$time5);

            $ceArray5 = array();
            $newArr15 = [];

            foreach($time5 as $i=>$y){
              if(!in_array($vwap_CE_signal5[$i],$ceArray5)){
                $ceArray5 = [];
                array_push($ceArray5,$vwap_CE_signal5[$i]);
                $newArr15[] = [
                    'time'=>$y,
                    'price'=>$CE_NETCHANGE_5[$i],
                    'text'=>$vwap_CE_signal5[$i],
                    'color'=>'#00FF00'
                ];
              }
            }

            $PeArray5 = array();
            $newArr25 = [];

            foreach($time5 as $i=>$y){
              if(!in_array($vwap_PE_signal5[$i],$PeArray5)){
                $PeArray5 = [];
                array_push($PeArray5,$vwap_PE_signal5[$i]);
                $newArr25[] = [
                    'time'=>$y,
                    'price'=>$PE_NETCHANGE_5[$i],
                    'text'=>$vwap_PE_signal5[$i],
                    'color'=>'#FF0000'
                ];
              }
            }
            $mergedArray5 = array_merge($newArr15, $newArr25);
          @endphp
        <div class="col-lg-12 mb-3">
            <div class="custom--card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                    
                      <h5 class="card-title mb-0"  data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right" title="Open interest reflects active options contracts. The net change is the current minus previous open interest. Bullish: CE's net increase over PE indicates optimism for asset's rise. Bearish: PE's significant increase over CE signals pessimism for asset's decline.">@lang('Open Interest CE/PE Net Change') </h5>
                      <small class="text-warning">Y- Open Interest, X - Time</small>
                    </div>
                    <div class="filter-box d-flex">
                      <form method="GET" class="d-flex align-items-center flex-wrap filter_dropdown">
                        <div class="mx-1">
                          <select name="symbol5" class="form-select" id="symbol5">
                            <option value="" disabled="" selected>Symbol Name</option>
                            @foreach ($symbolArr as $item)
                              <option value="{{$item}}" {{$item == $table5 ? "selected" : ""}}>{{$item}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="mx-1">
                          <select name="atmRange5" class="form-select" id="atmRange5">
                            <option value="" disabled="" selected>Strike</option>
                            @for ($i = -3; $i <= 3; $i++)
                              @if ($i == 0)
                                <option value="ATM" {{$Atmtype5 == "ATM" ? "selected" : ""}} >ATM</option>
                              @else
                                <option value="ATM{{$i > 0 ? '+'.$i : $i}}" {{$Atmtype5 == "ATM".($i > 0 ? '+'.$i : $i) ? "selected" : ""}} >ATM {{$i > 0 ? '+'.$i : $i}}</option>
                              @endif
                            @endfor
                        </select>
                        </div>
                        <div class="mx-1">
                          <select name="timeframe5" class="form-select" id="timeframe5">
                            <option value="" disabled="" selected>Time Frame</option>
                            <option value="1" {{$timeFrame5 == 1 ? 'selected' : ''}}>1</option>
                            <option value="3" {{$timeFrame5 == 3 ? 'selected' : ''}}>3</option>
                            <option value="5" {{$timeFrame5 == 5 ? 'selected' : ''}}>5</option>
                            </select>
                        </div>
                        <div class="mx-1">
                          <button class="btn btn-sm btn--base w-100 py-2" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                        </div>
                        <div class="mx-1">
                          <a href="{{url('/user/option-analysis')}}" class="btn btn-sm btn--base w-100 py-2" ><i class="las la-filter"></i> @lang('Refresh')</a>
                        </div>
                      </form>
                    </div>
                </div>
                <div class="card-body chart2">
                    <div id="apex-analysis-chart6" style="width: 100%;"></div>
                </div>
            </div>
        </div>
          {{-- Fifth Graph End --}}
        </div>
    </div>
</section>

@endsection
@push('script')

<script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>

<script>
var options = {
    
    series: [{
    name: 'Net Profit',
    data: [44, 55, 57, 56, 61, 58, 63, 60, 66,85,45,25]
  }, {
    name: 'Revenue',
    data: [76, 85, 101, 98, 87, 105, 91, 114,58,98,15, 94]
  }, {
    name: 'Free Cash Flow',
    data: [35, 41, 36, 26, 45, 48, 52, 53,36,56,92, 41]
  }],
    chart: {
    type: 'bar',
    foreColor: '#e4e4e4',
    height: 400,
    toolbar: {
        show: false,
       
    }
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '40%',
      endingShape: 'rounded'
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    show: true,
    width: 2,
    colors: ['transparent']
  },
  xaxis: {
    type: "category",
    categories: ['1','2','3','4'],
  },
  yaxis: {
    title: {
      text: '$ (thousands)'
    }
  },
  fill: {
    opacity: 1
  },
  tooltip: {
    enabled: true,
    theme: 'dark',
          
    y: {
      formatter: function (val) {
        return "$ " + val + " thousands"
      }
    }
  }
  };

  var chart = new ApexCharts(document.querySelector("#apex-analysis-chart"), options);
  chart.render();

//   apex charts 2

var options = {
          series: [{
            name: "Session Duration",
            data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10,14]
          },
          {
            name: "Page Views",
            data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35,45]
          },
          {
            name: 'Total Visits',
            data: [87, 57, 74, 99, 75, 38, 62, 47, 82, 56, 45, 47, 65]
          }
        ],
          chart: {
          height: 400,
          foreColor: '#e4e4e4',
          type: 'line',
          zoom: {
            enabled: false
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: [5, 7, 5],
          curve: 'straight',
          dashArray: [0, 8, 5]
        },
       
        legend: {
          tooltipHoverFormatter: function(val, opts) {
            return val + ' - <strong>' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + '</strong>'
          }
        },
        markers: {
          size: 0,
          hover: {
            sizeOffset: 6
          }
        },
        xaxis: {
          categories: ['01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan', '09 Jan',
            '10 Jan', '11 Jan', '12 Jan','13 Jan'
          ],
        },
        tooltip: {
            enabled: true,
    theme: 'dark',
          y: [
            {
              title: {
                formatter: function (val) {
                  return val + " (mins)"
                }
              }
            },
            {
              title: {
                formatter: function (val) {
                  return val + " per session"
                }
              }
            },
            {
              title: {
                formatter: function (val) {
                  return val;
                }
              }
            }
          ]
        },
    
        };

        var chart = new ApexCharts(document.querySelector("#apex-analysis-chart2"), options);
        chart.render();
</script>
{{-- ce-red.pe-green --}}
@php
    $data2 = [];
    foreach($mergedArray2 as $key => $value){
      if($value['text'] == "Bearish"){
        $background = "#FF0000";
        $color = "#fff";
      }else if($value['text'] == "Bullish"){
        $background = "#00FF00";
        $color = "#000";
      }else{
        $background = "yellow";
        $color = "#000";
      }
      $data2[] = [
        "x"=>$value['time'],
        "y"=>$value['price'],
        "marker"=>[
          'size'=>6,
          "fillColor"=> "#FFF",
          "strokeColor"=> "transparent",
          "radius"=> 2
        ],
        "label"=> [
            "borderColor"=> $background,
            "offsetY"=> 0,
            "style"=> [
              "color"=> $color,
              "background"=> $background
            ],
            "text"=> $value['text']
        ]
      ];
    }
    
@endphp


@php
    $data3 = [];
    foreach($mergedArray3 as $key => $value){
      if($value['text'] == "Bearish"){
        $background = "#FF0000";
        $color = "#fff";
      }else if($value['text'] == "Bullish"){
        $background = "#00FF00";
        $color = "#000";
      }else{
        $background = "yellow";
        $color = "#000";
      }
      $data3[] = [
        "x"=>$value['time'],
        "y"=>$value['price'],
        "marker"=>[
          'size'=>6,
          "fillColor"=> "#FFF",
          "strokeColor"=> "transparent",
          "radius"=> 2
        ],
        "label"=> [
            "borderColor"=> $background,
            "offsetY"=> 0,
            "style"=> [
              "color"=> $color,
              "background"=> $background
            ],
            "text"=> $value['text']
        ]
      ];
    }
@endphp

@php
    $data4 = [];
    foreach($mergedArray4 as $key => $value){
      if($value['text'] == "Bearish"){
        $background = "#FF0000";
        $color = "#fff";
      }else if($value['text'] == "Bullish"){
        $background = "#00FF00";
        $color = "#000";
      }else{
        $background = "yellow";
        $color = "#000";
      }
      $data4[] = [
        "x"=>$value['time'],
        "y"=>$value['price'],
        "marker"=>[
          'size'=>6,
          "fillColor"=> "#FFF",
          "strokeColor"=> "transparent",
          "radius"=> 2
        ],
        "label"=> [
            "borderColor"=> $background,
            "offsetY"=> 0,
            "style"=> [
              "color"=> $color,
              "background"=> $background
            ],
            "text"=> $value['text']
        ]
      ];
    }
@endphp


@php
    $data5 = [];
    foreach($mergedArray5 as $key => $value){
      if($value['color'] == "#00FF00"){ // CE
        $data5[] = [
          "x"=>$value['time'],
          "borderColor"=>$value['color'],
          "label"=>[
            "borderColor"=>"transparent",
            "style"=>[
              "color"=>'#000',
              "background"=>$value['color'],
            ],
            "orientation"=>"horizontal",
            "text"=>$value['text']
          ]
        ];

        
      }else if($value['color'] == "#FF0000"){ // PE
        $data5[] = [
          "x"=>$value['time'],
          "borderColor"=>$value['color'],
          "label"=>[
            "borderColor"=>"transparent",
            "style"=>[
              "color"=>'#fff',
              "background"=>$value['color'],
            ],
            "text"=>$value['text']
          ]
        ];

      }else{
        $background = "yellow";
        $color = "#000";
      }      

    }

    
@endphp

{{-- Apex Chart 2 --}}
<script>
  var series =
  {
    "monthDataSeries1": {
      "prices": <?= json_encode($close_CE2) ?>,
      "dates": <?= json_encode($time2); ?>
    },
    "monthDataSeries2": {
      "prices": <?= json_encode($close_PE2) ?>,
      "dates": <?= json_encode($time2); ?>
    }
  }
  var options = {
    annotations: {
      points: {!!json_encode($data2)!!}
    },
    chart: {
      height: 400,
      foreColor: '#E4E4E4',
      type: "line",
      id: "areachart-2",
      zoom: {
        enabled: false
      },
      toolbar: {
        show: false, 
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "straight",
      width:2
    },
    colors: ['#00FF00','#FF0000'],
    series: [
      {
        name: {!! json_encode($CE2[0]) !!},
        data: series.monthDataSeries1.prices,
      },
      {
        name: {!! json_encode($PE2[0]) !!},
        data: series.monthDataSeries2.prices
      }
    ],
    tooltip: {
      enabled: true,
      theme: 'dark',
    },
    labels: series.monthDataSeries1.dates,
    xaxis: {
        type: "category",
        categories: <?= json_encode($time2); ?>,
    },
  };
  var chart = new ApexCharts(document.querySelector("#apex-analysis-chart3"), options);
  chart.render();
</script>

<script>
  var series =
  {
    "monthDataSeries1": {
      "prices": <?= json_encode($close_CE3) ?>,
      "dates": <?= json_encode($time3); ?>
    },
    "monthDataSeries2": {
      "prices": <?= json_encode($close_PE3) ?>,
      "dates": <?= json_encode($time3); ?>
    }
  }
  var options = {
    annotations: {
      points: {!!json_encode($data3)!!}
    },
    chart: {
      height: 400,
      foreColor: '#E4E4E4',
      type: "line",
      id: "areachart-2",
      zoom: {
        enabled: false
      },
      toolbar: {
        show: false, 
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "straight",
      width:2
    },
    colors: ['#00FF00','#FF0000'],
    series: [
      {
        name: {!! json_encode($CE3[0]) !!},
        data: series.monthDataSeries1.prices,
      },
      {
        name: {!! json_encode($PE3[0]) !!},
        data: series.monthDataSeries2.prices
      }
    ],
    tooltip: {
      enabled: true,
      theme: 'dark',
    },
    labels: series.monthDataSeries1.dates,
    xaxis: {
      type: "category",
      categories: <?= json_encode($time3); ?>,
    },
  };
  var chart = new ApexCharts(document.querySelector("#apex-analysis-chart4"), options);
  chart.render();
</script>



<script>
  var series =
  {
    "monthDataSeries1": {
      "prices": <?= json_encode($OI_CE4) ?>,
      "dates": <?= json_encode($time4); ?>
    },
    "monthDataSeries2": {
      "prices": <?= json_encode($OI_PE4) ?>,
      "dates": <?= json_encode($time4); ?>
    }
  }
  var options = {
    annotations: {
      points: {!!json_encode($data4)!!}
    },
    chart: {
      height: 400,
      foreColor: '#E4E4E4',
      type: "line",
      id: "areachart-2",
      zoom: {
        enabled: false
      },
      toolbar: {
        show: false, 
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "straight",
      width:2
    },
    colors: ['#00FF00','#FF0000'],
    series: [
      {
        name: {!! json_encode($CE4[0]) !!},
        data: series.monthDataSeries1.prices,
      },
      {
        name: {!! json_encode($PE4[0]) !!},
        data: series.monthDataSeries2.prices
      }
    ],
    tooltip: {
      enabled: true,
      theme: 'dark',
    },
    labels: series.monthDataSeries1.dates,
    xaxis: {
      type: "category",
      categories: <?= json_encode($time4); ?>,
    },
  };
  var chart = new ApexCharts(document.querySelector("#apex-analysis-chart5"), options);
  chart.render();
</script>


<script>
  var series =
  {
    "monthDataSeries1": {
      "prices": <?= json_encode($CE_NETCHANGE_5) ?>,
      "dates": <?= json_encode($time5); ?>
    },
    "monthDataSeries2": {
      "prices": <?= json_encode($PE_NETCHANGE_5) ?>,
      "dates": <?= json_encode($time5); ?>
    }
  }
  var options = {
    annotations: {
      xaxis: {!!json_encode($data5)!!}
    },
    chart: {
      height: 400,
      foreColor: '#E4E4E4',
      type: "bar",
      id: "areachart-2",
      zoom: {
        enabled: false
      },
      toolbar: {
        show: false, 
      },
    },plotOptions: {
      bar: {
        columnWidth: '50%',
      },
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "straight",
      width:2
    },
    colors: ['#00FF00','#FF0000'],
    series: [
      {
        name: {!! json_encode($CE5[0]) !!},
        data: series.monthDataSeries1.prices,
      },
      {
        name: {!! json_encode($PE5[0]) !!},
        data: series.monthDataSeries2.prices
      }
    ],
    tooltip: {
      enabled: true,
      theme: 'dark',
    },
    labels: series.monthDataSeries1.dates,
    xaxis: {
      type: "category",
      categories: <?= json_encode($time5); ?>,
    },
  };
  var chart = new ApexCharts(document.querySelector("#apex-analysis-chart6"), options);
  chart.render();
</script>



@endpush


