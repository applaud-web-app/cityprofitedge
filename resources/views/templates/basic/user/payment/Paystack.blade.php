@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container content-container pt-100 pb-100">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Paystack')</h5>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                        @csrf
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                @lang('You have to pay')
                                <strong>{{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</strong>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                @lang('You will get')
                                <strong>{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</strong>
                            </li>
                        </ul>
                        <button type="button" class="btn btn--base w-100 mt-4" id="btn-confirm">@lang('Pay Now')</button>
                        <script
                            src="//js.paystack.co/v1/inline.js"
                            data-key="{{ $data->key }}"
                            data-email="{{ $data->email }}"
                            data-amount="{{ round($data->amount) }}"
                            data-currency="{{$data->currency}}"
                            data-ref="{{ $data->ref }}"
                            data-custom-button="btn-confirm"
                        >
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
