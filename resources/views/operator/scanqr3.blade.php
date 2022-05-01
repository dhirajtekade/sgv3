@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/dynamsoft-javascript-barcode@9.0.0/dist/dbr.js"></script>
@section('content')

<style>
    #barcode-scanner canvas.drawingBuffer, #barcode-scanner video.drawingBuffer {
    display: none;
    }

    #barcode-scanner canvas, #barcode-scanner video {
    width: 100%;
    height: auto;
    }
</style>


<div id='barcode-scanner'></div>

<script type="text/javascript">
    // Dynamsoft.DBR.BarcodeReader.license = "DLS2eyJoYW5kc2hha2VDb2RlIjoiMjAwMDAxLTE2NDk4Mjk3OTI2MzUiLCJvcmdhbml6YXRpb25JRCI6IjIwMDAwMSIsInNlc3Npb25QYXNzd29yZCI6IndTcGR6Vm05WDJrcEQ5YUoifQ==";
    //     let scanner = null;
    //     (async()=>{
    //         scanner = await Dynamsoft.DBR.BarcodeScanner.createInstance();
    //         scanner.onFrameRead = results => {console.log(results);};
    //         scanner.onUnduplicatedRead = (txt, result) => {};
    //         await scanner.show();
    //     })();


    //     window.onload = async function () {
    //     try {
    //         await Dynamsoft.DBR.BarcodeScanner.loadWasm();
    //         await initBarcodeScanner();
    //     } catch (ex) {
    //         alert(ex.message);
    //         throw ex;
    //     }
    // };
    // let scanner = null;
    // async function initBarcodeScanner() {
    //     scanner = await Dynamsoft.DBR.BarcodeScanner.createInstance();
    //     scanner.onFrameRead = results => {console.log(results);};
    //     scanner.onUnduplicatedRead = (txt, result) => {};
    //     await scanner.show();
    // }

//     barcode.config.start = 0.1;
// barcode.config.end = 0.9;
// barcode.config.video = '#barcodevideo';
// barcode.config.canvas = '#barcodecanvas';
// barcode.config.canvasg = '#barcodecanvasg';
// barcode.setHandler(function(barcode) {
//     console.log(barcode);
// });

// barcode.init();


//= require quagga
//= require_tree .

    function order_by_occurrence(arr) {
        var counts = {};
        arr.forEach(function(value){
            if(!counts[value]) {
                counts[value] = 0;
            }
            counts[value]++;
        });

        return Object.keys(counts).sort(function(curKey,nextKey) {
            return counts[curKey] < counts[nextKey];
        });
    }

    function load_quagga(){
        if ($('#barcode-scanner').length > 0 && navigator.mediaDevices && typeof navigator.mediaDevices.getUserMedia === 'function') {

            var last_result = [];
            if (Quagga.initialized == undefined) {
            Quagga.onDetected(function(result) {
                var last_code = result.codeResult.code;
                last_result.push(last_code);
                if (last_result.length > 20) {
                code = order_by_occurrence(last_result)[0];
                last_result = [];
                Quagga.stop();
                $.ajax({
                    type: "POST",
                    url: '/products/get_barcode',
                    data: { upc: code }
                });
                }
            });
            }

            Quagga.init({
            inputStream : {
                name : "Live",
                type : "LiveStream",
                numOfWorkers: navigator.hardwareConcurrency,
                target: document.querySelector('#barcode-scanner')
            },
            decoder: {
                readers : ['ean_reader','ean_8_reader','code_39_reader','code_39_vin_reader','codabar_reader','upc_reader','upc_e_reader']
            }
            },function(err) {
                if (err) { console.log(err); return }
                Quagga.initialized = true;
                Quagga.start();
            });

        }
    };
    $(document).on('turbolinks:load', load_quagga);

</script>
@endsection
