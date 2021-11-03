<?php

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


$this->title = 'Rao';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container panel panel-default">

    <video id="preview"></video>

    <input type="text" id="sample_text">
    <!-- <div id="scanner-container"></div>
    <input type="button" id="btn" value="Start/Stop the scanner" /> -->
    <html>
    <div id="showBarcode"></div>

    </html>
    <!--This element id should be passed on to options-->


    <?php

    use barcode\barcode\BarcodeGenerator as BarcodeGenerator;

    $optionsArray = array(
        'elementId' => 'showBarcode', /* div or canvas id*/
        'value' => '4797001018719', /* value for EAN 13 be careful to set right values for each barcode type */
        'type' => 'code128',/*supported types ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

    );
    echo BarcodeGenerator::widget($optionsArray);
    ?>
</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 100px;
    }
</style>
<script type="text/javascript" src="/afms/js/instascan.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>


<!-- Include the image-diff library -->

<!-- <script>
    var _scannerIsRunning = false;

    function startScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-container'),
                constraints: {
                    width: 480,
                    height: 320,
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "code_39_vin_reader",
                    "codabar_reader",
                    "upc_reader",
                    "upc_e_reader",
                    "i2of5_reader"
                ],
                debug: {
                    showCanvas: true,
                    showPatches: true,
                    showFoundPatches: true,
                    showSkeleton: true,
                    showLabels: true,
                    showPatchLabels: true,
                    showRemainingPatchLabels: true,
                    boxFromPatches: {
                        showTransformed: true,
                        showTransformedBox: true,
                        showBB: true
                    }
                }
            },

        }, function(err) {
            if (err) {
                console.log(err);
                return
            }

            console.log("Initialization finished. Ready to start");
            Quagga.start();

            // Set flag to is running
            _scannerIsRunning = true;
        });
        var result = []
        Quagga.onDetected(function(result) {
            console.log("Barcode detected and processed : [" + result.codeResult.code + "]", );
            // Quagga.stop();
        });
    }


    // Start/stop scanner
    document.getElementById("btn").addEventListener("click", function() {
        if (_scannerIsRunning) {
            Quagga.stop();
        } else {
            startScanner();
        }
    }, false);
</script> -->
<script>
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });
    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
        }
    }).catch(function(e) {
        console.error(e);
    });

    scanner.addListener('scan', (c) => {
        console.log(c)

        $('#sample_text').val(c)
        play()
    })

    function play() {
        var beepsound = new Audio(
            '/afms/frontend/web/beep.mp3');
        beepsound.play();
    }
</script>