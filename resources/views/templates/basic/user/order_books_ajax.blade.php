<div class="text-end">
           
</div>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="custom--card">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive transparent-form">
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
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-4 justify-content-center d-flex">
    {{ paginateLinks($order_data) }}
</div>