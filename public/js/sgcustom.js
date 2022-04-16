

$(document).on("keyup", '.search_mht_by', function(e) {
    var mobile = $("#search_by_mobile").val();
    var any = $("#search_by_any").val();
    var mhtid = $("#search_by_mhtid").val();
    $(".search_result").show();
    e.preventDefault();
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
            console.log(data);
            //clear previous records
            $(".not_default_search").remove();
           // $(".search_result_table_tr").hide();
            //$(".search_result_table_td_input").hide();
            //$(".search_result_table_td_span").show();

            if(data != '' && data != -1) {
                //hide default search tr
                $(".default_new").hide();
                //create tr html
                var trhtml = '';
                $.each( data, function( key, value ) {
                    trhtml += '<tr class="search_result_table_tr not_default_search" id="search_result_table_tr_'+value.id+'">';
                    trhtml += '<td class="search_result_table_td_mhtid">'+value.mht_id+'</td>';
                    trhtml += '<td class="search_result_table_td_name"><span class="search_result_table_td_span">'+value.name+'</span>';
                    trhtml += '<span class="search_result_table_td_input"><input value="'+value.name+'" type="text" name="name" class="name  w-75" id="name_'+value.id+'" size="10" placeholder="Full Name"></span></td>';
                    trhtml += '<td class="search_result_table_td_alternate_no"><span class="search_result_table_td_span">'+value.alternate_no+'</span>';
                    trhtml += '<span class="search_result_table_td_input"><input value="'+value.alternate_no+'" type="text" name="alternate_no" class="alternate_no numericCheck w-75" id="alternate_no_'+value.id+'" size="10" placeholder="Mobile Number" ></span></td>';
                    trhtml += '<td><input value="" type="text" name="no_luggage" class="no_luggage numericCheck" id="noluggage_'+value.id+'" size="5" placeholder="Bags">';
                    if(value.total_bags > 0){
                        trhtml += '<span class="mr-2"> +'+value.total_bags+'</span>';
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
                $(".default_new").show();
                $(".search_result_table_td_mhtid").html(mhtid);

                //still allow to write number and bag
            } else { //hide all search tr
                $(".search_result_table_tr").hide();

            }
            // if(data.statusCode == 200){
            //     $(".addNewPrint").attr('disabled', false);
            //     //close modal
            //     $("#addNewModal").modal('hide');
            //     var detail = data.data;
            //     console.log(detail);
            //     window.open(
            //         // generateFinalPrintUrl+'?l='+data.no_luggage+'&t='+data.token_no+'&qr='+data.qr+'&h='+data.have_mobile,
            //         generateFinalPrintUrl+'?s_id='+detail.s_mht_id+'&l='+detail.no_luggage+'&t='+detail.token_no,
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

})

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
            alert('check console');
            // if(data.statusCode == 200){
            //     $(".addNewPrint").attr('disabled', false);
            //     //close modal
            //     $("#addNewModal").modal('hide');
            //     var detail = data.data;
            //     console.log(detail);
            //     window.open(
            //         // generateFinalPrintUrl+'?l='+data.no_luggage+'&t='+data.token_no+'&qr='+data.qr+'&h='+data.have_mobile,
            //         generateFinalPrintUrl+'?s_id='+detail.s_mht_id+'&l='+detail.no_luggage+'&t='+detail.token_no,
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
    alert('not implementd')
})
