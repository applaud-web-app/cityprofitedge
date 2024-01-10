@extends($activeTemplate.'layouts.master')

@section('content')
<div class="pt-100 pb-100">
    <div class="container content-container">
        <div class="custom--card">
            <div class="card-body">
                <div class="col-md-12 mb-4">
                    <form action="#" class="transparent-form">
                        <label>@lang('Here is the user details. You can user User Code to login into the system.')</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>@lang('User Code')</label>
                                <div class="input-group">
                                    <input type="text" name="text" class="form-control form--control referralURL"
                                        value="{{ $user->username }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                 <label>@lang('User Email')</label>
                                <div class="input-group">
                                    <input type="text" name="text" class="form-control form--control referralURL"
                                        value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                 <label>@lang('First Name')</label>
                                <div class="input-group">
                                    <input type="text" name="text" class="form-control form--control referralURL"
                                        value="{{ $user->firstname }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                 <label>@lang('Last Name')</label>
                                <div class="input-group">
                                    <input type="text" name="text" class="form-control form--control referralURL"
                                        value="{{ $user->lastname }}" readonly>
                                </div>
                            </div>
                       </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

