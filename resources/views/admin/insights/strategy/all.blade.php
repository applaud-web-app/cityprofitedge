@extends('admin.layouts.app')

@section('panel')


<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--lg">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>legs</th>
                                <th>Risk</th>
                                <th>Prof</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <span>Short strategy</span>
                                </td>

                                <td>
                                   abc
                                </td>

                              <td>abc</td>
                              <td>abc</td>

                                <td>
                                    <span class="text--small badge font-weight-normal badge--success">Enabled</span>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end flex-wrap gap-2">
                                        <a href="" class="btn btn-sm btn-outline--primary">
                                            <i class="la la-pencil"></i> Edit </a>
                                        <button class="btn btn-sm btn-outline--danger confirmationBtn">
                                            <i class="la la-trash"></i> Delete </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>

        </div>
    </div>
</div>


@endsection

@if(request()->routeIs('admin.portfolio-insights.strategy.all'))

@push('breadcrumb-plugins')
    <a href="{{ route('admin.portfolio-insights.strategy.add.page') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush
@endif


@push('script')