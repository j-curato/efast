<?php

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


$this->title = 'Rao';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container panel panel-default">

    <div class="row">
        <div class="col-sm-5">
            <video id="preview"></video>

        </div>
        <div class="col-sm-7">
            <div class="row" style="margin-top: 20px;margin-left:20px">
                <div class="col-sm-5">
                    <label for="pc_number"> Property Card Number</label>
                    <input type="text" id="pc_number" class="form-control">
                </div>
                <div class="col-sm-3">
                    <button id="generate" type="button" class="btn btn-success" style="margin-top: 13px;">Generate </button>
                </div>
            </div>
            <table id="pc_details">
                <tbody>


                    <tr>
                        <th>Property Number</th>
                        <td id="property_number"></td>

                        <th>PAR Number</th>
                        <td id="par_number"></td>
                    </tr>
                    <tr>
                        <th>Property Car Number</th>
                        <td id="property_card_number"></td>
                        <th>PTR Number</th>
                        <td id="ptr_number"></td>
                    </tr>
                    <tr>
                        <th>Article</th>
                        <td id="article"></td>
                        <th>Model</th>
                        <td id="model"></td>
                    </tr>
                    <tr>
                        <th>Serial Number</th>
                        <td id="serial_number"></td>
                        <th>Date Acquired</th>
                        <td id="date_acquired"></td>
                    </tr>
                    <tr>
                        <th>Accoutable Person</th>
                        <td id="accountable_person"></td>
                        <th>Acquisation Amount</th>
                        <td id="amount"></td>

                    </tr>
                </tbody>
            </table>

        </div>

    </div>

</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 100px;
    }

    td,
    th {
        padding: 12px;
        border: 1px solid black;
    }

    table {
        margin: 20px;
    }

    #preview {
        height: 400px;
        width: 400px;
    }
</style>




<script>
    var constraints = {
        video: true,
        video: {
            width: 1280,
            height: 720
        }
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(mediaStream) {
            var video = document.querySelector('video');
            video.srcObject = mediaStream;
            video.onloadedmetadata = function(e) {
                video.play();
            };
        })
        .catch(function(err) {
            console.log('qwe' + err.name + ": " + err.message);
        })
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

        $('#pc_number').val(c)

        // $.ajax({
        //     type: 'POST',
        //     url: window.location.pathname + '?r=property-card/property-details',
        //     data: {
        //         id: c
        //     },
        //     success: function(data) {
        //         let res = JSON.parse(data)
        //         $("#property_number").text(res.property_number)
        //         $('#par_number').text(res.par_number)
        //         $('#pc_number').text(res.pc_number)
        //         $('#ptr_number').text(res.ptr_number)
        //         $('#article').text(res.article)
        //         $('#model').text(res.model)
        //         $('#serial_number').text(res.serial_number)
        //         $('#date_acquired').text(res.date_acquired)
        //         $('#accountable_person').text(res.accountable_officer)
        //         $('#amount').text(res.acquisition_amount)

        //     }
        // })
        detailsApi()
        play()
    })

    function detailsApi() {
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=property-card/property-details',
            data: {
                id: $('#pc_number').val()
            },
            success: function(data) {
                let res = JSON.parse(data)
                $("#property_number").text(res.property_number)
                $('#par_number').text(res.par_number)
                $('#property_card_number').text(res.pc_number)
                $('#ptr_number').text(res.ptr_number)
                $('#article').text(res.article)
                $('#model').text(res.model)
                $('#serial_number').text(res.serial_number)
                $('#date_acquired').text(res.date_acquired)
                $('#accountable_person').text(res.accountable_officer)
                $('#amount').text(res.acquisition_amount)

            }
        })
    }
    $('#generate').click(() => {
        detailsApi()
    })

    function play() {
        var beepsound = new Audio(
            '/afms/frontend/web/beep.mp3');
        beepsound.play();
    }
</script>