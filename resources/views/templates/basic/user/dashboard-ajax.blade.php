<div class="d-flex justify-content-between align-items-center">
    <h5 class="text-center">@lang('Greeks Market View')</h5>
    <form action="" class="transparent-form mb-3" method="GET">
        <div class="row">
            <div class="col-lg-3 form-group">
                <label for="stock_name">Symbol Name</label>
                <select name="stock_name" class="form--control" id="stock_name">
                    <option value="">Select Symbol Name</option>  
                    @foreach ($StrengthsymbolArr as $item)
                        <option value="{{$item}}" {{$stock_name == $item ? "selected" : ""}}>{{$item}}</option>  
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 form-group">
                <label for="atm_type">ATM</label>
                <select name="atm_type" class="form--control" id="atm_type">
                    <option value="">Select ATM</option>  
                    <option value="ATM-1" {{$atmRange == "ATM-1" ? "selected" : ""}}>ATM-1</option>
                    <option value="ATM" {{$atmRange == "ATM" ? "selected" : ""}}>ATM</option>
                    <option value="ATM+1" {{$atmRange == "ATM+1" ? "selected" : ""}}>ATM+1</option>
                </select>
            </div>
            <div class="col-lg-3 form-group mt-auto">
                <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> Filter</button>
            </div>
            <div class="col-lg-3 col-md-3 col-6 form-group mt-auto">
                <a href="{{url('/user/dashboard')}}" class="btn btn--base w-100"><i class="las la-redo-alt"></i> Refresh</a>
            </div>
        </div>
    </form>
</div>
<div class="custom--card">
    <div class="card-body p-0">
        <div class="table-responsive--md">
            <table class="table custom--table">
                <thead>
                    <tr>
                        <th class="text-uppercase">@lang('Stock Name')</th>
                        <th class="text-uppercase">@lang('iv')</th>
                        <th class="text-uppercase">@lang('delta')</th>
                        <th class="text-uppercase">@lang('theta')</th>
                        <th class="text-uppercase">@lang('vega')</th>
                        <th class="text-uppercase">@lang('gamma')</th>
                        <th class="text-uppercase">@lang('strength')</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                        @forelse($strengthData as $data)
                            <tr>
                                <td>{{$data->symbol_name}}</td>
                                <td>{{$data->iv}}</td>
                                <td>{{$data->delta}}</td>
                                <td>{{$data->theta}}</td>
                                <td>{{$data->vega}}</td>
                                <td>{{$data->gamma}}</td>
                                <td>{{$data->strength}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">NO DATA FOUND</td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>
        </div>
    </div>
</div>
