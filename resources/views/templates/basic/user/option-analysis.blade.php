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
  }
  .chart2 .apexcharts-legend-series .apexcharts-legend-marker[rel="2"]{
    background: transparent !important;
    border: 2px solid rgb(255, 0, 0) !important;
  }
</style>
@endpush
<section class="pt-100 pb-100">
    <div class="container-fluid">
      {{-- For First Chart --}}
      @php
        $atmData = [];
        foreach($data as $vvl){
            if(isset($vvl->atm) && $vvl->atm==$Atmtype){
                $atmData[] = $vvl;
            }
        }
      @endphp
      @php $i=1; @endphp
      @forelse($atmData as $val)
        @php
            $arrData = json_decode($val->data,true);    
            $CE = array_slice($arrData['CE'],-1);
            $PE = array_slice($arrData['PE'],-1);
            // $Date = array_slice($arrData['Date'],-20);
            $time = array_slice($arrData['time'],-20);
            // $BUY_Action = array_slice($arrData['BUY_Action'],-5);
            // $SELL_Action = array_slice($arrData['SELL_Action'],-5);
            // $Strategy_name = array_slice($arrData['Strategy_name'],-5);
            // $vwap_CE_signal = array_slice($arrData['vwap_CE_signal'],-5);
            // $vwap_PE_signal = array_slice($arrData['vwap_PE_signal'],-5);
            $CE_consolidated = array_slice($arrData['CE_consolidated'],-20);
            $PE_consolidated = array_slice($arrData['PE_consolidated'],-20);
            $close_CE = array_slice($arrData['close_CE'],-20);
            $close_PE = array_slice($arrData['close_PE'],-20);
        @endphp
      @empty
      @endforelse 
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title">@lang('Option Analysis 1')</h5>
                        <div class="filter-box d-flex">
                          <form method="GET" class="d-flex align-items-center flex-wrap filter_dropdown">
                            <div class="mx-1">
                              <select name="symbol" class="form-select" id="symbol">
                                <option value="" disabled="" selected>Symbol Name</option>
                                @foreach ($symbolArr as $item)
                                  <option value="{{$item}}" {{$item == $table ? "selected" : ""}}>{{$item}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="mx-1">
                              <select name="atmRange" class="form-select" id="atmRange">
                                <option value="" disabled="" selected>Strike</option>
                                @for ($i = -3; $i <= 3; $i++)
                                  @if ($i == 0)
                                    <option value="ATM" {{$Atmtype == "ATM" ? "selected" : ""}} >ATM</option>
                                  @else
                                    <option value="ATM{{$i}}" {{$Atmtype == "ATM$i" ? "selected" : ""}} >ATM {{$i}}</option>
                                  @endif
                                @endfor
                            </select>
                           </div>
                           <div class="mx-1">
                              <select name="timeframe" class="form-select" id="timeframe">
                                <option value="" disabled="" selected>Time Frame</option>
                                <option value="1" {{$timeFrame == 1 ? 'selected' : ''}}>1</option>
                                <option value="3" {{$timeFrame == 3 ? 'selected' : ''}}>3</option>
                                <option value="5" {{$timeFrame == 5 ? 'selected' : ''}}>5</option>
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

            {{-- For Graph 2 --}}
            @php
                $atmData = [];
                foreach($data as $vvl){
                    if(isset($vvl->atm) && $vvl->atm==$Atmtype){
                        $atmData[] = $vvl;
                    }
                }
            @endphp
            @php $i=1; @endphp
            @forelse($atmData as $val)
              @php
                  $arrData = json_decode($val->data,true);    
                  $CE = array_slice($arrData['CE'],-1);
                  $PE = array_slice($arrData['PE'],-1);
                  $Date = array_slice($arrData['Date'],-20);
                  $time = array_slice($arrData['time'],-40);
                  // $BUY_Action = array_slice($arrData['BUY_Action'],-5);
                  // $SELL_Action = array_slice($arrData['SELL_Action'],-5);
                  // $Strategy_name = array_slice($arrData['Strategy_name'],-5);
                  // $vwap_CE_signal = array_slice($arrData['vwap_CE_signal'],-5);
                  // $vwap_PE_signal = array_slice($arrData['vwap_PE_signal'],-5);
                  $CE_consolidated = array_slice($arrData['CE_consolidated'],-40);
                  $PE_consolidated = array_slice($arrData['PE_consolidated'],-40);
                  $close_CE = array_slice($arrData['close_CE'],-40);
                  $close_PE = array_slice($arrData['close_PE'],-40);
              @endphp
            @empty
            @endforelse  
            @php
              $time = array_map(function ($k , $y) use($Date) {
                  return $Date[$k]." ".date("g:i a", strtotime($y));
              },array_keys($time), $time);

              $ceArray = array();
              $newArr1 = [];

              foreach($time as $i=>$y){
                if(!in_array($CE_consolidated[$i],$ceArray)){
                  $ceArray = [];
                  array_push($ceArray,$CE_consolidated[$i]);
                  $newArr1[] = [
                      'time'=>$y,
                      'price'=>$close_CE[$i],
                      'text'=>$CE_consolidated[$i],
                  ];
                }
              }

              $PeArray = array();
              $newArr2 = [];

              foreach($time as $i=>$y){
                if(!in_array($PE_consolidated[$i],$PeArray)){
                  $PeArray = [];
                  array_push($PeArray,$PE_consolidated[$i]);
                  $newArr2[] = [
                      'time'=>$y,
                      'price'=>$close_PE[$i],
                      'text'=>$PE_consolidated[$i],
                  ];
                }
              }
              $mergedArray = array_merge($newArr1, $newArr2);
            @endphp
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title">@lang('Option Analysis - Open Interest CE/PE Signals')</h5>
                        <div class="filter-box d-flex">
                          <form method="GET" class="d-flex align-items-center flex-wrap filter_dropdown">
                            <div class="mx-1">
                              <select name="symbol" class="form-select" id="symbol">
                                <option value="" disabled="" selected>Symbol Name</option>
                                @foreach ($symbolArr as $item)
                                  <option value="{{$item}}" {{$item == $table ? "selected" : ""}}>{{$item}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="mx-1">
                              <select name="atmRange" class="form-select" id="atmRange">
                                <option value="" disabled="" selected>Strike</option>
                                @for ($i = -3; $i <= 3; $i++)
                                  @if ($i == 0)
                                    <option value="ATM" {{$Atmtype == "ATM" ? "selected" : ""}} >ATM</option>
                                  @else
                                    <option value="ATM{{$i}}" {{$Atmtype == "ATM$i" ? "selected" : ""}} >ATM {{$i}}</option>
                                  @endif
                                @endfor
                            </select>
                           </div>
                           <div class="mx-1">
                              <select name="timeframe" class="form-select" id="timeframe">
                                <option value="" disabled="" selected>Time Frame</option>
                                <option value="1" {{$timeFrame == 1 ? 'selected' : ''}}>1</option>
                                <option value="3" {{$timeFrame == 3 ? 'selected' : ''}}>3</option>
                                <option value="5" {{$timeFrame == 5 ? 'selected' : ''}}>5</option>
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
    $data = [];
    foreach($mergedArray as $key => $value){
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
      $data[] = [
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

{{-- Apex Chart 2 --}}
<script>
  var series =
  {
    "monthDataSeries1": {
      "prices": <?= json_encode($close_CE) ?>,
      "dates": <?= json_encode($time); ?>
    },
    "monthDataSeries2": {
      "prices": <?= json_encode($close_PE) ?>,
      "dates": <?= json_encode($time); ?>
    }
  }
  var options = {
    annotations: {
      points: {!!json_encode($data)!!}
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
    title : {
      text : "Y- ClosePrice, X - Time",
      align : "right"
    },
    colors: ['#00FF00','#FF0000'],
    series: [
      {
        name: {!! json_encode($CE[0]) !!},
        data: series.monthDataSeries1.prices,
      },
      {
        name: {!! json_encode($PE[0]) !!},
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
        categories: <?= json_encode($time); ?>,
    },
  };
  var chart = new ApexCharts(document.querySelector("#apex-analysis-chart3"), options);
  chart.render();
</script>
@endpush

