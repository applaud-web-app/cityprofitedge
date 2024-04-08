@extends('admin.layouts.app')

@section('panel')
  <!-- include summernote css/js -->
  
     
            <div>
                <form action="{{ route('admin.portfolio-insights.top-losers.add.submit')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 mt-xl-0">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xxl-12">
                                            <div class="form-group">
                                                <label>@lang('Strategy Name')</label>
                                                <input type="text" class="form-control" name="stock_name" required value="">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>@lang('legs')</label>
                                                <input type="text" class="form-control" name="legs" required value="">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>@lang('Risk')</label>
                                                <input type="text" class="form-control" name="cmp" required value="">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>@lang('Prof.')</label>
                                                <input type="text" class="form-control" name="change_percentage" required value="">
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>@lang('Image.')</label>
                                                <input type="file" class="form-control" name="image" required value="">
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>@lang('Type')</label>
                                                <select name="" class="form-control" id="" required>
                                                    <option value="">@lang('Bullish')</option>
                                                    <option value="">@lang('Bearish')</option>
                                                    <option value="">@lang('Volatile')</option>
                                                    <option value="">@lang('Oscillate')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>@lang('Status')</label>
                                                <select name="" class="form-control" id="" required>
                                                    <option value="">@lang('Enable')</option>
                                                    <option value="">@lang('Disable')</option>
                                                   
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                           
                                            <div class="form-group">
                                                <label>@lang('Description')</label>
                                                <textarea class="form-control" name="change_percentage" id="summernote" required value="" ></textarea>
                                            </div>

                                        </div>
                                        <div class="col-xxl-12 mt-3 border-top pt-4">
                                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
  
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.portfolio-insights.strategy.all') }}" />
    
@endpush

@push('script')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
  $('#summernote').summernote({ height: 250});
});
</script>
@endpush


