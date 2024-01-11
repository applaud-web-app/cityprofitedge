@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i>
                Filter</button>
        </div>
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <form action="#">
                    <div class="d-flex flex-wrap gap-3">
                       
                        <div class="flex-grow-1">
                            <label>Symbol Name</label>
                            <select name="trx_type" class="form-control" id="trx_type">
                                <option value="">Option 1</option>
                                <option value="">Option 2</option>
                                <option value="">Option 3</option>
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label>Time Frame</label>
                            <select class="form-control" name="remark" id="remark">
                                <option value="">Option 1</option>
                                <option value="">Option 2</option>
                                <option value="">Option 3</option>
                            </select>
                        </div>
                       
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>CE</th>
                                <th>PC</th>
                                <th>VMAP CE Signal</th>
                                <th>VMAP PE Signal</th>
                                <th>CE_Con Signal</th>
                                <th>CE_Con Signal</th>
                                <th>BUY_Action</th>
                                <th>SELL_Action</th>
                                <th>Strategy Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i < 15; $i++)
                            <tr>
                                <td>{{$i}}</td>
                                <td>2023-02-10</td>
                                <td>12:24 PM</td>
                                <td>ADMINUYTR4568</td>
                                <td>ADMINUYTR4568</td>
                                <td>Bearish</td>
                                <td>Bearish</td>
                                <td>Bearish</td>
                                <td>Bearish</td>
                                <td>Hold</td>
                                <td>Hold</td>
                                <td>Reoccuring</td>
                            </tr>
                            @endfor
                           

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            <div class="card-footer py-4">
                <nav class="d-flex justify-content-end">
                    <ul class="pagination">

                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                            <span class="page-link" aria-hidden="true"><</span>
                        </li>





                        <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link"
                                href="">2</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                href="">3</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                href="">4</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                href="">5</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                href="">6</a>
                        </li>
                  

                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>

                        <li class="page-item"><a class="page-link"
                                href="8">18</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                href="9">19</a>
                        </li>


                        <li class="page-item">
                            <a class="page-link"
                                href=""
                                rel="next" aria-label="Next »">></a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection

@push('breadcrumb-plugins')

@endpush