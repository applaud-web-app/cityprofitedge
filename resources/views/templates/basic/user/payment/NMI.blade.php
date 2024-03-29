@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container content-container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="custom--card card-deposit">
                        <div class="card-header">
                            <h5 class="card-title text-center">@lang('NMI Payment')</h5>
                        </div>
                        <div class="card-body card-body-deposit">
                            <form role="form" class="transparent-form" id="payment-form" method="{{ $data->method }}" action="{{ $data->url }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">@lang('Card Number')</label>
                                        <div class="input-group">
                                            <input type="tel" class="custom-input form--control" name="billing-cc-number" autocomplete="off" value="{{ old('billing-cc-number') }}" required autofocus />
                                            <span class="input-group-text bg--base text-white border-0"><i class="fa fa-credit-card"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('Expiration Date')</label>
                                        <input type="tel" class="custom-input form--control" name="billing-cc-exp" value="{{ old('billing-cc-exp') }}" placeholder="e.g. MM/YY" autocomplete="off" required />
                                    </div>
                                    <div class="col-md-6 ">
                                        <label class="form-label">@lang('CVC Code')</label>
                                        <input type="tel" class="custom-input form--control" name="billing-cc-cvv" value="{{ old('billing-cc-cvv') }}" autocomplete="off" required />
                                    </div>
                                </div>
                                <br>
                                <button class="btn btn--base w-100" type="submit"> @lang('Submit')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
