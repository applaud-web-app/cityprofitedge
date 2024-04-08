@extends($activeTemplate.'layouts.master')

@section('content')
<div class="pt-100 pb-100 statergy-area">
    <div class="container content-container">
        <div class="row g-3">
            @for ($a = 0; $a < 5; $a++)
            <div class="col-lg-3 col-md-4 col-12">
                <div class="statergy-pannel">
                    <div class="statergy-pannel-header">
                        <h5>@lang('Statergy')</h5>
                        <i class="las la-arrow-down"></i>
                    </div>
                    <div class="statergy-pannel-body">
                        <div class="row g-1">

                            @for ($i = 0; $i < 10; $i++)
                                <div class="col-lg-6 col-md-6 col-12">
                                    <a href="{{ url('user/stratergies-details') }}" class="startery-card">
                                     
                                            <img src="https://www.quantsapp.com/assets/icons/chart-image/chart-short-call-option-strategy.webp" alt="" class="img-fluid statergy-img">
                                            <h6 class="text--base">statregies title</h6>

                                            <ul class="list-group list-group-flush">
                                                <li class="d-flex justify-content-between"><span>@lang('legs')</span><span>@lang('-')</span"></li>
                                                <li class="d-flex justify-content-between"><span>@lang('Risk')</span><span>@lang('-')</span"></li>
                                                <li class="d-flex justify-content-between"><span>@lang('Prof.')</span><span>@lang('-')</span"></li>
                                            </ul>
                                      
                                        </a>
                                </div>
                            @endfor
                          
                        </div>
                    </div>
                </div>
            </div>
            @endfor
           
           
        </div>

    
    </div>
</div>
@endsection

