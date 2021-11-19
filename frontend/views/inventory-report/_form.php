<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


$this->title = 'Inventory Report';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="con ">
    <div class="grid-container">

        <div class="property-details panel panel-default">
            <div class="row">
                <div class="col-sm-12">

                    <video id="preview"></video>
                </div>
            </div>
            <div class="card">
                <span class="details-label">Property Card Number</span>
                <br>
                <span>

                    <input type="text" id="pc_number" class="form-control">
                </span>
            </div>
            <div class="card">
                <span class="details-label">PAR Number</span>
                <br>
                <span id="par_number"></span>

            </div>
            <div class="card">
                <span class="details-label">Property Number</span>
                <br>
                <span id="property_number"></span>

            </div>
            <div class="card">
                <span class="details-label">PTR Number</span>
                <br>
                <span id="ptr_number"></span>

            </div>
            <div class="card">
                <span class="details-label">Article</span>
                <br>
                <span id="article"></span>

            </div>
            <div class="card">
                <span class="details-label">Model</span>
                <br>
                <span id="model">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perferendis ea facilis ad dolorum hic quisquam repudiandae nulla quam non nemo labore similique, aliquam rerum atque velit asperiores quas earum natus!</span>

            </div>
            <div class="card">
                <span class="details-label">Serial Number</span>
                <br>
                <span id="serial_number"></span>

            </div>
            <div class="card">
                <span class="details-label">Date Acquired</span>
                <br>
                <span id="date_acquired"></span>

            </div>

            <div class="card">
                <span class="details-label">Accoutable Person</span>
                <br>
                <span id="accountable_person"></span>

            </div>
            <div class="card">
                <span class="details-label">Acquisation Amount</span>
                <br>
                <span id="amount"></span>

            </div>
            <div class="card">
                <span>

                    <button id="generate" type="button" class="btn btn-primary" style="margin-top: 24px;">Generate </button>
                </span>
                <span>
                    <button class="btn btn-warning" style="margin-top: 24px;" id="add">Add</button>
                </span>

            </div>

        </div>
        <div class="property-list panel-panel-default">
            <form id="property-list-form">

                <table class="property-list-table  " id="list-table">
                    <thead>

                        <th>Property Number</th>
                        <th>PAR Number</th>
                        <th>Property Car Number</th>
                        <th>PTR Number</th>
                        <th>Article</th>
                        <th>Model</th>
                        <th>Serial Number</th>
                        <th>Date Acquired</th>
                        <th>Accoutable Person</th>
                        <th>Acquisation Amount</th>
                    </thead>
                    <tbody>


                        <?php
                        $id = '';
                        if (!empty($model->id)) {
                            $id = $model->id;

                            $query  = Yii::$app->db->createCommand("SELECT
                                par.par_number,
                                IFNULL(ptr.ptr_number,'') as ptr_number,
                                par.date as par_date,
                                property.property_number,
                                property.quantity,
                                property.acquisition_amount,
                                property.article,
                                property.iar_number,
                                property.model,
                                property.serial_number,
                                property.date as date_acquired,
                                property_card.pc_number,
                                UPPER(recieved_by.employee_name) as accountable_officer
                                FROM property_card
                                LEFT JOIN par ON  property_card.par_number =par.par_number
                                LEFT JOIN property ON par.property_number = property.property_number
                                LEFT JOIN employee_search_view as recieved_by ON par.employee_id  = recieved_by.employee_id
                                LEFT JOIN ptr ON par.par_number = ptr.par_number
                                WHERE property_card.pc_number IN (SELECT inventory_report_entries.pc_number FROM inventory_report_entries 
                                WHERE inventory_report_entries.inventory_report_id = :id)
                            ")
                                ->bindValue(':id', $model->id)
                                ->queryAll();

                            foreach ($query as $val) {
                                echo "<tr>
                                <td style='display:none' ><input type='text' value='{$val['pc_number']}' class='pc-number-list' name='pc_number[]'></td>
                            <td>{$val['pc_number']}</td>
                            <td>{$val['property_number']}</td>
                            <td>{$val['par_number']}</td>
                            <td>{$val['ptr_number']}</td>
                            <td>{$val['article']}</td>
                            <td>  {$val['model']}</td>
                            <td>  {$val['serial_number']}</td>
                            <td>  {$val['date_acquired']}</td>
                            <td>  {$val['accountable_officer']}</td>
                            <td>  {$val['acquisition_amount']}</td>
                            <td><button class='remove btn-xs btn-danger'>remove</button></td>
        
                        </tr>";
                            }
                        }
                        echo "<input type='hidden' name='id' value='$id'>";

                        ?>
                    </tbody>
                    <tfoot>

                        <tr>
                            <td colspan="11"> <button class="btn btn-success" style="width: 100%;" id="save">Save</button></td>
                        </tr>
                    </tfoot>
                </table>
            </form>

        </div>
    </div>

</div>
<style>
    .details-label {
        font-weight: bold;
    }

    .con {
        display: block;
        height: 100%;
        background-color: #b3b3b3;

    }

    /* .table-striped > tbody > tr:nth-of-type(odd)  */
    .property-list-table>tbody>tr:nth-of-type(odd) {
        background-color: #cceeff;
    }

    .grid-container {
        display: grid;
        grid-template-columns: 30% 70%;
        gap: 10px;
        padding: 1em;
    }

    .card {
        width: 100%;
        padding: 5px;
        box-shadow: 0.5px 1px #888888;
        height: auto;
        min-height: 5em;
        font-size: smaller;

    }

    .property-details {
        height: auto;
        min-height: 300px;
        display: grid;
        grid-auto-flow: row;
        gap: 0.3em;
        max-height: 70em;
    }


    .property-list {
        height: auto;
        background-color: white;
        box-shadow: 0.5px 1px #888888;
        padding: 1em;
    }

    .grid-view td {
        white-space: normal;
        width: 100px;
    }


    td,
    th {
        font-size: x-small;
        padding: 7px;
    }

    table {
        width: 100%;
    }

    #preview {
        height: 400px;
        width: 100%;
        height: 100%;
    }
</style>



<?php
SweetAlertAsset::register($this);
?>
<script>
    var constraints = {
        video: true,
        video: {
            width: 1280,
            height: 720
        }
    };

    $('#add').click(() => {
        addToList()
    })

    $('#pc_number').on('keypress', function(e) {
        if (e.which == 13) {
            detailsApi()
        }
    });
    $(document).keydown(function(e) {
        if (e.which == 39) {
            addToList()
        }
    });

    function addToList() {

        var q = $('.pc-number-list').map((_, el) => el.value).get()
        var property_number = $("#property_number").text()
        var par_number = $('#par_number').text()
        var property_card_number = $('#pc_number').val()
        var ptr_number = $('#ptr_number').text()
        var article = $('#article').text()
        var model = $('#model').text()
        var serial_number = $('#serial_number').text()
        var date_acquired = $('#date_acquired').text()
        var accountable_person = $('#accountable_person').text()
        var amount = $('#amount').text()
        var search = q.includes(property_card_number)
        if (
            property_card_number == '' ||
            par_number == ''
        ) {

            swal({
                title: 'Error',
                type: 'error',
                text: 'Please Generate First',
                button: false
            })
            return

        }
        if (search) {

            swal({
                title: "Error",
                text: 'Na sulod na sa list',
                type: 'error',
                button: false,
                timer: 3000,
            })
        } else {


            var row = ` <tr>
                        <td style='display:none' ><input type="text" value='${property_card_number}' class='pc-number-list' name='pc_number[]'></td>
                        <td>${property_card_number}</td>
                        <td>${property_number}</td>
                        <td>${par_number}</td>
                        <td>${ptr_number}</td>
                        <td>
                        ${article}
                          </td>
                        <td>  ${model}</td>
                        <td>  ${serial_number}</td>
                        <td>  ${date_acquired}</td>
                        <td>  ${accountable_person}</td>
                        <td>  ${amount}</td>
                        <td><button class='remove btn-xs btn-danger'>remove</button></td>

                    </tr>`
            $('#list-table tbody').append(row)

            $("#property_number").text('')
            $('#par_number').text('')
            $('#pc_number').val('')
            $('#ptr_number').text('')
            $('#article').text('')
            $('#model').text('')
            $('#serial_number').text('')
            $('#date_acquired').text('')
            $('#accountable_person').text('')
            $('#amount').text('')
        }

    }

    $(".property-list-table").on("click", ".remove", function() {
        $(this).closest("tr").remove();
    });


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

        $.when(detailsApi()).done(() => {


            setTimeout(function() {
                addToList()
            }, 500);

        })




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
    $('#save').click((e) => {
        e.preventDefault()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=inventory-report/insert',
            data: $('#property-list-form').serialize(),
            success: function(data) {
                console.log(JSON.parse(data))
            }
        })
    })
</script>