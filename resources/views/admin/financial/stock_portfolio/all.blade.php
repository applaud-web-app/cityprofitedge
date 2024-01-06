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
                            <th>@lang('Clinet')</th>
                            <th>@lang('Broker Name')</th>
                            <th>@lang('Stock Name')</th>
                            <th>@lang('Qty')</th>
                            <th>@lang('Buy Date')</th>
                            <th>@lang('Buy Price')</th>
                            <th>@lang('CMP')</th>
                            <th>@lang('Current Value')</th>
                            <th>@lang('Profit/Loss')</th>
                            <th>@lang('Sector')</th>
                            <th>@lang('Pooling Broker Name')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($stockPortfolios as $stockPortfolio)
                                <tr>
                                    <td>
                                        {{ $stockPortfolio->user->fullname }}
                                    </td>
                                    <td>
                                        {{ $stockPortfolio->broker_name }}
                                    </td>
                                    <td>
                                        {{ $stockPortfolio->stock_name }}
                                    </td>
                                    <td>
                                        {{ $stockPortfolio->quantity }}
                                    </td>
                                    <td>
                                        {{ showDateTime($stockPortfolio->buy_date) }}<br>{{ diffForHumans($stockPortfolio->buy_date) }}
                                    </td>
                                    <td>
                                        {{ showAmount($stockPortfolio->buy_price) }}
                                    </td>
                                    <td>
                                        {{ $stockPortfolio->cmp }}
                                    </td>
                                    <td>
                                        {{ showAmount($stockPortfolio->current_value) }}
                                    </td>
                                    <td>{{ $stockPortfolio->profit_loss }}</td>
                                    <td>{{ $stockPortfolio->sector }}</td>
                                    <td>{{ $stockPortfolio->poolingAccountPortfolio->broker_name }}</td>

                                    <td>
                                        <div class="d-flex justify-content-end flex-wrap gap-2">
                                            <a href="{{ route('admin.signal.edit', $stockPortfolio->id) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </a>
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-question="@lang('Are you sure to delete this signal')?"
                                                data-action="{{ route('admin.signal.delete') }}"
                                                data-hidden_id="{{ $stockPortfolio->id }}">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($stockPortfolios->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($stockPortfolios) }}
                </div>
            @endif
        </div>
    </div>
</div>

<x-confirmation-modal />

<!-- Model pop with the form to upload an xls file -->
<div class="modal fade" id="uploadXlsModal" tabindex="-1" role="dialog" aria-labelledby="uploadXlsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadXlsModalLabel">@lang('Upload XLS File')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.financial-overview.stock-portfolio.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="xlsFile">@lang('Select XLS File')</label>
                        <input type="file" class="form-control" id="xlsFile" name="xlsFile" accept=".xlsx, .xls" required>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('Upload')</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@if(!request()->routeIs('admin.financial-overview.stock-portfolio'))
    @push('breadcrumb-plugins')
        {{-- <a href="{{ route('admin.financial-overview.stock-portfolio.add.page') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a> --}}
        <a href="{{ route('admin.financial-overview.stock-portfolio.download.template') }}" class="btn btn-sm btn-outline--primary"><i class="las la-download"></i>@lang('Download Excel Template')</a>
        <button class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#uploadXlsModal"><i class="las la-upload"></i>@lang('Upload via XLS')</button>
    @endpush
@endif
