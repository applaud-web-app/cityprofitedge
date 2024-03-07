@extends($activeTemplate . 'layouts.master')
@section('content')
    @push('style')
    <style>
        .custom--table thead th{
            text-align: left !important;
        }
        .custom--table tbody td{
            text-align: left !important;
        }
    </style>
    @endpush
    <section class="pt-100 pb-100">
        <div class="container content-container">
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="custom--nav-tabs mb-3">
                        <ul class="nav ">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="{{route('user.watchList')}}">Watchlist</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('user.watchListOrder')}}">Order Book</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('user.watchListPosition')}}">Trade Position</a>
                            </li>
                        </ul>
                    </div>
                    <div class="custom--card card" id="pst_hre">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ENTRY DATE</th>
                                            <th>SYMBOL NAME</th>
                                            <th>BUY QTY</th>
                                            <th>BUY PRICE</th>
                                            <th>BUY VALUE</th>
                                            <th>SELL QTY</th>
                                            <th>SELL PRICE</th>
                                            <th>SELL VALUE</th>
                                            <th>NET CHANGE</th>
                                            <th>LTP</th>
                                            <th>MTM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @isset($wishlistorder)
                                            @if (count($wishlistorder))
                                                @foreach ($wishlistorder as $item)
                                                    @php
                                                        $angleData = App\Models\AngelApiInstrument::WHERE('token',$item->token)->first();
                                                        $tickSize = 1;
                                                        $buyValue = $item->buy_quantity * $item->buy_price;
                                                        $sellValue = $item->sell_quantity * $item->sell_price;
                                                        if (isset($angleData)) {
                                                            if($angleData->name == "CRUDEOIL"){
                                                                $buyValue = $buyValue*100;
                                                                $sellValue = $sellValue*100;
                                                                $tickSize = $angleData['lotsize'];
                                                            }else if($angleData->name == "GOLD"){
                                                                $buyValue = $buyValue*10;
                                                                $sellValue = $sellValue*10;
                                                                $tickSize = $angleData['lotsize'];
                                                            }else if($angleData->name == "NATURALGAS"){
                                                                $buyValue = $buyValue*1250;
                                                                $sellValue = $sellValue*1250;
                                                                $tickSize = $angleData['lotsize'];
                                                            }else if($angleData->name == "SILVER"){
                                                                $buyValue = $buyValue*5;
                                                                $sellValue = $sellValue*5;
                                                                $tickSize = $angleData['lotsize'];
                                                            }else{
                                                                $buyValue = $buyValue*$angleData->tick_size;
                                                                $sellValue = $sellValue*$angleData->tick_size;
                                                                $tickSize = $angleData['lotsize'];
                                                            }
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{($item->created_at)->format('d-M, Y H:i:s')}}</td>
                                                        <td>{{$item->symbol}}</td>
                                                        <td>{{$item->buy_quantity}}</td>
                                                        <td>{{$item->buy_price}}</td>
                                                        <td>{{$buyValue}}</td>
                                                        <td>{{$item->sell_quantity}}</td>
                                                        <td>{{$item->sell_price}}</td> 
                                                        <td>{{$sellValue}}</td>
                                                        <td>{{$item->net_change}}</td>
                                                        <td>{{$item->ltp}}</td>
                                                        @php
                                                            $textColor = "text-success";
                                                            $totalVal = ($item->ltp - $item->buy_price) * $item->buy_quantity;
                                                            if(($totalVal * $tickSize) < 0){
                                                                $textColor = "text-danger";
                                                            }
                                                        @endphp
                                                    <td class="{{$textColor}}" {{$tickSize}}>{{round($totalVal * $tickSize,2)}}</td>
                                                    </tr>
                                                    @php
                                                        $total += $totalVal * $tickSize;
                                                    @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%" class="d-flex justify-content-center text-center">
                                                        Please Wait Data Is Loading...
                                                    </td>
                                                </tr>
                                            @endif
                                        @endisset
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-danger">Total Profit : {{round($total,2)}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script>
    // $(document).ready(function(){
    //     function reloadData(){
    //         $.get('{!!$fullUrl!!}',function(data){
    //             if(data=='NO_DATA'){
    //                 reloadData();
    //                 return;
    //             }
    //             $("#pst_hre").html(data);
    //         });
    //     }

    //     setInterval(() => {
    //         reloadData();
    //     }, 30000);//call every 1/2 minute
    // })

    $(document).ready(function(){
        function reloadData(){
            $.get('{!!$fullUrl!!}', function(data){
                if(data !== 'NO_DATA'){
                    $("#pst_hre").html(data);
                }
            }).fail(function(){
                // Handle errors here, such as server unavailability
                console.error('Failed to fetch data from the server.');
            });
        }

        setInterval(function(){
            reloadData();
        }, 30000); // Reload every 30 seconds
    });
</script>
@endpush