@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="pt-100 pb-100">
        <div class="container content-container">

                <form action="" class="transparent-form mb-3">
                    <div class="row">
                        <div class="col-lg-2 form-group">
                            <label>@lang('Symbol Name')</label>
                            <select name="symbol" class="form--control" id="">
                                <option value="">All</option>
                                @foreach ($symbolArr as $v)
                                    <option value="{{$v}}" {{$v==$selSymbol ? 'selected':''}}>{{$v}}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" name="search" value="" class="form--control" placeholder="@lang('Stock Name')"> --}}
                        </div>
                        <div class="col-lg-2 form-group">
                            <label>@lang('From Date')</label>
                            <input type="date" name="from_date" class="form--control" value="{{$fromDate}}" required>
                        </div>
                        <div class="col-lg-2 form-group">
                            <label>@lang('To Date')</label>
                            <input type="date" name="to_date" class="form--control" value="{{$toDate}}" required>
                        </div>
                        <div class="col-lg-3 form-group mt-auto">
                            <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 form-group mt-auto">
                            <a href="{{url('/user/predictions')}}" class="btn btn--base w-100"><i class="las la-redo-alt"></i> @lang('Refresh')</a>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                @forelse ($data as $key=>$itemArr)
                    @foreach($itemArr as $k=>$value)
                       
                            <div class="col-lg-12 mt-3">
                                <div class="custom--card">
                                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                        <h5 class="card-title mb-0" >{{$k}} <span class="text--base">({{date("d-M-Y",strtotime($key))}}) </span></h5>
                                        {{-- <button class="btn btn-sm btn--base py-2" type="submit">Get All <i class="las la-long-arrow-alt-right"></i> </button> --}}
                                
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive--md table-responsive">
                                            <table class="table custom--table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        
                                                        <th>AI/ML Predictions</th>
                                                        <th>Open</th>
                                                        <th>High </th>
                                                        <th>Low</th>
                                                        <th>Close</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($value as $item)
                                                        <tr>
                                                            <td>{{$item->model}}</td>
                                                            <td>{{$item->open}}</td>
                                                            <td>{{$item->high}}</td>
                                                            <td>{{$item->low}}</td>
                                                            <td>{{$item->close}}</td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                                                            
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                    @endforeach

                @empty

                <div class="col-lg-12 mt-3">
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <h4 class="my-5 text-center text-danger">NO DATA</h4>
                        </div>
                    </div>
                </div> 

                @endforelse
            </div>
                


        </div>
    </div>
@endsection
