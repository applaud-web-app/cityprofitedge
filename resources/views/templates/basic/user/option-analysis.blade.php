@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
<section class="pt-100 pb-100">
    <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Stock Name')</th>
                                        <th>@lang('Expiry')</th>
                                        <th>@lang('Strick Price')</th>
                                        <th>@lang('Option Type')</th>
                                        <th>@lang('Delta')</th>
                                        <th>@lang('Damma')</th>
                                        <th>@lang('Implied Volatility')</th>
                                        <th>@lang('Theta')</th>
                                        <th>@lang('Trade Volume')</th>
                                        <th>@lang('Vega')</th>
                                    </tr>
                                </thead>
                                <tbody id="greekData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title">@lang('Option Analysis 1')</h5>
                        <div class="transparent-form">
                        
                            <select name="Symbol" class="form--control" id="symbol">
                                <option value="" disabled="" selected>--Select Symbol--</option>
                                <option value="1">Symbol 1</option>
                                <option value="2">Symbol 2</option>
                                <option value="3">Symbol 3</option>
                                <option value="4">Symbol 4</option>
                                <option value="5">Symbol 5</option>
                                <option value="6">Symbol 6</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="apex-analysis-chart" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title">@lang('Option Analysis 2')</h5>
                        <div class="transparent-form">
                        
                            <select name="Symbol" class="form--control" id="symbol">
                                <option value="" disabled="" selected>--Select--</option>
                                <option value="1">Symbol 1</option>
                                <option value="2">Symbol 2</option>
                                <option value="3">Symbol 3</option>
                                <option value="4">Symbol 4</option>
                                <option value="5">Symbol 5</option>
                                <option value="6">Symbol 6</option>
                            </select>
                        </div>

                    </div>
                    <div class="card-body">
                        <div id="apex-analysis-chart2" style="width: 100%;"></div>
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
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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

<script>
  function fetchGreekData(){
      $.get('{{route("fetch-option-greek-data")}}',function(response){
          $symbol = "TCS";
          $empDate = "25JAN2024";

          if(response['status'] == true){
            // console.log(response['data']);
            records = response['data'];
            if(records.length > 0){
              var str = "";
              for (var i in records) {
                  str += `<tr>
                      <td class="">${records[i].name}</td>
                      <td class="">${records[i].expiry}</td>
                      <td class=""> ${records[i].strikePrice}</td>
                      <td class=""> ${records[i].optionType}</td>
                      <td class="">${records[i].delta}</td>
                      <td class="">${records[i].gamma}</td>
                      <td class="">${records[i].impliedVolatility}</td>
                      <td class="">${records[i].theta}
                        <td class="">${records[i].tradeVolume}
                          <td class="">${records[i].vega}</td>`;
              }
              $("#greekData").html(str);
            }
          }else{
            $("#greekData").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
          }
          // if(data['status'] === true){
          // data = data['data'];
          // if(data.length > 0){
          //     var str = "";
          //     for (var i in data) {
          //         if(i>4){
          //             break;
          //         } 
          //         str += `<tr>
          //             <td class="text-start">${data[i].tradingSymbol}</td>
          //             <td class="text-start">${data[i].ltp}</td>
          //             <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
          //             <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
          //             <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
          //             <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
          //     }
          //     $("#longBuild").html(str);
          // }else{
          //     $("#longBuild").html('');
          // }
          // }else{
          //     
          // }
      });
  }
  fetchGreekData();
</script>
@endpush

