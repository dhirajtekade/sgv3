<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script src="{{ asset('js/common.js') }}"></script>
  <title>Document</title>

    <style>
        #qr, #token{
            margin: auto;
            border: 3px solid green;
            /* padding: 10px; */
            text-align: center;
        }
        .tokenbox {
            /* margin: auto; */
            /* border: 3px solid green; */
            /* padding: 10px; */
            text-align: center;
        }

        #token_td {
            /* text-align: center; */
        }
        #token_title {
            /* float:left; */
        }
        #token_code {
            /* float:right; */
        }
        #event_title {
            /* float:left; */
            font-weight: bold;
        }
        #token_number {
             /* float:left; */
            font-size:100px;
            /* margin-top: -95px !important;
            margin-bottom: -65px !important; */
            /* font-family: "Pacifico script=latin rev=2";
            src: url('/fonts/Pacifico-Regular.ttf'); */
            line-height: 70px;
            height: 35px;
        }
        .token_num_row {
            /* margin: -60px; */
        }
        .EventDetails {
            text-align: initial;
        }
        .barcode_img {
            width: 80%;
        }
    </style>

</head>
<?php
$tokenExplode = explode(',', $data['t']);
$bagExplode = explode(',', $data['b']);
?>
<body>
    <?php
        $eventInfo = getEventInfo();
        $departmentName = (isset($eventInfo->department)) ? $eventInfo->department : 'Samanghar';
        $event_name = (isset($eventInfo->event_name)) ? $eventInfo->event_name : 'Satsang';
        $month = (isset($eventInfo->month)) ? $eventInfo->month : 'May';
        $year = (isset($eventInfo->year)) ? $eventInfo->year : '2022';

        $setttingVars = getSettingConfig();
        if(isset($setttingVars->qr_code_print) && $setttingVars->qr_code_print == 'double'){
        for($i = 0 ; $i < $data['l'] ; $i++) {
            ?>
        <div style='page-break-after: always'>
            <table id="qr" width="250">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <img src="{{asset('images/'.$tokenExplode[$i].'.png')}}" >
                        <br>
                        <span >{{$data['s_id']}}
                            {{ ($setttingVars->show_token_number == 'both') ? "/ ".$tokenExplode[$i] : ''}}
                            {{ ($setttingVars->show_bag_number == 'yes') ? "/ ".$bagExplode[$i] : ''}}

                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <br><br>
    <?php } ?>


    @for($i = 0 ; $i < $data['l'] ; $i++)
        <div class="" style='page-break-after: always'>
            <table id="qr" width="250" class="mt-2 mr-2 ml-2">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="col-sm font-weight-bold"><?php echo $event_name; ?> <?php echo $month.' '.$year; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span id="token_number" class="">{{$tokenExplode[$i]}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="row text-center">
                            <img src="{{asset('images/'.$tokenExplode[$i].'.png')}}"  class="px-4 ">
                            <span >{{$data['s_id']}}</span>
                        </div>
                    </td>
                    {{-- <td>
                        <div class="container tokenbox  ">
                            <div class="row">
                                <div class="col-sm EventDetails">
                                    <div class="row text-center mt-3">
                                        <div class="col-sm font-weight-bold"><?php echo $event_name; ?> <?php echo $month.' '.$year; ?></div>
                                    </div>
                                    <div class="row text-center">
                                        <span id="token_number" class="">{{$tokenExplode[$i]}}</span>
                                    </div>
                                    <div class="row text-center m-3">
                                        <img src="{{asset('images/'.$tokenExplode[$i].'.png')}}"  width="100px" >
                                        <span >{{$data['s_id']}}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td> --}}
                </tr>
                </tbody>
            </table>
        </div>
        @endfor
</body>

</html>

<script type="text/javascript">
    // window.addEventListener("beforeprint", function(event) {
    // });

        // if(have_mobile == 'no') {
        //         window.open(
        //             generateFinalPrint_Url+'?l=0&t='+data.token_no+'&qr='+data.qr+'&h='+data.have_mobile,
        //             '_blank' // <- This is what makes it open in a new window.
        //         );
        // }


        // $('.print-window').click(function() {
            //     window.print();
            // });
            window.print();
            // window.onfocus=function(){ window.close();}

    // setTimeout(function () { window.print(); }, 500);
    //     window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
</script>
