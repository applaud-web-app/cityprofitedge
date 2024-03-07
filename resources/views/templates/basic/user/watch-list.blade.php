@extends($activeTemplate . 'layouts.master')
@section('content')
    @push('style')
    <style>
        .custom--table thead th{
            text-align: left !important;
            padding: 7px 5px !important;
        }
        .custom--table tbody td{
            text-align: left !important;
            padding: 5px !important;
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
                                <a class="nav-link active" aria-current="page" href="{{route('user.watchList')}}">Watchlist</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('user.watchListOrder')}}">Order Book</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('user.watchListPosition')}}">Trade Position</a>
                            </li>
                        </ul>
                    </div>
                    <div class="custom--card card" id="pst_hre">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Symbol Name</th>
                                            <th>Exchange</th>
                                            <th>LTP</th>
                                            <th>OPEN</th>
                                            <th>HIGH</th>
                                            <th>LOW</th>
                                            <th>CLOSE</th>
                                            <th>Net Change</th>
                                            <th>Percent Change</th>
                                            <th>Avg Price</th>
                                            <th>Trade Volume</th>
                                            <th>Open Interest</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="watchList">
                                        @isset($finalResponse)
                                            @if ($finalResponse != false)
                                                @foreach ($finalResponse as $item)
                                                    @php $text = "text-danger"; @endphp
                                                    @if ($item['netChange'] > 0)
                                                        @php $text = "text-success"; @endphp
                                                    @endif
                                                    <tr>
                                                        <td class="{{$text}}">{{$item['symbol_name']}}</td>
                                                        <td class="{{$text}}">{{$item['exchange']}}</td>
                                                        <td class="{{$text}}">{{$item['ltp']}}</td>
                                                        <td class="{{$text}}">{{$item['open']}}</td>
                                                        <td class="{{$text}}">{{$item['high']}}</td>
                                                        <td class="{{$text}}">{{$item['low']}}</td>
                                                        <td class="{{$text}}">{{$item['close']}}</td>
                                                        <td class="{{$text}}">{{$item['netChange']}}</td>
                                                        <td class="{{$text}}">{{$item['percentChange']}}</td>
                                                        <td class="{{$text}}">{{$item['avgPrice']}}</td>
                                                        <td class="{{$text}}">{{$item['tradeVolume']}}</td>
                                                        <td class="{{$text}}">{{$item['opnInterest']}}</td>
                                                        <td class="{{$text}}"><button class="py-0 buyModal btn btn-primary btn-sm" data-token="{{$item['symbolToken']}}" data-symbol="{{$item['symbol_name']}}" data-ltp="{{$item['ltp']}}" data-price="{{$item['ltp']}}" data-exchange="{{$item['exchange']}}" data-type="BUY" data-bs-toggle="modal" data-bs-target="#buy">BUY</button><button class="py-0 buyModal ms-1 btn btn-danger btn-sm" data-token="{{$item['symbolToken']}}" data-symbol="{{$item['symbol_name']}}" data-ltp="{{$item['ltp']}}" data-price="{{$item['ltp']}}" data-exchange="{{$item['exchange']}}" data-type="SELL" data-bs-toggle="modal" data-bs-target="#buy">SELL</button></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td colspan="100%" class="d-flex justify-content-center text-center">
                                                    No Data Found...
                                                </td>
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
        </div>
    </section>

    <!-- BUY -->
    <div class="modal fade"  id="buy" tabindex="-1" aria-labelledby="buyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="buyCurrentStock">
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function(){
            $(document).on('click','.buyModal',function(){
                var token = $(this).data('token');
                var symbol = $(this).data('symbol');
                var price = $(this).data('price');
                var ltp = $(this).data('ltp');
                var exchange = $(this).data('exchange');
                var type = $(this).data('type');
                var buyhtml = 
                `<div class="modal-header">
                    <h5 class="modal-title" id="buyLabel">${symbol}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user.buyWatchListStock')}}" class="transparent-form" method="POST">
                        @csrf
                        <div class="mb-0">
                            <input type="radio" name="order_type" value="market" onclick="limitPrice()" id="market" checked>
                            <label for="market" class="col-form-label">Market </label>
                            <input type="radio" name="order_type" value="limit" onclick="limitPrice()" id="limit">
                            <label for="limit" class="col-form-label">Limit </label>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="col-form-label">Price:</label>
                            <input type="number" min="0" step="any" name="price" data-price="${price}" class="form--control" value="${price}" id="price" placeholder="Enter Your Price" required readonly>
                            <input type="hidden" name="symbol" class="form-control" value="${symbol}" id="symbol" >
                            <input type="hidden" name="token" class="form-control" value="${token}" id="token">
                            <input type="hidden" name="ltp" class="form-control" value="${ltp}" id="ltp">
                            <input type="hidden" name="exchange" class="form-control" value="${exchange}" id="exchange">
                            <input type="hidden" name="type" class="form-control" value="${type}" id="type">
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="col-form-label">Quantity:</label>
                            <input type="number" name="quantity" class="form--control" value="1" min="1" id="quantity" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>`;
                $('#buyCurrentStock').html(buyhtml);
            });
        }); 
        
    </script>

    <script>
        function limitPrice(){
            if($('#limit').prop('checked')){
                    $('#price').removeAttr("readonly");
            }else{
                $price = $('#price').data('price');
                $('#price').attr("readonly","true");
                $('#price').val($price);
            }   
        }
    </script>
    <script>
        $(document).ready(function(){
            function reloadData(){
                $.get('{!!$fullUrl!!}',function(data){
                    if(data=='NO_DATA'){
                        reloadData();
                        return;
                    }
                    $("#pst_hre").html(data);
                });
            }
        
            setInterval(() => {
                reloadData();
            }, 15000);//call every 1/2 minute
        });
    </script>
@endpush
