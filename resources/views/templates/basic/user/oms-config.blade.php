@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
<section class="pt-100 pb-100">
    <div class="container content-container">
        <div class="text-end">
            <button class="btn btn--base" type="button" data-bs-toggle="modal" data-bs-target="#clientModal"><i class="las la-plus"></i> @lang('Add New Row')</button>
        </div>
        
       
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md table-responsive transparent-form">
                            <table class="table custom--table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Symbol Name</th>
                                        <th>Signal TF</th>
                                        <th>TXN Type</th>
                                        <th>Entry Poit</th>
                                        <th>Pyramid1</th>
                                        <th>Pyramid2</th>
                                        <th>Pyramid3</th>
                                        <th>Pyramid %</th>
                                        <th>Order Type</th>
                                        <th>Product</th>
                                        <th>Pyramid Freq</th>
                                        <th>CE Symbol Name</th>
                                        <th>CE Qty</th>
                                        <th>PE Symbol Name</th>
                                        <th>PE Qty</th>
                                        <th>Client Name</th>
                                        <th>Strategy Name</th>
                                        <th>Exit1 Qty</th>
                                        <th>Exit2 Qty</th>
                                        <th>Exit1 Target</th>
                                        <th>Exit2 Target</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 10; $i++)
                                    <tr>
                                      <td>
                                         <div class="form-group mb-0">
                                              <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                         </div>
                                      </td>
                                      <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                          <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                          <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                          <button class="btn btn-sm btn--base deplay_btn" type="submit">@lang('deploy')</button>
                                        </div>
                                     </td>

                                     



                                      
                                       
                                    
                                    </tr>    
                                    @endfor
                                             
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

<div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{route('user.portfolio.store-broker-details')}}" class="transparent-form" method="post">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="clientModalLabel">Add New</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label for="symbol_name" class="required">Symbol Name <sup class="text--danger">*</sup></label>
                        <select name="symbol_name" class="form--control" required="" id="symbol_name">
                            <option value="">Select Symbol</option>
                            @foreach (allTradeSymbols() as $item)
                                <option value="{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="signal_tf" class="required">Signal TF<sup class="text--danger">*</sup></label>
                        <select name="signal_tf" class="form--control" required="" id="signal_tf">
                            <option value="">Select Signal TF</option>
                            @foreach (allTradeTimeFrames() as $item)
                                <option value="{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 form-group ce_pe_symbl" style="display: none;">
                        <label for="ce_symbol_name" class="required">CE Symbol Name<sup class="text--danger">*</sup></label>
                        <select name="ce_symbol_name" class="form--control" required="" id="ce_symbol_name">
                           
                        </select>
                    </div>

                    <div class="col-lg-6 form-group ce_pe_symbl" style="display: none;">
                        <label for="pe_symbol_name" class="required">PE Symbol Name<sup class="text--danger">*</sup></label>
                        <select name="pe_symbol_name" class="form--control" required="" id="pe_symbol_name">
                            
                        </select>
                    </div>



                    <div class="col-lg-6 form-group">
                        <label for="client_name" class="required">Client Name <sup class="text--danger">*</sup></label>
                        <select name="client_name" class="form--control" required="" id="client_name">
                            <option value="">Select Client</option>
                            @foreach ($brokers as $item)
                                <option value="{{$item->id}}">{{$item->client_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    

                    <div class="col-lg-6 form-group">
                        <label for="strategy_name" class="required">Strategy Name<sup class="text--danger">*</sup></label>
                        <select name="strategy_name" class="form--control" required="" id="strategy_name">
                            <option value="">Select Strategy</option>
                            @foreach (strategyNames() as $item)
                                <option value="{{$item}}">{{$item}}</option>    
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-6 form-group">
                        <label for="order_type" class="required">Order Type<sup class="text--danger">*</sup></label>
                        <select name="order_type" class="form--control" required="" id="order_type">
                            <option value="">Select Order Type</option>
                            <option value="LIMIT">LIMIT</option>
                            <option value="MARKET">MARKET</option>  
                        </select>
                    </div>

                    <div class="col-lg-6 form-group" id="pyramid_percent_dv" style="display: none;">
                        <label for="pyramid_percent" class="required">Pyramid(%)<sup class="text--danger">*</sup></label>
                        <select name="pyramid_percent" class="form--control" id="pyramid_percent">
                            <option value="">Select Pyramid</option>
                            <option value="33">33</option>
                            <option value="50">50</option>  
                            <option value="100">100</option>  
                        </select>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="pyramid_percent" class="required">Product<sup class="text--danger">*</sup></label>
                        <select name="pyramid_percent" class="form--control" id="pyramid_percent" required>
                            <option value="">Select Product</option>
                            <option value="NRML">NRML</option>
                            <option value="MIS">MIS</option>  
                        </select>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="ce_quantity" class="required">CE Qty<sup class="text--danger">*</sup></label>
                        <input type="text" name="ce_quantity" id="ce_quantity" class="form--control">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="pe_quantity" class="required">PE Qty<sup class="text--danger">*</sup></label>
                        <input type="text" name="pe_quantity" id="pe_quantity" class="form--control">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="pyramid_freq" class="required">Pyramid Freq.<sup class="text--danger">*</sup></label>
                        <input type="text" name="pyramid_freq" id="pyramid_freq" class="form--control">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="exit_1_qty" class="required">Exit 1 Qty<sup class="text--danger">*</sup></label>
                        <input type="text" name="exit_1_qty" id="exit_1_qty" class="form--control">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="exit_1_target" class="required">Exit 1 Target<sup class="text--danger">*</sup></label>
                        <input type="text" name="exit_1_target" id="exit_1_target" class="form--control">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="exit_2_qty" class="required">Exit 2 Qty<sup class="text--danger">*</sup></label>
                        <input type="text" name="exit_2_qty" id="exit_2_qty" class="form--control">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="exit_2_target" class="required">Exit 2 Target<sup class="text--danger">*</sup></label>
                        <input type="text" name="exit_2_target" id="exit_2_target" class="form--control">
                    </div>

                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-md btn--base">Deploy</button>
            </div>
        </form>
      </div>
    </div>
</div>

@push('script')
<script>
    $("#order_type").on('click',function(){
        var vl = $(this).val();
        if(vl=='LIMIT'){
            $("#pyramid_percent_dv").show();
        }else{
            $("#pyramid_percent_dv").hide();
        }
    });
</script>
<script>
    $("#symbol_name,#signal_tf").on('click',function(){
        var symbl = $("#symbol_name option:selected").val();
        var signl = $("#signal_tf option:selected").val();
        if(symbl!='' && signl!=''){
            $(".ce_pe_symbl").show();
            $("#ce_symbol_name").html('<option value="">Loading...</option>');
            $("#pe_symbol_name").html('<option value="">Loading...</option>');
            $.post('{{url("user/get-pe-ce-symbol-names")}}',{'_token':'{{csrf_token()}}','symbol':symbl,'signal':signl},function(data){
                var cestr = '<option value="">Select</option>';
                var pestr = '<option value="">Select</option>';
                if(data.data.length){
                    var dataA = data.data;
                    for(var i in dataA){
                        cestr+=`<option value="${dataA[i].ce}">${dataA[i].ce}</option>`;
                        pestr+=`<option value="${dataA[i].ce}">${dataA[i].ce}</option>`;
                    }
                }
                $(".ce_pe_symbl").show();
                $("#ce_symbol_name").html(cestr);
                $("#pe_symbol_name").html(pestr);
            });
        }else{
            $(".ce_pe_symbl").hide();
        }
    })
</script>
@endpush
@endsection


