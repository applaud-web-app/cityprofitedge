<form action="{{route('user.portfolio.store-oms-config')}}" class="transparent-form" method="post">
    @csrf
    <div class="modal-header">
    <h5 class="modal-title" id="editclientModalLabel">Add New</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6 form-group">
                <label for="symbol_name_up" class="required">Symbol Name <sup class="text--danger">*</sup></label>
                <select name="symbol_name_up" class="form--control" required="" id="symbol_name_up">
                    <option value="">Select Symbol</option>
                    @foreach (allTradeSymbols() as $item)
                        <option value="{{$item}}">{{$item}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label for="signal_tf_up" class="required">Signal TF<sup class="text--danger">*</sup></label>
                <select name="signal_tf_up" class="form--control" required="" id="signal_tf_up">
                    <option value="">Select Signal TF</option>
                    @foreach (allTradeTimeFrames() as $item)
                        <option value="{{$item}}">{{$item}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label for="strategy_name_up" class="required">Strategy Name<sup class="text--danger">*</sup></label>
                <select name="strategy_name_up" class="form--control" required="" id="strategy_name_up">
                    <option value="">Select Strategy</option>
                    @foreach (strategyNames() as $item)
                        <option value="{{$item}}">{{$item}}</option>    
                    @endforeach
                </select>
            </div>


            <div class="col-lg-6 form-group ce_pe_symbl_1" style="display: none;">
                <label for="ce_symbol_name_up" class="required">CE Symbol Name</label>
                <select name="ce_symbol_name_up" class="form--control" id="ce_symbol_name_up">
                   
                </select>
            </div>

            <div class="col-lg-6 form-group ce_pe_symbl_2" style="display: none;">
                <label for="pe_symbol_name_up" class="required">PE Symbol Name</label>
                <select name="pe_symbol_name_up" class="form--control" id="pe_symbol_name_up">
                    
                </select>
            </div>



            <div class="col-lg-6 form-group">
                <label for="client_name_up" class="required">Client Name <sup class="text--danger">*</sup></label>
                <select name="client_name_up" class="form--control" required="" id="client_name_up">
                    <option value="">Select Client</option>
                    @foreach ($brokers as $item)
                        <option value="{{$item->id}}">{{$item->client_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label for="entry_point_up" class="required">Entry Point <sup class="text--danger">*</sup></label>
                <select name="entry_point_up" class="form--control" required="" id="entry_point_up">
                    <option value="Fibonacci">Fibonacci</option>
                </select>
            </div>

            
            
            <div class="col-lg-6 form-group">
                <label for="order_type_up" class="required">Order Type<sup class="text--danger">*</sup></label>
                <select name="order_type_up" class="form--control" required="" id="order_type_up">
                    <option value="">Select Order Type</option>
                    <option value="LIMIT">LIMIT</option>
                    <option value="MARKET">MARKET</option>  
                </select>
            </div>

            <div class="col-lg-6 form-group" id="pyramid_percent_dv_up" style="display: none;">
                <label for="pyramid_percent_up" class="required">Pyramid(%)<sup class="text--danger">*</sup></label>
                <select name="pyramid_percent_up" class="form--control" id="pyramid_percent_up">
                    <option value="">Select Pyramid</option>
                    <option value="33">33</option>
                    <option value="50">50</option>  
                    <option value="100">100</option>  
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label for="product_up" class="required">Product<sup class="text--danger">*</sup></label>
                <select name="product_up" class="form--control" id="product_up" required>
                    <option value="">Select Product</option>
                    <option value="NRML">NRML</option>
                    <option value="MIS">MIS</option>  
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label for="ce_quantity_up" class="required">CE Qty<sup class="text--danger">*</sup></label>
                <input type="text" name="ce_quantity_up" id="ce_quantity_up" class="form--control">
            </div>

            <div class="col-lg-6 form-group">
                <label for="pe_quantity_up" class="required">PE Qty<sup class="text--danger">*</sup></label>
                <input type="text" name="pe_quantity_up" id="pe_quantity_up" class="form--control">
            </div>

            <div class="col-lg-6 form-group">
                <label for="pyramid_freq_up" class="required">Pyramid Freq. (In Minutes)<sup class="text--danger">*</sup></label>
                <input type="number" name="pyramid_freq_up" id="pyramid_freq_up" class="form--control">
            </div>

            <div class="col-lg-6 form-group">
                <label for="exit_1_qty_up" class="required">Exit 1 Qty<sup class="text--danger">*</sup></label>
                <input type="text" name="exit_1_qty_up" id="exit_1_qty_up" class="form--control">
            </div>

            <div class="col-lg-6 form-group">
                <label for="exit_1_target_up" class="required">Exit 1 Target<sup class="text--danger">*</sup></label>
                <input type="text" name="exit_1_target_up" id="exit_1_target_up" class="form--control">
            </div>

            <div class="col-lg-6 form-group">
                <label for="exit_2_qty_up" class="required">Exit 2 Qty<sup class="text--danger">*</sup></label>
                <input type="text" name="exit_2_qty_up" id="exit_2_qty_up" class="form--control">
            </div>

            <div class="col-lg-6 form-group">
                <label for="exit_2_target_up" class="required">Exit 2 Target<sup class="text--danger">*</sup></label>
                <input type="text" name="exit_2_target_up" id="exit_2_target_up" class="form--control">
            </div>                   
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-md btn--base">Deploy</button>
    </div>
</form>
