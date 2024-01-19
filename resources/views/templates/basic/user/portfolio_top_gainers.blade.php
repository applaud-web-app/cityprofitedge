@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="" class="transparent-form mb-3">
            <div class="row">
                {{-- <div class="col-lg-3 form-group">
                    <label>@lang('Stock Name')</label>
                    <input type="text" name="search" value="{{ request()->search }}" class="form--control" placeholder="@lang('Stock Name')">
                </div> --}}
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
            </div>
        </form>
        
        @foreach($symbolArr as $v)
            @php 
               if($v == "LTP"){

               }else{
                $data = \DB::connection('mysql_rm')->table($v)->select('*')->where(['date'=>$todayDate,'timeframe'=>$timeFrame])->get(); 
               }
              
            @endphp
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
                                            <th>VMAP CE</th>
                                            <th>VMAP PE</th>
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
                                                    // dd($arrData);
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
        <div class="mt-4 justify-content-center d-flex">
            {{-- {{ paginateLinks($portfolioTopGainers) }} --}}
        </div>
    </div>
</section>
@endsection


