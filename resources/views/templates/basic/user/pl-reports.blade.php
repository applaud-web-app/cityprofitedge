@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
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
                    <label>@lang('Dates')</label>
                    <input type="text" name="search" id="dates_range" value="" class="form--control" placeholder="Choose Date">
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
        <div class="row g-3">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Realised PNL')</h5>
                        <h2 class="text--base">@lang('14512')</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Charges & Taxes')</h5>
                        <h2 class="text-light">@lang('569')</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Other Credits & Debits')</h5>
                        <h2 class="text-light">@lang('895')</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Not Realised PNL')</h5>
                        <h2 class="text--base">@lang('14512')</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
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
                                    @for ($i = 0; $i < 25; $i++)
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

@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    dates_range
    $('#dates_range').daterangepicker();

</script>
@endpush
@endsection


