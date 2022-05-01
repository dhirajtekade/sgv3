$(document).on("click","#displayTodayRecord",function(e) {

    // var isDisplayTodayRecordChecked = $('#displayTodayRecord:checked').val();
    // alert(isDisplayTodayRecordChecked);
    if($('#displayTodayRecord:checked').val() == 'on'){
        $(".token_result").show();
    } else {
        $(".token_result").hide();
    }
})
