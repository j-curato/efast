<?php

use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\data\ActiveDataProvider;


$this->title = 'RAAF';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class=" " style="width: 100%;background-color:white">

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
    <div class="con">

        <table id="raaf_table">
            <thead>
                <tr>
                    <th colspan="15" style="text-align: center;border:0;padding-bottom:80px">
                        <span> REPORT OF ACCOUNTABILITY FOR ACCOUNTABLE FORMS</span>
                        <br>
                        <span>As of June 20, 2021</span>
                    </th>
                </tr>
                <tr>
                    <th colspan="7" style="border: 0;">
                        <span>Entity Name: </span>
                        <span>Department of Trade and Industry - </span>
                        <span id="prov"></span>
                    </th>
                    <th colspan="8" style="border: 0;">
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
                    <th> A. WITH FACE VALUE</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan=""> B. WITHOUT FACE VALUE</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="15" style="text-align: center;">
                        <span>

                            CERTIFICATION
                        </span>
                        <br>
                        <br>
                        <br>
                        <span>
                            I hereby certify that the foregoing is a true statement of all accountable forms received,
                        </span>
                        <br>
                        <span>
                            issued and transferred by me during the period above-stated and that the beginning and ending balances are correct.
                        </span>
                        <br>
                        <br>
                        <br>
                        <br>

                        <span id="officer" style="text-decoration: underline;">qwer</span>
                        <br>
                        <span>
                            Signature over Printed Name of the Accountable Officer
                        </span>

                    </td>
                </tr>
            </tfoot>
        </table>

        <table id="duplicate">
            <thead>
                <tr>

                    <th colspan="3" style="text-align: center;">Duplcates</th>
                </tr>
                <tr>

                    <th>Range</th>
                    <th>Check Number</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <table id="skipped_check" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">Skipped</th>
                </tr>
                <tr>

                    <th>Range</th>
                    <th>Check Number</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>

</div>
<div id="dots5" style="display: none;">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<style>
    .con {
        margin-top: 15px;
    }

    table,
    td,
    th {
        border: 1px solid black;
        padding: 12px;
    }

    .contaner {
        width: 100%;
    }

    .con {
        display: none;
    }

    #duplicate {
        margin-top: 20px;
    }

    @media print {
        @page {
            size: landscape;
        }

        #duplicate {
            display: none;
        }

        #skipped_check {
            display: none;
        }

        table,
        td,
        th {
            padding: 3px;
            margin: 0;
        }

        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    var province = {
        'adn': 'Agusan Del Norte',
        'ads': 'Agusan Del Sur',
        'sdn': 'Surigao Del Norte',
        'sds': 'Surigao Del Sur',
        'pdi': 'Province of Dinagat Islands',
    }
    var officer = {
        'adn': 'ROSIE R. VELLESCO',
        'ads': 'PRESCYLIN C. LADEMORA',
        'sdn': 'FERDINAND R. INRES',
        'pdi': 'VENUS A. CUSTODIO',
        'sds': 'FRITZIE N. USARES',
    }
    $("#filter").submit((e) => {
        $('#dots5').show()
        $('.con').hide()
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
                $('#prov').text(province[res.province])
                $('#officer').text(officer[res.province])
                setTimeout(() => {
                    $('#dots5').hide()
                    $('.con').show()
                }, 1000);
            }
        })
    })

    function displayData(data) {
        $('#raaf_table tbody').html('')
        for (var i = 0; i < data.length; i++) {
            var cur_count = data[i]['current_count'] == 0 ? '' : data[i]['current_count'];
            console.log(cur_count)
            var balance = parseInt(data[i]['begin_balance']) - parseInt(data[i]['current_count'])
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
                <td>` + balance + `</td>
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