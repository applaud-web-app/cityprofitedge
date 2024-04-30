@extends($activeTemplate . 'layouts.master')
@section('content')

    @push('style')
        {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" /> --}}
    @endpush


    <section class="pt-100 pb-100">
        <div class="container content-container">
            <div class="mb-1">
                <div class="custom--nav-tabs mb-3">
                    <ul class="nav ">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('user/portfolio-top-gainers') }}">Index Options</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('user/portfolio-top-gainers-stock') }}">Stock Options</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('user/portfolio-greeks') }}">Greeks Options</a>
                        </li>
                    </ul>
                </div>
            </div>
            <form action="" class="transparent-form mb-3">
                <div class="row">
                    <div class="col-lg-3 form-group">
                        <label>@lang('Symbol Name')</label>
                        <select name="stock_name" class="form--control" id="">
                            <option value="">Select Symbol Name</option>
                            @foreach ($symbolArr as $v)
                                @if (in_array($v, [
                                        'CRUDEOIL',
                                        'BANKNIFTY',
                                        'FINNIFTY',
                                        'SILVER',
                                        'NIFTY',
                                        'MIDCPNIFTY',
                                        'NATURALGAS',
                                        'SILVER',
                                        'GOLD',
                                    ]))
                                    <option value="{{ $v }}" {{ $v == $stockName ? 'selected' : '' }}>
                                        {{ $v }}</option>
                                @endif
                            @endforeach
                        </select>
                        {{-- <input type="text" name="search" value="" class="form--control" placeholder="@lang('Stock Name')"> --}}
                    </div>
                    <div class="col-lg-3 form-group">
                        <label>@lang('TimeFrame')</label>
                        <select name="time_frame" class="form--control">
                            @foreach (allTradeTimeFrames() as $item)
                                <option value="{{ $item }}" {{ $item == $timeFrame ? 'selected' : '' }}>
                                    {{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 form-group mt-auto">
                        <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i>
                            @lang('Filter')</button>
                    </div>
                    <div class="col-lg-3 col-md-3 col-6 form-group mt-auto">
                        <a href="{{ url('/user/portfolio-greeks') }}" class="btn btn--base w-100"><i
                                class="las la-redo-alt"></i> @lang('Refresh')</a>
                    </div>
                </div>
            </form>


            <div class="mb-1">
                <div class="custom--nav-tabs border-0 mb-3">
                    <ul class="nav d-flex justify-content-end">
                        <li class="nav-item">
                            <a class="nav-link " href="{{ url('user/portfolio-greeks') }}">Greeks Options</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('user/portfolio-greeks-graphs') }}">Greeks Graphs</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="pst_hre">
                @php $tableData = []; @endphp
                @if ($stockName != '')
                    @php
                        $data = \DB::connection('mysql_rm')
                            ->table($stockName)
                            ->select('*')
                            ->where(['date' => $todayDate, 'timeframe' => $timeFrame])
                            ->get();
                        if (count($data) == 0) {
                            $data = \DB::connection('mysql_rm')
                                ->table($stockName)
                                ->select('*')
                                ->where(['timeframe' => $timeFrame])
                                ->get();
                        }
                        $atmData = [];
                        foreach ($data as $vvl) {
                            if (isset($vvl->atm) && $vvl->atm == 'ATM') {
                                $atmData[] = $vvl;
                            }
                        }
                        $totalItems = 0;
                        $itemsPerPage = 100;
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                    @endphp
                    @forelse($atmData as $val)
                        @php
                            $arrData = json_decode($val->data, true);
                            $totalItems = count($arrData['Date']);
                            $newArr = array_reverse($arrData['Date'], true);
                            $currentItems = array_slice(
                                $newArr,
                                ($currentPage - 1) * $itemsPerPage,
                                $itemsPerPage,
                                true,
                            );
                        @endphp
                    @empty
                    @endforelse
                    @php
                        $DATE_NOW = array_slice($arrData['Date'],40);
                        $TIME_NOW = array_slice($arrData['time'],40);
                        $tableData[$stockName]['time'] = array_map(
                            function ($k, $y) use ($DATE_NOW) {
                                return date('d-M-Y', $DATE_NOW[$k] / 1000) . ', ' . date('g:i a', strtotime($y));
                            },
                            array_keys($DATE_NOW),
                            $TIME_NOW,
                        );
                        $tableData[$stockName]['CE'] = array_slice($arrData['CE'],40);
                        $tableData[$stockName]['PE'] = array_slice($arrData['PE'],40);
                        $tableData[$stockName]['CE_Delta'] = array_slice($arrData['CE_Delta'],40);
                        $tableData[$stockName]['PE_Delta'] = array_slice($arrData['PE_Delta'],40);
                    @endphp
                @else
                    @foreach ($symbolArr as $v)
                        @php
                            if (
                                !in_array($v, [
                                    'CRUDEOIL',
                                    'BANKNIFTY',
                                    'FINNIFTY',
                                    'SILVER',
                                    'NIFTY',
                                    'MIDCPNIFTY',
                                    'NATURALGAS',
                                    'SILVER',
                                    'GOLD',
                                ])
                            ) {
                                continue;
                            }
                            if ($v == 'LTP') {
                            } else {
                                $dataLast = \DB::connection('mysql_rm')
                                    ->table($v)
                                    ->select('date')
                                    ->where(['timeframe' => $timeFrame])
                                    ->orderBy('id', 'DESC')
                                    ->first();
                                if ($dataLast) {
                                    $todayDate = $dataLast->date;
                                }
                                $data = \DB::connection('mysql_rm')
                                    ->table($v)
                                    ->select('*')
                                    ->where(['date' => $todayDate, 'timeframe' => $timeFrame])
                                    ->get();
                            }
                        @endphp
                        @php
                            $atmData = [];
                            foreach ($data as $vvl) {
                                if (isset($vvl->atm) && $vvl->atm == 'ATM') {
                                    $atmData[] = $vvl;
                                }
                            }
                        @endphp
                        @php $i=1; @endphp
                        @forelse($atmData as $val)
                            @php
                                $arrData = json_decode($val->data, true);
                                $CE = array_slice($arrData['CE'], -5);
                                $PE = array_slice($arrData['PE'], -5);
                                $Date = array_slice($arrData['Date'], -5);
                                $time = array_slice($arrData['time'], -5);
                                $CEIV = array_slice($arrData['CE_IV'], -5);
                                $PEIV = array_slice($arrData['PE_IV'], -5);
                                $CEDelta = array_slice($arrData['CE_Delta'], -5);
                                $PEDelta = array_slice($arrData['PE_Delta'], -5);
                                $CETheta = array_slice($arrData['CE_Theta'], -5);
                                $PETheta = array_slice($arrData['PE_Theta'], -5);
                                $CEVega = array_slice($arrData['CE_Vega'], -5);
                                $PEVega = array_slice($arrData['PE_Vega'], -5);
                                $CEGamma = array_slice($arrData['CE_Gamma'], -5);
                                $PEGamma = array_slice($arrData['PE_Gamma'], -5);
                            @endphp
                            @php
                                $tableData[$v]['time'] = array_map(
                                    function ($k, $y) use ($Date) {
                                        return date('d-M-Y', $Date[$k] / 1000) . ', ' . date('g:i a', strtotime($y));
                                    },
                                    array_keys($Date),
                                    $time,
                                );

                                $tableData[$v]['CE'] = $CE;
                                $tableData[$v]['PE'] = $PE;
                                $tableData[$v]['CE_Delta'] = $CEDelta;
                                $tableData[$v]['PE_Delta'] = $PEDelta;
                            @endphp
                        @empty
                            @php
                                $time2 = '';
                                $CE2 = ['NO DATA'];
                                $PE2 = ['NO DATA'];
                                $close_CE2 = '';
                                $close_PE2 = '';
                            @endphp
                        @endforelse
                    @endforeach
                @endif
                @foreach ($tableData as $key => $item)
                    <div class="row mb-5">
                        <div class="col-lg-12">
                            <div class="custom--card card">
                                <div class="card-header">
                                    <h6 class="card-title">{{ $key }}</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="card-body chart2">
                                        <div id="apex-analysis-chart{{ $loop->index }}" style="width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection


@push('script')

    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    @isset($tableData)
        @php $i =0; @endphp
        @foreach ($tableData as $key => $item)
            <script>
                var series = {
                    "monthDataSeries1": {
                        "prices": <?= json_encode($item['CE_Delta']) ?>,
                        "dates": <?= json_encode($item['time']) ?>
                    },
                    "monthDataSeries2": {
                        "prices": <?= json_encode($item['PE_Delta']) ?>,
                        "dates": <?= json_encode($item['time']) ?>
                    }
                }
                var options = {
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
                        width: 2
                    },
                    colors: ['#00bf63', '#FF0000'],
                    series: [{
                            name: {!! json_encode($item['CE'][0]) !!},
                            data: series.monthDataSeries1.prices,
                        },
                        {
                            name: {!! json_encode($item['PE'][0]) !!},
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
                        categories: <?= json_encode($item['time']) ?>,
                    },
                    noData: {
                        text: "NO DATA FOUND",
                        align: 'center',
                        verticalAlign: 'middle',
                        offsetX: 0,
                        offsetY: 0,
                    }
                };
                var chart = new ApexCharts(document.querySelector("#apex-analysis-chart" + {{ $i++ }} + ""), options);
                chart.render();
            </script>
        @endforeach
    @endisset
@endpush
