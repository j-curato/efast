<?php

use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\data\ActiveDataProvider;


$this->title = 'RAAF';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class=" panel panel-default">
    <form id='filter'>
        <div class="row">

            <div class="col-sm-2">
                <label for="to_reporting_period">To Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'to_reporting_period',
                    'name' => 'to_reporting_period',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView' => 'months',
                        'minViewMode' => 'months',
                        'format' => 'yyyy-mm'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-2">
                <label for="province">Province</label>
                <?php
                echo Select2::widget([
                    'name' => 'province',
                    'id' => 'province',
                    'data' => [
                        'adn' => 'ADN',
                        'ads' => 'ADS',
                        'sdn' => 'SDN',
                        'sds' => 'SDS',
                        'pdi' => 'PDI',
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Province'
                    ]
                ])

                ?>
            </div>

            <div class="col-sm-2">
                <button class="btn btn-success" id="generate" style="margin-top:23px">Generate</button>
            </div>
        </div>
    </form>
    <table id="raaf_table">
        <thead>
            <tr>
                <th colspan="7">
                    <span>Entity Name: </span>
                    <span>Department of Trade and Industry -ADN</span>
                </th>
                <th colspan="8">
                    <span>Fund Cluster: </span>
                    <span>01</span>
                </th>
            </tr>
            <tr>
                <th colspan="3">Accountable Forms</th>
                <th colspan="3">Beginning Balance</th>
                <th colspan="3">Reciept</th>
                <th colspan="3">Issue</th>
                <th colspan="3">Ending Balance</th>
            </tr>
            <tr>
                <th rowspan="2">Name of Form</th>
                <th rowspan="2"> Number</th>
                <th rowspan="2">Face Value</th>
                <th rowspan="2">Quantity</th>
                <th rowspan="1" colspan="2"> Inclusive Serial Nos.</th>
                <th rowspan="2"> Quantity.</th>
                <th rowspan="1" colspan="2"> Inclusive Serial Nos.</th>
                <th rowspan="2"> Quantity.</th>
                <th rowspan="1" colspan="2"> Inclusive Serial Nos.</th>
                <th rowspan="2"> Quantity.</th>
                <th rowspan="1" colspan="2"> Inclusive Serial Nos.</th>
            </tr>
            <tr>
                <th rowspan="1"> From</th>
                <th rowspan="1"> To</th>
                <th rowspan="1"> From</th>
                <th rowspan="1"> To</th>
                <th rowspan="1"> From</th>
                <th rowspan="1"> To</th>
                <th rowspan="1"> From</th>
                <th rowspan="1"> To</th>
            </tr>
            <tr>
                <th colspan="15"> A. WITH FACE VALUE</th>
            </tr>
            <tr>
                <th colspan="15"> B. WITHOUT FACE VALUE</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <table id="duplicate">
        <thead>
            <th>Range</th>
            <th>Check Number</th>
            <th>Count</th>
        </thead>
        <tbody>

        </tbody>
    </table>
    <table id="skipped_check">
        <thead>
            <th>Range</th>
            <th>Check Number</th>
        </thead>
        <tbody>

        </tbody>
    </table>


</div>
<style>
    table,
    td,
    th {
        border: 1px solid black;
        padding: 12px;
    }

    .contaner {
        width: 100%;
    }

    #duplicate {
        margin-top: 20px;
    }
</style>
<script>
    $("#filter").submit((e) => {
        e.preventDefault()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=report/raaf',
            data: $('#filter').serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                displayData(res.results)
                duplicates(res.duplicates)
                skipped(res.skiped_checks)
            }
        })
    })

    function displayData(data) {
        $('#raaf_table tbody').html('')
        for (var i = 0; i < data.length; i++) {
            var row = `<tr>
                <td></td>
                <td></td>
                <td></td>
                <td>` + data[i]['begin_balance'] + `</td>
                <td>` + data[i]['from'] + `</td>
                <td>` + data[i]['to'] + `</td>
                <td></td>
                <td>` + data[i]['from'] + `</td>
                <td>` + data[i]['to'] + `</td>
                <td>` + data[i]['current_count'] + `</td>
                <td>` + data[i]['current_min'] + `</td>
                <td>` + data[i]['current_max'] + `</td>
                <td>` + data[i]['balance'] + `</td>
                <td>` + data[i]['from'] + `</td>
                <td>` + data[i]['to'] + `</td>
            </tr>`
            $('#raaf_table >tbody').append(row)

        }

    }

    function duplicates(data) {
        $('#duplicate tbody').html('')
        var range_keys = Object.keys(data)
        for (var i = 0; i < range_keys.length; i++) {
            var range = range_keys[i]
            var row = `<tr>
                <td >` + range + `</td>
                <td ></td>
                <td ></td>
            </tr>`
            $('#duplicate > tbody').append(row)
            for (var x = 0; x < data[range].length; x++) {
                row = `<tr>
                <td></td>
                <td>` + data[range][x]['check_number'] + `</td>
                <td>` + data[range][x]['dup_count'] + `</td>
            </tr>`
                $('#duplicate > tbody').append(row)
            }
        }

    }

    function skipped(data) {
        $('#skipped_check tbody').html('')
        var range_keys = Object.keys(data)
        for (var i = 0; i < range_keys.length; i++) {
            var range = range_keys[i]
            var row = `<tr>
                <td >` + range + `</td>
                <td ></td>
            </tr>`
            $('#skipped_check > tbody').append(row)
            var child = Object.keys(data[range])
            for (var x = 0; x < child.length; x++) {
                row = `<tr>
                <td></td>
                <td>` + data[range][child[x]] + `</td>
            </tr>`
                $('#skipped_check > tbody').append(row)
            }
        }

    }
</script>