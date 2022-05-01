//scan the barcode
// var scannedcode = '47-1-33';//scanned result
// $("#scannedcode").val(scannedcode);


$(document).on("keyup", '#scannedcode', function(e) {
    var scannedcode = $(this).val();
    if(scannedcode) {
        $(".checkout_result").html('checkout in progress...')

        if(scannedcode.indexOf('-') != -1){
            partialcheckout(scannedcode);
        } else {
            //ask for confirmation to checkout all bags for scanned mhtid
            let text = "Do you want to checkout all bags for mhtid="+scannedcode;
            if (confirm(text) == true) {
                fullcheckout(scannedcode);
            }
        }
    }
})


function partialcheckout(scannedcode){
    //split the eventid and token numner from scannedcode
    var scannedcode_split = scannedcode.split('-');
    var sgid = scannedcode_split[0];
    var eventid = scannedcode_split[1];
    var token_no = scannedcode_split[2];

    $.ajax({
        url: partialcheckout_Url,
        type: 'post',
        dataType: 'json',
        data:{'sgid':sgid, 'eventid':eventid, 'token_no': token_no},
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            console.log(data);
            var token_number = data.data.token;
            $(".checkout_result").html('checkout done for token:'+token_number)
            //disable the checkout button
            // $("#noluggageplus_"+sr).html('');
            // $("#loading").hide();
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

}

function fullcheckout(mhtid) {

    $.ajax({
        url: checkoutallinone_Url,
        type: 'post',
        dataType: 'json',
        data:{'mhtid':mhtid},
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            console.log(data);
            var mhtid = data.data.mhtid;
            $(".checkout_result").html('checkout done for mhtid:'+mhtid)
            //disable the checkout button
            // $("#noluggageplus_"+sr).html('');
            // $("#loading").hide();
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
}

