@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="#" class="transparent-form mb-3">
            <div class="row">
              
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Segments')</label>
                    <select name="segments" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="1" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="2" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('P and L')</label>
                    <select name="type" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="1" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="2" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Symbol')</label>
                    <select name="symbol" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="1" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="2" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Date Range')</label>
                    <select name="date_range" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="1" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="2" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Tags')</label>
                    <select name="tags" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="1" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="2" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group mt-auto">
                    <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>Stock Name</th>
                                        <th>Buy Date</th>
                                        <th>Buy Price</th>
                                        <th>Quantity</th>
                                        <th>Sold Date</th>
                                        <th>Sell Price</th>
                                        <th>PNL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>Test Legers stock</span>
                                        </td>
                                        <td >2024-01-16</td>
                                        <td >200.00</td>
                                        <td >4</td>
                                        <td>2023-01-16</td>
                                        <td>400.00</td>
                                        <td >100.00</td>
                                    
                                    </tr>               
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
@endsection


