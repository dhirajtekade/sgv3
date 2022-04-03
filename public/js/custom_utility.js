
 $("#loading").hide();//loader hide initially

 //Datatable token table -today's record
 $(function() {
    // let pusher = new Pusher('a0b5950aa40d7ee5e6d1', {
    //     cluster: 'ap2',
    //     encrypted: true,
    // });

    // let checkouttoastr = pusher.subscribe("checkout-toastr");

    // checkouttoastr.bind('checkouttoastr', function(data) {
    //     console.log(data);
    //     toastr.success("<strong>"+data['data'].user+"</strong> has checkout for token no.: "+data['data'].tokenDeleted);
    // });


    var table = $('#token_result_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: sglist_Url,
        columns: [
            {
                data: 'token_no',
                name: 'token_no'
            },

            {
                data: 'mht_id',
                name: 'mht_id'
            },
            // {
            //     data: 'name',
            //     name: 'name'
            // },
            // {
                //     data: 'whatsapp_no',
            //     name: 'whatsapp_no'
            // },
            {
                data: 'alternate_no',
                name: 'alternate_no'
            },
            // {
            //     data: 'no_luggage',
            //     name: 'no_luggage'
            // },
            // {
            //     data: 'center_name',
            //     name: 'center_name'
            // },
            // {
            //     data: 'sr',
            //     name: 'sr'
            // },
            // {
                //     data: 'city',
                //     name: 'city'
                // },
                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // },
            ]
    });

    // $(".no_luggage").addClass('numericCheck');

});

//edit search mht id record
$(document).on("click",".EditMhtButton",function(e) {
    var id = $(this).attr('id');//EditMhtButton_420
    var searchRecordId = id.replace('EditMhtButton_',''); //420

    //hide search_result_table_td_span
    $("#search_result_table_tr_"+searchRecordId).find(".search_result_table_td_span").toggle();
    //show search_result_table_td_input
    $("#search_result_table_tr_"+searchRecordId).find(".search_result_table_td_input").toggle();

    //just change button text - not needed as of now

})

//open mmodal form - Add New Person modal open
$(document).on("click","#openaddNewModal",function(e) {
    //clear all form input
    $("#name").val(''); $("#fname").val(''); $("#mname").val(''); $("#lname").val('');
    $("#alternate_no").val('');
    $("#center_name").val('');
    $("#mht_id").val('');
    $("#alternate_no").val('');
    $("#no_luggage").val('');$('#no_luggage').removeClass("errorClass");
    $(".addNewPrint").attr('disabled', true);

    setTimeout(function() { $('input[name="alternate_no"]').focus() }, 1000);
    $("#addNewModal").modal('show');
})

//Enable Print button when luggage entered in token_result list
$(document).on('keyup','.no_luggage',function(e){
    var id = $(this).attr('id');
    var sr = id.split('_')[1];//indesx

    var value = $(this).val();
    if(value != '') {
        //enable print button
        $("#printId_"+sr).attr('disabled', false);
    } else {
        $("#printId_"+sr).attr('disabled', true);
    }
});

//Enable Print button when luggage entered in Add new person modal
$(document).on('keyup','#no_luggage',function(e){
    var value = $(this).val();
    if(value != '') {
        //enable print button
        $(".addNewPrint").attr('disabled', false);
        $('#no_luggage').removeClass("errorClass");
    } else {
        $('#no_luggage').addClass("errorClass");
        $(".addNewPrint").attr('disabled', true);
    }
})

//allow(Mask) only numbers in numerCheck css class fields
$(document).on("keypress", ".numericCheck", function(e){
    var keyCode = e.which ? e.which : e.keyCode
    if (!(keyCode >= 48 && keyCode <= 57)) {
      $(".error").css("display", "inline");
      return false;
    }else{
      $(".error").css("display", "none");
    }
  })
