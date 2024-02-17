@extends($activeTemplate.'layouts.master')
@section('content')

@push('style')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" /> --}}
@endpush


<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="" class="transparent-form mb-3">
            <div class="row">
                <div class="col-lg-3 form-group">
                    <label>@lang('Symbol Name')</label>
                    <select name="stock_name" class="form--control" id="">
                        <option value="">Select Symbol  Name</option>
                        @foreach ($symbolArr as $v)
                            <option value="{{$v}}" {{$v==$stockName ? 'selected':''}}>{{$v}}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" name="search" value="" class="form--control" placeholder="@lang('Stock Name')"> --}}
                </div>
                <div class="col-lg-3 form-group">
                    <label>@lang('TimeFrame')</label>
                    <select name="time_frame" class="form--control">
                       @foreach (allTradeTimeFrames() as $item)
                           <option value="{{$item}}" {{$item==$timeFrame ? 'selected':''}}>{{$item}}</option>
                       @endforeach
                    </select>
                </div>
                <div class="col-lg-3 form-group mt-auto">
                    <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                </div>
                <div class="col-lg-3 col-md-3 col-6 form-group mt-auto">
                    <a href="{{url('/user/portfolio-top-gainers')}}" class="btn btn--base w-100"><i class="las la-redo-alt"></i> @lang('Refresh')</a>
                </div>
            </div>
        </form>

        <div id="pst_hre">
           
            @if ($stockName != "")
                @php
                    $data = \DB::connection('mysql_rm')->table($stockName)->select('*')->where(['date'=>$todayDate,'timeframe'=>$timeFrame])->get();
                    if(count($data)==0){
                        $data = \DB::connection('mysql_rm')->table($stockName)->select('*')->where(['timeframe'=>$timeFrame])->get();
                    }
                @endphp
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="custom--card card">
                            <div class="card-header">
                                <h6 class="card-title">{{$stockName}}</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive--md table-responsive">
                                    <table class="table custom--table text-nowrap">
                                        <thead>
                                            <tr>
                                                {{-- <th>#</th> --}}
                                                <th class="text-nowrap">DATE</th>
                                                <th>TIME</th>
                                                <th>CE Symbol Name</th>
                                                <th>PE Symbol Name</th>
                                                <th>VWAP CE</th>
                                                <th>VWAP PE</th>
                                                <th>OI CE</th>
                                                <th>OI PE</th>
                                                <th>CE CLOSE PRICE</th>
                                                <th>PE CLOSE PRICE</th>
                                                <th>BUY ACTION</th>
                                                <th>SELL ACTION</th>
                                                <th>STRATEGY NAME</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $atmData = [];
                                                foreach($data as $vvl){
                                                    if(isset($vvl->atm) && $vvl->atm=="ATM"){
                                                        $atmData[] = $vvl;
                                                    }
                                                }
                                                
                                            @endphp
                                            @php  
                                                $totalItems = 0;
                                                $itemsPerPage = 100;
                                                $currentPage =  isset($_GET['page']) ? $_GET['page'] : 1;
                                            @endphp


                                            @forelse($atmData as $val)
                                                    @php
                                                        $arrData = json_decode($val->data,true);  
                                                        $totalItems = count($arrData['Date']);
                                                        // dd($arrData['Date']);
                                                        $currentItems = array_slice($arrData['Date'], ($currentPage - 1) * $itemsPerPage, $itemsPerPage);

                                                        // $CE = $arrData['CE'];
                                                        // $PE = $arrData['PE'];
                                                        // $Date = $arrData['Date'];
                                                        // $time = $arrData['time'];
                                                        // $BUY_Action = $arrData['BUY_Action'];
                                                        // $SELL_Action = $arrData['SELL_Action'];
                                                        // $Strategy_name = $arrData['Strategy_name'];
                                                        // $vwap_CE_signal = $arrData['vwap_CE_signal'];
                                                        // $vwap_PE_signal = $arrData['vwap_PE_signal'];
                                                        // $CE_consolidated = $arrData['CE_consolidated'];
                                                        // $PE_consolidated = $arrData['PE_consolidated'];
                                                        // $close_CE = $arrData['close_CE'];
                                                        // $close_PE = $arrData['close_PE'];
                                                        $k = ($currentPage - 1) * $itemsPerPage;
                                                    @endphp
                                                    @foreach ($currentItems as $item)
                                                        <tr>
                                                            {{-- <td>{{$i++}}</td> --}}
                                                            <td>{{date("d-M-Y",($arrData['Date'][$k]/1000))}}</td>
                                                            <td>{{$arrData['time'][$k]}}</td>
                                                            <td>{{$arrData['CE'][$k]}}</td>
                                                            <td>{{$arrData['PE'][$k]}}</td>
                                                            <td>{{$arrData['vwap_CE_signal'][$k]}}</td>
                                                            <td>{{$arrData['vwap_PE_signal'][$k]}}</td>
                                                            <td>{{$arrData['CE_consolidated'][$k]}}</td>
                                                            <td>{{$arrData['PE_consolidated'][$k]}}</td>
                                                            <td>{{$arrData['close_CE'][$k]}}</td>
                                                            <td>{{$arrData['close_PE'][$k]}}</td>
                                                            <td>{{$arrData['BUY_Action'][$k]}}</td>
                                                            <td>{{$arrData['SELL_Action'][$k]}}</td>
                                                            <td>{{$arrData['Strategy_name'][$k]}}</td>
                                                        </tr>
                                                        @php
                                                            $k++;
                                                        @endphp
                                                    @endforeach
                                            @empty
                                                <tr>
                                                    <td colspan="100%"><h5 class="text-danger text-center">NO DATA</h5></td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            
                            </div>
                    
                        </div>
                        <div>
                            @php
                                $totalPages = ceil($totalItems / $itemsPerPage);
                                echo '<nav class="mt-3 justify-content-end d-flex">
                                        <ul class="pagination mb-0">';
                            @endphp
                            @for($i = 1; $i <= $totalPages; $i++)
                                @if($i == $currentPage)
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{$i}}</span></li>
                                @else
                                <li class="page-item"><a class="page-link" href="{{url('user/portfolio-top-gainers?stock_name='.$stockName.'&time_frame='.$timeFrame.'&page='.$i.'')}}">{{$i}}</a></li>
                                @endif
                            @endfor
                            @php     
                                echo '  </ul>
                                    </nav>';
                            @endphp
                        </div>

                    

                    </div>
                </div>
            @else
                
                @foreach($symbolArr as $v)
                    @php 
                    if($v == "LTP"){

                    }else{
                        $data = \DB::connection('mysql_rm')->table($v)->select('*')->where(['date'=>$todayDate,'timeframe'=>$timeFrame])->get(); 
                    }
                    @endphp
                </pre>
                    <div class="row mb-5">
                        <div class="col-lg-12">
                            <div class="custom--card card">
                                <div class="card-header">
                                    <h6 class="card-title">{{$v}}</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive--md table-responsive">
                                        <table class="table custom--table text-nowrap">
                                            <thead>
                                                <tr>
                                                    {{-- <th>#</th> --}}
                                                    <th class="text-nowrap">DATE</th>
                                                    <th>TIME</th>
                                                    <th>CE Symbol Name</th>
                                                    <th>PE Symbol Name</th>
                                                    <th>VWAP CE</th>
                                                    <th>VWAP PE</th>
                                                    <th>OI CE</th>
                                                    <th>OI PE</th>
                                                    <th>CE CLOSE PRICE</th>
                                                    <th>PE CLOSE PRICE</th>
                                                    <th>BUY ACTION</th>
                                                    <th>SELL ACTION</th>
                                                    <th>STRATEGY NAME</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $atmData = [];
                                                    foreach($data as $vvl){
                                                        if(isset($vvl->atm) && $vvl->atm=="ATM"){
                                                            $atmData[] = $vvl;
                                                        }
                                                    }
                                                @endphp
                                                @php $i=1; @endphp
                                                @forelse($atmData as $val)
                                                        @php
                                                            $arrData = json_decode($val->data,true);    
                                                            $CE = array_slice($arrData['CE'],-5);
                                                            $PE = array_slice($arrData['PE'],-5);
                                                            $Date = array_slice($arrData['Date'],-5);
                                                            $time = array_slice($arrData['time'],-5);
                                                            $BUY_Action = array_slice($arrData['BUY_Action'],-5);
                                                            $SELL_Action = array_slice($arrData['SELL_Action'],-5);
                                                            $Strategy_name = array_slice($arrData['Strategy_name'],-5);
                                                            $vwap_CE_signal = array_slice($arrData['vwap_CE_signal'],-5);
                                                            $vwap_PE_signal = array_slice($arrData['vwap_PE_signal'],-5);
                                                            $CE_consolidated = array_slice($arrData['CE_consolidated'],-5);
                                                            $PE_consolidated = array_slice($arrData['PE_consolidated'],-5);
                                                            $close_CE = array_slice($arrData['close_CE'],-5);
                                                            $close_PE = array_slice($arrData['close_PE'],-5);
                                                        @endphp
                                                        @foreach ($CE as $k=>$item)
                                                            <tr>
                                                                {{-- <td>{{$i++}}</td> --}}
                                                                <td>{{date("d-M-Y",($Date[$k]/1000))}}</td>
                                                                <td>{{$time[$k]}}</td>
                                                                <td>{{$item}}</td>
                                                                <td>{{$PE[$k]}}</td>
                                                                <td>{{$vwap_CE_signal[$k]}}</td>
                                                                <td>{{$vwap_PE_signal[$k]}}</td>
                                                                <td>{{$CE_consolidated[$k]}}</td>
                                                                <td>{{$PE_consolidated[$k]}}</td>
                                                                <td>{{$close_CE[$k]}}</td>
                                                                <td>{{$close_PE[$k]}}</td>
                                                                <td>{{$BUY_Action[$k]}}</td>
                                                                <td>{{$SELL_Action[$k]}}</td>
                                                                <td>{{$Strategy_name[$k]}}</td>
                                                            </tr>
                                                        @endforeach
                                                @empty
                                                    <tr>
                                                        <td colspan="100%"><h5 class="text-danger text-center">NO DATA</h5></td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>    
    </div>
</section>
@endsection


@push('script')
<script>
    function reloadData(){
        $.get('{!!$fullUrl!!}',function(data){
            $("#pst_hre").html(data);
        });
    }

    setInterval(() => {
        reloadData();
    }, 30000);//call every 1/2 minute
    
</script>
@endpush
