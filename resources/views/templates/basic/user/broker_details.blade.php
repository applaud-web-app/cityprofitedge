@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container content-container">
       
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="custom--card card">
                  
                    <div class="card-body p-0">
                        <div class="table-responsive--md table-responsive">
                            <table class="table custom--table text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Client Name</th>
                                        <th>Broker Name</th>
                                        <th>Account User Name</th>
                                        <th>Account Password</th>
                                        <th>API Key</th>
                                        <th>API Secret Key</th>
                                        <th>Security PIN</th>
                                        <th>TOTP</th>
                                    </tr>
                                </thead>
                               <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection


