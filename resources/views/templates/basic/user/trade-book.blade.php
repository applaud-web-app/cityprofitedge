@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="#" class="transparent-form mb-3">
            <div class="row">
                <div class="col-lg-3 form-group">
                    <label>@lang('Stock Name')</label>
                    <input type="text" name="search" value="{{ request()->search }}" class="form--control" placeholder="@lang('Stock Name')">
                </div>
                {{-- <div class="col-lg-3 form-group">
                    <label>@lang('Type')</label>
                    <select name="type" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="+" @selected(request()->type == '+')>@lang('Profit')</option>
                        <option value="-" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div> --}}
                <div class="col-lg-3 form-group mt-auto">
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
                                        <th>Reco Date</th>
                                        <th>Buy Price</th>
                                        <th>CMP</th>
                                        <th>PNL</th>
                                        <th>Sector</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                                        <tr>
                                        <td data-label="STOCK NAME">
                                            seventh stock
                                        </td>
                                        <td data-label="RECO DATE">
                                            2027-02-24
                                        </td>
                                        <td data-label="BUY PRICE">
                                            98,789.00
                                        </td>
                                        <td data-label="CMP">
                                            897.00
                                        </td>
                                        <td data-label="PNL">
                                            87987.00
                                        </td>
                                        <td data-label="SECTOR">fourth test</td>
                                    </tr>
                                                                    <tr>
                                        <td data-label="STOCK NAME">
                                            sixth stock
                                        </td>
                                        <td data-label="RECO DATE">
                                            2023-08-24
                                        </td>
                                        <td data-label="BUY PRICE">
                                            23,445.00
                                        </td>
                                        <td data-label="CMP">
                                            687687.00
                                        </td>
                                        <td data-label="PNL">
                                            7767.00
                                        </td>
                                        <td data-label="SECTOR">test third</td>
                                    </tr>
                                                                    <tr>
                                        <td data-label="STOCK NAME">
                                            Fifth Stock
                                        </td>
                                        <td data-label="RECO DATE">
                                            2021-01-24
                                        </td>
                                        <td data-label="BUY PRICE">
                                            45,442.00
                                        </td>
                                        <td data-label="CMP">
                                            67656.00
                                        </td>
                                        <td data-label="PNL">
                                            455.00
                                        </td>
                                        <td data-label="SECTOR">Sec two</td>
                                    </tr>
                                                                    <tr>
                                        <td data-label="STOCK NAME">
                                            Fourth Stock
                                        </td>
                                        <td data-label="RECO DATE">
                                            2023-02-24
                                        </td>
                                        <td data-label="BUY PRICE">
                                            65,777.00
                                        </td>
                                        <td data-label="CMP">
                                            6567.00
                                        </td>
                                        <td data-label="PNL">
                                            1223.00
                                        </td>
                                        <td data-label="SECTOR">Sec one</td>
                                    </tr>
                                                                    <tr>
                                        <td data-label="STOCK NAME">
                                            third stock name
                                        </td>
                                        <td data-label="RECO DATE">
                                            2024-01-01
                                        </td>
                                        <td data-label="BUY PRICE">
                                            1,200.00
                                        </td>
                                        <td data-label="CMP">
                                            3000.00
                                        </td>
                                        <td data-label="PNL">
                                            0.00
                                        </td>
                                        <td data-label="SECTOR">8934798</td>
                                    </tr>
                                                                    <tr>
                                        <td data-label="STOCK NAME">
                                            second test sotck
                                        </td>
                                        <td data-label="RECO DATE">
                                            2024-01-31
                                        </td>
                                        <td data-label="BUY PRICE">
                                            1,200.00
                                        </td>
                                        <td data-label="CMP">
                                            0.00
                                        </td>
                                        <td data-label="PNL">
                                            8098.00
                                        </td>
                                        <td data-label="SECTOR">ljaslkdfjlksdjf</td>
                                    </tr>
                                                                    <tr>
                                        <td data-label="STOCK NAME">
                                            first rest stock
                                        </td>
                                        <td data-label="RECO DATE">
                                            2024-01-05
                                        </td>
                                        <td data-label="BUY PRICE">
                                            1,200.00
                                        </td>
                                        <td data-label="CMP">
                                            0.00
                                        </td>
                                        <td data-label="PNL">
                                            0.00
                                        </td>
                                        <td data-label="SECTOR">987sdf</td>
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


