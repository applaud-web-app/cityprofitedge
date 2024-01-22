@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    .ch-container{
        width: 100%;
    }
</style>
@endpush

<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="#" class="transparent-form mb-3" autocomplete="off">
            <div class="row">
              
                {{-- <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Segments')</label>
                    <select name="segments" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="1" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="2" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div>
             --}}
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Symbol')</label>
                    <select name="symbol" class="form--control">
                        <option value="" selected disabled>@lang('Select an option')</option>
                        @isset($stock)
                        @php $symbol = []; @endphp
                            @foreach ($stock as $item)
                                @if (isset($item['stock_name']))
                                     @if (in_array($item['stock_name'],$symbol))

                                     @else
                                        @php array_push($symbol,$item['stock_name']); @endphp
                                     @endif
                                @endif
                            @endforeach
                            @foreach ($symbol as $item)
                                <option value="{{$item}}" @selected(request()->type == '+')>@lang($item)</option>
                            @endforeach
                        @endisset 
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Dates')</label>
                    <input type="text" name="buyDate" id="dates_range" value="" class="form--control" placeholder="Choose Date">
                </div>
                {{-- <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Tags')</label>
                    <select name="tags" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="1" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="2" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div> --}}
                <div class="col-lg-2 col-md-2 col-6 form-group mt-auto">
                    <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group mt-auto">
                    <a href="{{url('/user/trade-book')}}" class="btn btn--base w-100"><i class="las la-redo-alt"></i> @lang('Refresh')</a>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-12">
                <div id="ex-wind" class="mb-3" style="width: 100%;"></div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>Stock Name</th>
                                        <th>Buy Date</th>
                                        <th>Buy Price</th>
                                        <th>Quantity</th>
                                        <th>Sold Date</th>
                                        <th>Sell Price</th>
                                        <th>PNL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($Ledger)
                                        @if (count($Ledger))
                                            @foreach ($Ledger as $item)
                                            <tr>
                                                <td>{{$item['stock_name']}}</td>
                                                <td>{{showDate($item['buy_date'])}}</td>
                                                <td>{{$item['buy_price']}}</td>
                                                <td>{{$item['quantity']}}</td>
                                                <td> @if (isset($item['sold_date']))
                                                    {{showDate($item['sold_date'])}}
                                                @else
                                                    {{"---"}}
                                                @endif</td>
                                                <td>  @if (isset($item['sell_price']))
                                                    {{$item['sell_price']}}
                                                @else
                                                    {{"---"}}
                                                @endif</td>
                                                <td>{{$item['profit_loss']}}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%"><span class="text--base">NO DATA</span></td> 
                                            </tr>
                                        @endif
                                    @endisset    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 justify-content-center d-flex">
           {{-- pagination links --}}
        </div>
    </div>
</section>
@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://d3js.org/d3.v7.min.js"></script>
<!-- v6 is also supported -->
<script src="https://d3js.org/d3.v6.min.js"></script>
<script src="https://unpkg.com/cal-heatmap/dist/cal-heatmap.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/cal-heatmap/dist/cal-heatmap.css">
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/cal-heatmap/dist/plugins/Tooltip.min.js"></script>
<script src="https://unpkg.com/cal-heatmap/dist/plugins/Legend.min.js"></script>
<script src="https://unpkg.com/cal-heatmap/dist/plugins/CalendarLabel.min.js"></script>


<script>
    $("#dates_range").daterangepicker({
        autoUpdateInput: false,
        minYear: 1901,
        showDropdowns: true,
    }).on("apply.daterangepicker", function (e, picker) {
        picker.element.val(picker.startDate.format('YYYY-MM-DD') + "/" + picker.endDate.format('YYYY-MM-DD'));
    });
</script>

<script type="text/javascript">
    const cal = new CalHeatmap();
  cal.paint(
    {
      data: {
        source: '../fixtures/seattle-weather.csv',
        type: 'csv',
        x: 'date',
        y: d => +d['wind'],
        groupY: 'max',
      },
      date: { start: new Date('2012-01-01') },
      range: 12,
      scale: {
        color: {
          type: 'quantize',
          scheme: 'Oranges',
          domain: [0, 1, 2, 3, 4, 5, 6, 7,8,9,10,11],
        },
      },
      domain: {
        type: 'month',
      },
      subDomain: { type: 'day', radius: 2 },
      itemSelector: '#ex-wind',
    },
    [
      [
        Tooltip,
        {
          text: function (date, value, dayjsDate) {
            return (
              (value ? value + 'km/h' : 'No data') +
              ' on ' +
              dayjsDate.format('LL')
            );
          },
        },
      ],
      [
        Legend,
        {
          tickSize: 0,
          width: '100%',
          itemSelector: '#ex-wind-legend',
          label: 'Seattle wind (km/h)',
        },
      ],
    ]
  );
  render(
  );
      </script>
      <script>
          function next(e){
              e.preventDefault();
              cal.next();
          }
          function prev(e){
              e.preventDefault();
              cal.previous();
          }
      </script>
@endpush

@endsection


