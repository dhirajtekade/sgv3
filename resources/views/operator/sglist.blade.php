@extends('layouts.app')

@section('content')

<div class="container">
    <div class="top_floor border border-secondary">
    <div class="row px-3 m-2">
        {{-- <div class="col-2">
            <button class="btn btn-warning mr-2" data-toggle="modal" id="openaddNewModal" ><i class="fa fa-solid fa-plus"></i> Add as a Mht</button>
        </div> --}}
        <div class="col-2 mr-2">
            <input type="text" id="search_by_any" class="search_mht_by" placeholder=" Search by Any" value="">
        </div>
        <div class="col-2">
            <input type="text" id="search_by_mobile" class="search_mht_by" placeholder=" Search by Mobile" value="">
        </div>
        <div class="col-2">
            <input type="text" id="search_by_mhtid" class="search_mht_by" placeholder=" Search by Mhtid" value="" autofocus>
        </div>


        <div class="col-6 group today_total_status">
            @if(isset($getTodayStatusData) && count($getTodayStatusData) > 0)
            <span class="total_bags_checkout ">, Total checkout: <span class="total_bags_checkout_html font-weight-bold text-primary">{{$getTodayStatusData['total_bags_checkout']}}</span></span>
            <span class="total_bags_checkin">, Bags remain:  <span class="total_bags_checkin_html font-weight-bold text-primary">{{$getTodayStatusData['total_bags_checkin']}}</span></span>
            <span class="total_bags_count "> Total checkin:  <span class="total_bags_count_html font-weight-bold text-primary">{{$getTodayStatusData['total_bags_count']}}</span></span>
            @endif
        </div>
    </div>



    <div class="row mt-2 px-3">
        <!-- this table will be display only when search is in progress or done after its action perform we can hide it-->
        <div class="col search_result">
            {{-- <h2>Search Result</h2> --}}
            <table class="table table-bordered data-table" id="search_result_table">
                {{-- <thead>
                    <tr>
                        <th>Token No.</th>
                        <th>Bags No.</th>
                        <th>Mht Id</th>
                        <th>Mobile No.</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead> --}}
                <tbody class="search_result_table_tbody">
                    <tr class="default_new search_result_table_tr search_result_table_tr_0">
                        <td class="search_result_table_td_mhtid">Mhtdvalue</td>
                        <td><input type="text" name="alternate_no" class="alternate_no numericCheck w-75" id="alternate_no_0" size="10" placeholder="Mobile Number"></td>
                        <td><input type="text" name="no_luggage" class="no_luggage numericCheck" id="noluggage_0" size="5" placeholder="Bags"></td>
                        <td>
                            <button class="printButton btn btn-primary btn-sm mr-2" id="printButton_0" disabled>Print</button>
                            {{-- <a  class="checkoutButton btn btn-success btn-sm mr-2" id="checkoutId_0" disabled>Checkout</a> --}}
                            {{-- <button class="editButton btn btn-info btn-sm" id="editId_" disabled>Edit</button> --}}
                            {{-- <a href="" class="sendMsgButton btn btn-info btn-sm mr-2" id="sendId_" style="display:none;">Send Msg</a> --}}
                            {{-- <a  class="partialcheckoutButton btn btn-info btn-sm mr-2" id="partialcheckoutId_">Partial Checkout</a> --}}
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
    </div>

    <br>
    <hr>
    <div class="second_floor">
    <h2 class="token_result">Today's Record</h2>
    <div class="row mt-2 ">
        <div class="col token_result">
            <table class="table table-bordered data-table" id="token_result_table">
                <thead>
                    <tr>
                        <th>Token No.</th>
                        <th>Mht Id</th>
                        <th>Mobile No.</th>
                        {{-- <th>Bags No.</th> --}}
                        {{-- <th width="100px">Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                </tbody>

            </table>
        </div>
    </div>
    </div>




    {{-- <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">New message</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form>
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Recipient:</label>
                  <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Message:</label>
                  <textarea class="form-control" id="message-text"></textarea>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Send message</button>
            </div>
          </div>
        </div>
      </div> --}}



    <!-- pop up modal = addNewModal-->
    <div class="modal fade bd-example-modal-lg " id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title font-weight-bold" id="addNewModalLabel">Add New Record</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="addNewModalForm">
                    <div class="form-group form-check-inline w-100">

                      <label for="alternate_no" class="col-form-label w-50  mr-1 text-right font-weight-bold">Mobile Number:</label>
                        <input type="text" class="form-control numericCheck" id="alternate_no" name="alternate_no" maxlength="10" size="10" placeholder="Whatsapp number">

                    </div>

                    <div class="form-group form-check-inline w-100">
                      <label for="name" class="col-form-label"></label>
                      <input type="text" class="form-control text-capitalize" id="fname" name="fname" placeholder="First Name">
                      <input type="text" class="form-control text-capitalize" id="mname" name="mname" placeholder="Middle Name">
                      <input type="text" class="form-control text-capitalize " id="lname" name="lname" placeholder="Last Name">
                    </div>
                    <div class="form-group  form-check-inline w-100 ">
                        {{-- <label for="whatsapp_no" class="col-form-label w-25 pr-2">Whatsapp Number:</label>
                        <input type="text" class="form-control numericCheck" id="whatsapp_no" name="whatsapp_no" maxlength="10" data-bind="value:replyNumber"> --}}

                        {{-- <label for="alternate_no" class="col-form-label w-25  p-1 text-right">Alternate Number:</label>
                        <input type="text" class="form-control numericCheck" id="alternate_no" name="alternate_no" maxlength="10"> --}}
                    </div>
                    <div class="form-group  form-check-inline w-100">
                        <label for="center_name" class="col-form-label mr-1 font-weight-bold">Center:</label>
                        <input type="text" class="form-control text-capitalize " id="center_name" name="center_name" placeholder="Center-city">

                        {{-- <label for="city" class="col-form-label  w-25 p-1 text-right">City:</label>
                        <input type="text" class="form-control text-capitalize" id="city" name="city"> --}}

                        <label for="no_luggage" class="col-form-label w-25 p-1 text-right font-weight-bold">Bags*:</label>
                        <input type="text" class="form-control numericCheck" id="no_luggage" name="no_luggage" placeholder="Lugguage Count">
                    </div>
                    <div class="form-group error-message text-danger"></div>


                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary addNewPrint"><i class="fas fa-print"></i> Print</button>
                      </div>
                  </form>
              {{-- <form action="" id="addNewModalForm">
                <div class="form-group form-check-inline w-100">

                    <label for="mht_id" class="col-form-label mr-1 font-weight-bold">MhtId:</label>
                    <input type="text" class="form-control mht_id_foucs_first" id="mht_id" name="mht_id"  maxlength="8"  autofocus>

                    <label for="alternate_no" class="col-form-label text-right font-weight-bold">Mobile Number:</label>
                    <input type="text" class="form-control numericCheck" id="alternate_no" name="alternate_no" maxlength="10" size="10">
                    <label for="no_luggage" class="col-form-label text-right font-weight-bold">Bags*:</label>
                    <input type="text" class="form-control numericCheck" id="no_luggage" name="no_luggage">
                </div>

                <div class="form-group error-message text-danger"></div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary addNewPrint"><i class="fas fa-print"></i> Print</button>
                  </div>
              </form> --}}
            </div>

          </div>
        </div>
    </div>

</div>
<script src="{{ asset('js/sgcustom.js') }}" defer></script>
<script src="{{ asset('js/custom_utility.js') }}" defer></script>
<script type="text/javascript">
    $("#loading").hide();
        var addNewMht_Url = "{{ route('addNewMht') }}";
        var updateMht_Url = "{{ route('updateMht') }}";
        var sglist_Url = "{{ route('sglist') }}";
        var searchResult_Url = "{{ route('searchResult') }}";
        var checkout_Url = "{{ route('checkout') }}";
        // var checkout_Url = "{{ route('checkout') }}";
        var generateFinalPrint_Url = "{{ route('generateFinalPrint') }}";

    </script>

@endsection
