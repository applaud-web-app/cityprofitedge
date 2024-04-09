@extends($activeTemplate.'layouts.master')

@section('content')
<div class="pt-100 pb-100 statergy-area">
    <div class="container content-container">
        <div class="section-header  wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s" >
            <h2 class="section-title">@isset($data->strategy_name) {{$data->strategy_name}} @endisset Strategy</h2>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="details-img">
                    <img src="{{asset('assets/images/strategy/'.$data->strategy_image.'')}}" alt="" class="img-fluid w-100 border">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="stratergy-details-description">
                    <h4>Strategy</h4>
                    <p>@isset($data->strategy_name){{$data->strategy_name}}@endisset</p>
                    <h4>Strategy Legs</h4>
                    <p>@isset($data->legs){{$data->legs}}@endisset</p>
                    <h4>Strategy Risk</h4>
                    <p>@isset($data->risk){{$data->risk}}@endisset</p>
                    <h4>Profit Percent</h4>
                    <p>@isset($data->profit){{$data->profit}}@endisset</p>
                    <h4>Strategy Type</h4>
                    <p>@isset($data->market_trend){{$data->market_trend}}@endisset</p>
                    <h4>About Strategy</h4>
                    <p>@isset($data->description){!! trim($data->description, 'firstlast') !!}@endisset</p>
                </div>
            </div>
        </div>

        <hr>
        <div class="row">
            @if(!$related->isEmpty())
                <div class="col-lg-12 col-md-12 col-12">
                    <h4 class="text-decoration-underline mb-3">Related Stratergies</h4>
                    <div class="d-flex flex-wrap">
                            @foreach ($related as $similar)
                                <a href="{{route('user.stratergyDetails',$similar->id)}}" class="badge bg-light px-3 py-1  text-dark fw-light me-1 mb-1">{{$similar->strategy_name}}</a>
                            @endforeach
                    </div>
                </div>
            @endif
        </div>
    
    </div>
</div>
@endsection

