@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-5">
                <form action="{{url('admin/package/store-fibonaci-variables')}}" method="post" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="percentage_one">First (In percent)</label>
                                <input type="text" name="percentage_one" id="percentage_one" class="form-control" value="{{$percentData->percentage_one}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="percentage_two">Second (In percent)</label>
                                <input type="text" name="percentage_two" id="percentage_two" class="form-control" value="{{$percentData->percentage_two}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="percentage_three">Third (In percent)</label>
                                <input type="text" name="percentage_three" id="percentage_three" class="form-control" value="{{$percentData->percentage_three}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>

@endsection

