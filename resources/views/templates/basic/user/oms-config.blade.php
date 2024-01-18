@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
<section class="pt-100 pb-100">
    <div class="container content-container">
        <div class="text-end">
            <button class="btn btn--base" type="submit"><i class="las la-plus"></i> @lang('Add New Row')</button>
        </div>
        
       
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md table-responsive transparent-form">
                            <table class="table custom--table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Symbol Name</th>
                                        <th>Signal TF</th>
                                        <th>TXN Type</th>
                                        <th>Entry Poit</th>
                                        <th>Pyramid1</th>
                                        <th>Pyramid2</th>
                                        <th>Pyramid3</th>
                                        <th>Pyramid %</th>
                                        <th>Order Type</th>
                                        <th>Product</th>
                                        <th>Pyramid Freq</th>
                                        <th>CE Symbol Name</th>
                                        <th>CE Qty</th>
                                        <th>PE Symbol Name</th>
                                        <th>PE Qty</th>
                                        <th>Client Name</th>
                                        <th>Strategy Name</th>
                                        <th>Exit1 Qty</th>
                                        <th>Exit2 Qty</th>
                                        <th>Exit1 Target</th>
                                        <th>Exit2 Target</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 10; $i++)
                                    <tr>
                                      <td>
                                         <div class="form-group mb-0">
                                              <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                         </div>
                                      </td>
                                      <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                          <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                          <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                <option value="">Select</option>
                                                <option value="">1</option>
                                                <option value="">2</option>
                                                <option value="">3</option>
                                                <option value="">4</option>
                                            </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                           <input type="text" class="form--control" placeholder="Enter Text">
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                            <select name="" class="form--control" id="">
                                                  <option value="">Select</option>
                                                  <option value="">1</option>
                                                  <option value="">2</option>
                                                  <option value="">3</option>
                                                  <option value="">4</option>
                                              </select>
                                        </div>
                                     </td>

                                     <td>
                                        <div class="form-group mb-0">
                                          <button class="btn btn-sm btn--base deplay_btn" type="submit">@lang('deploy')</button>
                                        </div>
                                     </td>

                                     



                                      
                                       
                                    
                                    </tr>    
                                    @endfor
                                             
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 justify-content-center d-flex">
           {{-- pagination links --}}
        </div>
    </div>
</section>

@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
  

    $("#dates_range").daterangepicker({
        autoUpdateInput: false,
        minYear: 1901,
        showDropdowns: true,
      
       
    }).on("apply.daterangepicker", function (e, picker) {
        picker.element.val(picker.startDate.format(picker.locale.format) + " - " + picker.endDate.format(picker.locale.format));
    });



</script>
@endpush
@endsection


