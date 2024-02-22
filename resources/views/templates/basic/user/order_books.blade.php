@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
<section class="pt-100 pb-100">
    <div class="container content-container" id="pst_hre">
        <div class="text-start">
            <form action="" method="get" id="filter_frm">
                <div class="row">
                    <div class="col-lg-3 form-group">
                        <label>@lang('Broker')</label>
                        <select name="broker_name" class="form--control" id="broker_name">
                            <option value="">All</option>
                            <option value="OMS_ORDERS" {{$brokerId=="OMS_ORDERS" ? "selected":""}}>OMS Config Orders</option>
                            @foreach ($broker_data as $item)
                                <option value="{{$item->id}}" {{$item->id==$brokerId ? 'selected':''}}>{{$item->broker_name.' ('.$item->account_user_name.')'}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 form-group mt-auto">
                        <button class="btn btn--base w-100" id="sub_btn" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                    </div>
                </div>
            </form>
        </div>
        @foreach ($order_data as $key=>$order_data)
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h5 class="card-title">{{$key}}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive transparent-form">
                                @if($key=="OMS CONFIG")
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Placed By</th>
                                            <th>Order ID</th>
                                            <th>TXN Type</th>
                                            <th>Symbol Name</th>
                                            <th>QTY</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Message</th>
                                            <th>Order Date</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($order_data as $item)
                                        <tr>
                                            <td>{{$item->broker_username}}</td>
                                            <td>{{$item->order_id}}</td>
                                            <td>{{$item->transaction_type}}</td>
                                            <td>{{$item->trading_symbol}}</td>
                                            <td>{{$item->quantity}}</td>
                                            <td>{{$item->price}}</td>
                                            <td>{{$item->status}}</td>
                                            <td>{{$item->status_message}}</td>
                                            <td>{{showDateTime($item->order_datetime)}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9"><h4 class="text-center">NO DATA</h4></td>
                                        </tr>
                                    @endforelse
                                                
                                    </tbody>
                                </table>
                                @else

                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="mt-4 justify-content-center d-flex">
            {{ isset($order_data['OMS CONFIG']) ?  paginateLinks($order_data['OMS CONFIG']) : ''}}
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


