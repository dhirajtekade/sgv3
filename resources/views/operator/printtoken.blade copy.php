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
            font-size:200px;
            margin-top: -95px !important;
            margin-bottom: -65px !important;
        }
        .token_num_row {
            /* margin: -60px; */
        }
        .EventDetails {
            text-align: initial;
        }
    </style>

</head>
<?php
$tokenExplode = explode(',', $data['t']);
?>
<body>
    <?php
        $setttingVars = getSettingConfig();
        if(isset($setttingVars->qr_code_print) && $setttingVars->qr_code_print == 'double'){
        for($i = 0 ; $i < $data['l'] ; $i++) {
            ?>
        <div style='page-break-before: always'>
            <table id="qr" width="350">
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
                        <span >Temp # {{$data['s_id']}}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php }} ?>

  <br><br>


    @for($i = 0 ; $i < $data['l'] ; $i++)
        <div style='page-break-before: always'>
            <table id="qr" width="350">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>
                    <div class="container tokenbox">
                        <br>
                        <br>
                        <div class="row token_num_row">
                            <div class="col-sm">
                                <hr>
                                <span >Temp # {{$data['s_id']}}</span>
                                <hr>
                                <div id="token_number" class="">{{$tokenExplode[$i]}}</div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm EventDetails">
                                <div class="row text-left">
                                    <div class="col-sm font-weight-bold">MMHT Shibir</div>
                                </div>
                                <div class="row text-left">
                                    <div class="col-sm">March 2022</div>
                                </div>
                                <div class="row text-left">
                                    <div class="col-sm">Saman Ghar</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <img src="{{asset('images/'.$tokenExplode[$i].'.png')}}"  width="150px" >
                            </div>
                        </div>

                    </div>
                </td>
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
            window.onfocus=function(){ window.close();}

    // setTimeout(function () { window.print(); }, 500);
    //     window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
</script>
