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
                    <div class="custom--card card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ENTRY DATE</th>
                                            <th>SYMBOL NAME</th>
                                            <th>QTY</th>
                                            <th>PRICE</th>
                                            <th>LTP</th>
                                            <th>Average Price</th>
                                            <th>UNREALIZED P/L</th>
                                        </tr>
                                    </thead>
                                    <tbody id="watchList">

                                        @if (isset($respond))
                                            @if ($respond['status'] == true)
                                                @php $watchList = $respond['data']['fetched']; @endphp
                                            @else
                                                @php $watchList = NULL; @endphp
                                            @endif
                                        @else
                                            @php $watchList = NULL; @endphp
                                        @endif
                                        @isset($wishlistorder)
                                            @if (count($wishlistorder) && $watchList != NULL)
                                                @foreach ($wishlistorder as $item)
                                                    @php
                                                        $key = array_search($item->token, array_column($watchList, 'symbolToken'));
                                                    @endphp
                                                    <tr>
                                                        <td>{{($item->created_at)->format('d-M, Y H:m:s')}}</td>
                                                        <td>{{$item->symbol}}</td>
                                                        <td>{{$item->quantity}}</td>
                                                        <td>{{$item->buy_price}}</td>
                                                        <td>{{$watchList[$key]['ltp']}}</td>
                                                        <td>{{$item->avg_price}}</td> 
                                                        <td class="{{ (($watchList[$key]['ltp'] - $item->buy_price) * $item->quantity) > 0 ? 'text-success' : 'text-danger' }}">{{($watchList[$key]['ltp'] - $item->buy_price) * $item->quantity}}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%">No Order Found</td>
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
@endsection