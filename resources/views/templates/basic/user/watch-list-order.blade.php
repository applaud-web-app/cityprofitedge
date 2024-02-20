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
                            <a class="nav-link active" href="{{route('user.watchListOrder')}}">Order Book</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="{{route('user.watchListPosition')}}">Trade Position</a>
                            </li>
                        </ul>
                    </div>
                    <div class="custom--card card" id="pst_hre">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ORDER ID</th>
                                            <th>TNX TYPE</th>
                                            <th>SYMBOL NAME</th>
                                            <th>ORDER TYPE</th>
                                            <th>QTY</th>
                                            <th>PRICE</th>
                                            <th>STATUS</th>
                                            <th>ORDER DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="watchList">
                                        @isset($wishlistorder)
                                        @if (count($wishlistorder))
                                            @foreach ($wishlistorder as $item)
                                                <tr>
                                                    <td>{{$item->order_id}}</td>
                                                    <td>{{$item->type}}</td>
                                                    <td>{{$item->symbol}}</td>
                                                    <td>{{ucfirst($item->order_type)}}</td>
                                                    <td>{{$item->quantity}}</td>
                                                    <td>{{$item->buy_price}}</td>
                                                    <td>{{ucfirst($item->status)}}</td>
                                                    <td>{{($item->created_at)->format('d-M, Y H:i:s')}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%" class="text-center">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
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