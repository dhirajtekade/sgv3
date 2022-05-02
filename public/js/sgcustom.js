
var Timer;
var search_mht_byId = 'search_by_mhtid';
function Start() {
    $('.search_mht_by').keyup(function () {
        search_mht_byId = $(this).attr('id');
        clearTimeout(Timer);
        Timer = setTimeout(SendRequest, 1000);
    });
}

function SendRequest() {
    var scannedcode = $("#"+search_mht_byId).val();
    //search data for entered mhtid
    if(scannedcode){
        search_mht_in_db();
        // $("#search_by_mhtid").val('');
    } else {
        var mobile = $("#search_by_mobile").val();
        var any = $("#search_by_any").val();
        var mhtid = $("#search_by_mhtid").val();

        if(mobile == '' && any == '' && mhtid == ''){
            $(".search_result").hide();
        }
    }
}
$(Start);


// $(document).on("keyup", '.search_mht_by', function(e) {
//     search_mht_in_db()
// })
function search_mht_in_db() {
        var mobile = $("#search_by_mobile").val();
        var any = $("#search_by_any").val();
        var mhtid = $("#search_by_mhtid").val();
        $(".search_result").show();
        // e.preventDefault();
        $("#loading").show();

        $.ajax({
            url:searchResult_Url,
            type: 'get',
            dataType: 'json',
            data: {
                mhtid: mhtid,
                mobile:mobile,
                any:any
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $("#loading").hide();
                // console.log(data);
                //clear previous records
                $(".search_result_table_tr").remove();
               // $(".search_result_table_tr").hide();
                //$(".search_result_table_td_input").hide();
                //$(".search_result_table_td_span").show();
                var trhtml = '';
                if(data != '' && data != -1) {
                    //hide default search tr
                    $(".default_new").hide();
                    //create tr html

                    $.each( data, function( key, value ) {
                        trhtml += '<tr class="search_result_table_tr not_default_search" id="search_result_table_tr_'+value.id+'">';
                        trhtml += '<td class="search_result_table_td_mhtid">'+value.mht_id+'</td>';
                        if($.trim(value.name) != ''){
                            trhtml += '<td class="search_result_table_td_name"><span class="search_result_table_td_span">'+value.name+'</span>';
                        } else {
                            trhtml += '<td class="search_result_table_td_name"><span class="search_result_table_td_span"><em>(Click Edit button to update with Mht Name)</em></span>';
                        }
                        trhtml += '<span class="search_result_table_td_input"><input value="'+value.name+'" type="text" name="name" class="name  w-75" id="name_'+value.id+'" size="10" placeholder="Full Name"></span></td>';
                        trhtml += '<td class="search_result_table_td_alternate_no"><span class="search_result_table_td_span">'+value.alternate_no+'</span>';
                        trhtml += '<span class="search_result_table_td_input"><input value="'+value.alternate_no+'" type="text" name="alternate_no" class="alternate_no numericCheck w-75" id="alternate_no_'+value.id+'" size="10" placeholder="Mobile Number" ></span></td>';
                        trhtml += '<td><input value="" type="text" name="no_luggage" class="no_luggage numericCheck" id="noluggage_'+value.id+'" size="5" placeholder="Bags">';
                        if(value.total_bags > 0){
                            trhtml += '<span class="mr-2" id="noluggageplus_'+value.id+'"> +'+value.total_bags+'</span>';
                        }

                        trhtml += '</td>';
                        // trhtml += '<td><input value="no of luggage if any" type="text" name="no_luggage" class="no_luggage numericCheck" id="noluggage_0" size="5" placeholder="Bags"></td>';
                        trhtml += '<td>';
                        trhtml += '<button class="printButton btn btn-primary btn-sm mr-2" id="printButton_'+value.id+'" disabled>Print</button>';
                        trhtml += '<a class="checkoutButton btn btn-success btn-sm mr-2" id="checkoutButton_'+value.id+'" disabled>Checkout</a>';
                        trhtml += '<a class="EditMhtButton btn btn-info btn-sm mr-2" id="EditMhtButton_'+value.id+'">Edit</a>';
                        trhtml += '</td>';
                        trhtml += '</tr>';
                     });
                    $(".search_result_table_tbody").append(trhtml);

                } else if(data != -1) {
                    //show default search tr
                    // alert(66)
                   // $(".default_new").show();

                   trhtml += '<tr class="default_new search_result_table_tr" id="search_result_table_tr_0">';
                   trhtml += '<td class="search_result_table_td_mhtid">Mhtdvalue</td>';
                   trhtml += '<td><input type="text" name="alternate_no" class="alternate_no numericCheck w-75" id="alternate_no_0" size="10" placeholder="Mobile Number"></td>';
                   trhtml += '<td><input type="text" name="no_luggage" class="no_luggage numericCheck" id="noluggage_0" size="5" placeholder="Bags"></td>';
                   trhtml += '<td>';
                   trhtml += '<button class="printButton btn btn-primary btn-sm mr-2" id="printButton_0" disabled>Print</button>';
                   trhtml += '</td>';
                   trhtml += '</tr>';

                    $(".search_result_table_tbody").append(trhtml);
                    $(".search_result_table_td_mhtid").html(mhtid);

                    //still allow to write number and bag
                } else { //hide all search tr
                    $(".search_result_table_tr").remove();

                }
            },
            error: function(xhr, textStatus, errorThrown){
                $("#loading").hide();
                $(".addNewPrint").attr('disabled', false);
                console.log(xhr);
                console.log(textStatus);
                console.log(errorThrown);
                var message = xhr.responseJSON.message;
                if(message == 'Unauthenticated.'){
                    alert('User got logged out. You need to login again.');
                    location.reload();
                } else {
                    alert('Bhogave Eni Bhul! Some error occured. Try Reentering data or Reload the page!');
                }

             }
        });
}


//adding new mht not having mht id yet
$(document).on("click",".addNewPrint",function(e) {
    e.preventDefault();
    $("#loading").show();
    $(".addNewPrint").attr('disabled', true);

    $.ajax({
        url: addNewMht_Url,
        type: 'post',
        dataType: 'json',
        data: $('form#addNewModalForm').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            $("#loading").hide();
            console.log(data);
            // alert('check console');
            // if(data.statusCode == 200){
            //     $(".addNewPrint").attr('disabled', false);
            //     //close modal
            //     $("#addNewModal").modal('hide');
            //     var detail = data.data;
            //     console.log(detail);
            //     window.open(
            //         // generateFinalPrint_Url+'?l='+data.no_luggage+'&t='+data.token_no+'&qr='+data.qr+'&h='+data.have_mobile,
            //         generateFinalPrint_Url+'?s_id='+detail.s_mht_id+'&l='+detail.no_luggage+'&t='+detail.token_no,
            //         '_blank' // <- This is what makes it open in a new window.
            //       );
            // } else {
            //     console.log(data);
            //     if(data.data) {
            //         $.each( data.data, function( key, value ) {
            //             alert( key + ": " + value );
            //            $(".error-message").html(value);
            //            // $("#"+key).css();
            //          });
            //     }
            // }
        },
        error: function(xhr, textStatus, errorThrown){
            $("#loading").hide();
            $(".addNewPrint").attr('disabled', false);
            console.log(xhr);
            console.log(textStatus);
            console.log(errorThrown);
            alert('Some error occured! Try Reentering data or Reload the page!');

         }
    });

});

//
/**
 * creating token from mhtids
 * mhtid can be exist in the system or may not be also
 * if mhtid exist then add new tokens against him
 * if mhtid not exists then add new mht entry and assign tokens against him
 */
$(document).on("click",".printButton",function(e) {
    //basic prior operations
    e.preventDefault();
   // $("#loading").show();
    $(".printButton").attr('disabled', true);

    //get id of clicked printButton record
    var id = $(this).attr('id');
    var sr = id.split('_')[1];//indesx

    // search_result_table_tr_1

    var mht_id = $("#search_result_table_tr_"+sr).find('.search_result_table_td_mhtid').html();

    var no_luggage = $("#noluggage_"+sr).val();
    var alternate_no = $("#alternate_no_"+sr).val();

    $.ajax({
        url: addNewMht_Url,
        type: 'post',
        dataType: 'json',
        data: {
            mht_id:mht_id,
            no_luggage:no_luggage,
            alternate_no:alternate_no
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            $("#loading").hide();
            console.log(data);
            // alert('check console');
            if(data.statusCode == 200){
                $(".addNewPrint").attr('disabled', false);
                //close modal
                $("#addNewModal").modal('hide');
                var detail = data.data;
                console.log(detail);
                window.open(
                    // generateFinalPrint_Url+'?l='+data.no_luggage+'&t='+data.token_no+'&qr='+data.qr+'&h='+data.have_mobile,
                    generateFinalPrint_Url+'?s_id='+detail.mht_id+'&l='+detail.no_luggage+'&t='+detail.token_no+'&b='+detail.bags_no,
                    '_blank' // <- This is what makes it open in a new window.
                  );
            } else {
                console.log(data);
                if(data.data) {
                    $.each( data.data, function( key, value ) {
                        alert( key + ": " + value );
                       $(".error-message").html(value);
                       // $("#"+key).css();
                     });
                }
            }
        },
        error: function(xhr, textStatus, errorThrown){
            $("#loading").hide();
            $(".printButton").attr('disabled', false);
            console.log(xhr);
            console.log(textStatus);
            console.log(errorThrown);
            alert('Some error occured! Try Reentering data or Reload the page!');

         }
    });
})



$(document).on('click','.checkoutButton',function(e){
    //get id of clicked printButton record
    var id = $(this).attr('id');
    var sr = id.split('_')[1];//indesx
    $("#loading").show();
    $.ajax({
        url: checkout_Url,
        type: 'post',
        dataType: 'json',
        data:{'id':sr},
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            //disable the checkout button
            $("#noluggageplus_"+sr).html('');
            $("#loading").hide();
            $("#noluggage_"+sr).val();
            // $("#"+id).hide();
            // $("#tokenno_"+sr).html('');
            // $("#partialcheckoutId_"+sr).hide();
            //remove token ids,
            // console.log('checkoutButton='+newToken);
            // $("#tokenno_"+sr).hide();// TODO - need to do from pusher now

            //TODO - should we delete the QR code image or keep.
            //TODO - or we can delete them in flush cron
        }
    });
})
/**
 * update mhtname and alternate_no if edit is done on him
 */
function updateMhtName(searchRecordId, mhtname,alternate_no ) {
    $.ajax({
        url: updateMht_Url,
        type: 'post',
        dataType: 'json',
        data: {
            mhtid:searchRecordId,
            mhtname:mhtname,
            alternate_no:alternate_no
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            // $("#loading").hide();
            console.log(data);
            $("#search_result_table_tr_"+searchRecordId).find('.search_result_table_td_name').find('.search_result_table_td_span').html(data.data.name);
            $("#search_result_table_tr_"+searchRecordId).find('.search_result_table_td_alternate_no').find('.search_result_table_td_span').html(data.data.alternate_no);
            $("#name_"+searchRecordId).val(data.data.name);
            $("#alternate_no_"+searchRecordId).val(data.data.alternate_no);
        },
        error: function(xhr, textStatus, errorThrown){
            $("#loading").hide();
            $(".printButton").attr('disabled', false);
            console.log(xhr);
            console.log(textStatus);
            console.log(errorThrown);
            alert('Edit Mht!');

         }
    });
}
