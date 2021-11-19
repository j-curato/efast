<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "FUR per MFO/PAP";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">

            <div class="col-sm-3">
                <label for="from_reporting_period"> From Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'from_reporting_period',
                    'id' => 'from_reporting_period',
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-3">
                <label for="to_reporting_period"> To Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'to_reporting_period',
                    'id' => 'to_reporting_period',
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-2">
                <label for="mfo_code">MFO/PAP Code</label>
                <?php
                $user = Yii::$app->user->identity->division;
                if (!empty($user)) {
                    $data = Yii::$app->db->createCommand("SELECT 'all' as id, 'ALL' as `new_text`
                UNION
                SELECT id,CONCAT(code,'-',`name`) as new_text FROM mfo_pap_code
                WHERE mfo_pap_code.division = :division
                 ")
                        ->bindValue(':division', $user)
                        ->queryAll();
                } else {
                    $data = Yii::$app->db->createCommand("SELECT 'all' as id, 'ALL' as `new_text`
                    UNION
                    SELECT id,CONCAT(code,'-',`name`) as new_text FROM mfo_pap_code
                     ")->queryAll();
                }

                // ->where('code IN (100000100001000,)')

                echo Select2::widget([
                    'name' => 'mfo_code',
                    'id' => 'mfo_code',
                    'data' => ArrayHelper::map($data, 'id', 'new_text'),
                    'options' => ['placeholder' => 'Select MFO/PAP'],

                ]);
                ?>
            </div>

            <div class="col-sm-2" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>

    </form>

    <!-- <div id="con"> -->

    <div id='con'>
        <table id="summary_table">
            <thead>
                <tr>

                    <th rowspan="2"> MFO/PAP </th>
                    <th rowspan="2"> Document Recieve</th>
                    <th rowspan="2"> Book</th>
                    <th rowspan="2">Allotment</th>
                    <th colspan="3">Obligation</th>
                    <th rowspan="2">BALANCES</th>
                    <th rowspan="2"> UTILIZATION</th>

                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>




            </thead>
            <tbody>

            </tbody>

        </table>

        <table class="" id="fur_table" style="margin-top: 30px;">
            <thead>
                <tr>

                    <th rowspan="2">Project / Program</th>
                    <th rowspan="2">Allotment</th>
                    <th colspan="3">Obligation</th>
                    <th rowspan="2">BALANCES</th>
                    <th rowspan="2"> UTILIZATION</th>
                    <th rowspan="2"> MFO/PAP </th>
                    <th rowspan="2"> Document Recieve</th>
                    <th rowspan="2"> Book</th>
                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>




            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- </div> -->
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    .document-row {
        background-color: #ffff99;
    }

    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }

    #summary_table {
        margin-top: 30px;
    }

    /* #con {
        display: none;
    } */

    .amount {
        text-align: right;
    }

    .total-bg {
        background-color: #b3ffff;
    }

    @media print {
        #summary_table {
            margin-top: 0;
        }

        @page {
            margin-left: 0;
            margin-right: 0;
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

        /* .total-bg {

            background-color: red !important;
            -webkit-print-color-adjust: exact;
        } */

    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $('#generate').click((e) => {
        e.preventDefault();
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=report/fur-mfo',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                // var detailed = res.detailed
                // var conso = res.conso
                addData(res.result, res.allotments)
                addToSummaryTable(res.conso_saob)

                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function addData(res, major) {
        $("#fur_table tbody").html('');
        // console.log(major)
        // console.log(res['Personnel Services']['Mid-Year Bonus']['5010216000'][0][])
        var major_keys = Object.keys(res)
        for (var i = 0; i < major_keys.length; i++) {
            var major_name = major_keys[i]
            var str = major_name.toLowerCase().replace(/\s/g, '-');
            row = `<tr class='data_row' id ='${str}'>
                        <td colspan='' style='text-align:left;font-weight:bold;background-color:#cccccc' class='major-header'>` + major_name + `</td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        </tr>`
            $('#fur_table tbody').append(row)
            var sub_major_keys = Object.keys(res[major_name])
            var total_allotment = 0
            var total_prev = 0
            var total_current = 0
            var total_to_date = 0
            var total_balance = 0
            var total_utilization = 0

            for (var x = 0; x < sub_major_keys.length; x++) {

                var sub_major_name = sub_major_keys[x]

                row = `<tr class='data_row document-row'>
                        <td colspan='' style='text-align:left;font-weight:bold'>` + sub_major_name + `</td>
                        <td ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
                // if (sub_major_name == major_name) {
                //     // $(`#${str}`).after(row)
                // } else {

                $('#fur_table tbody').append(row)
                // }
                var uacs_keys = Object.keys(res[major_name][sub_major_name])
                var document_total_allotment = 0
                var document_total_prev = 0
                var document_total_current = 0
                var document_total_to_date = 0
                var document_total_balance = 0
                var document_total_utilization = 0
                for (var y = 0; y < uacs_keys.length; y++) {
                    var uacs = uacs_keys[y]
                    // var ors_object_codes_keys = Object.keys(res[major_name][sub_major_name][uacs])
                    // for (var w = 0; w < ors_object_codes_keys.length; w++) {
                    // var ors_object_code = ors_object_codes_keys[w];
                    var allotment = parseFloat(res[major_name][sub_major_name][y]['allotment'])
                    var prev = parseFloat(res[major_name][sub_major_name][y]['prev_total_ors'])
                    var current = parseFloat(res[major_name][sub_major_name][y]['current_total_ors'])
                    var to_date = parseFloat(res[major_name][sub_major_name][y]['ors_to_date'])
                    var balance = parseFloat(res[major_name][sub_major_name][y]['balance'])
                    var uacs = res[major_name][sub_major_name][y]['uacs']
                    // var mfo = res[major_name][sub_major_name][y]['mfo_code']
                    var document = res[major_name][sub_major_name][y]['document_name']
                    var allotment_uacs = res[major_name][sub_major_name][y]['major_object_code']
                    var mfo_name = res[major_name][sub_major_name][y]['mfo_name']
                    var book_name = res[major_name][sub_major_name][y]['book_name']
                    var utilization = 0
                    if (allotment > 0) {
                        utilization = to_date / allotment * 100
                    }
                    // if (
                    //     allotment == 0

                    // ) {
                    //     console.log(mfo_name, document,
                    //         allotment_uacs)
                    //     var allotment_begin_balance = parseFloat(major[mfo_name][document][allotment_uacs])
                    //     balance = allotment_begin_balance - to_date
                    //     major[mfo_name][document][allotment_uacs] = balance
                    //     utilization = to_date / allotment_begin_balance
                    // } else {
                    //     balance = allotment - to_date
                    // }

                    balance = allotment - to_date

                    if (allotment != 0 || to_date != 0) {

                        // console.log(res[major_name][sub_major_name][y]['ors_object_code'])
                        row = `<tr class='data_row'>
                        <td style ='text-align:left'>` + res[major_name][sub_major_name][y]['uacs'] + '-' + res[major_name][sub_major_name][y]['general_ledger'] + `</td>
                        <td class='amount'>` + thousands_separators(allotment) + `</td>
                        <td class='amount'>` + thousands_separators(prev) + `</td>
                        <td class='amount'>` + thousands_separators(current) + `</td>
                        <td class='amount'>` + thousands_separators(to_date) + `</td>
                        <td class='amount'>` + thousands_separators(balance) + `</td>
                        <td class='amount'>` + thousands_separators(utilization) + '%' + `</td>
                        <td >` + mfo_name + `</td>
                        <td >` + document + `</td>
                        <td >` + book_name + `</td>
                    
                    </tr>`
                        // if (uacs == 5010000000 ||
                        //     uacs == 5020000000 ||
                        //     uacs == 5060000000
                        // ) {
                        //     $(`#${str}`).after(row)
                        // } else {

                            $('#fur_table tbody').append(row)
                        // }
                        total_allotment += allotment
                        total_prev += prev
                        total_current += current
                        total_to_date += to_date
                        total_balance += balance

                        document_total_allotment += allotment
                        document_total_prev += prev
                        document_total_current += current
                        document_total_to_date += to_date
                        document_total_balance += balance
                    }



                }
                document_total_utilization = document_total_to_date/document_total_allotment*100
                row = `<tr class='data_row'>
                        <td  class='total-bg' colspan='' style='font-weight:bold'>`+ major_name+' '+sub_major_name+` Total</td>
                        <td class='amount total-bg'>` + thousands_separators(parseFloat(document_total_allotment.toFixed(2))) + `</td>
                        <td class='amount total-bg'>` + thousands_separators(parseFloat(document_total_prev.toFixed(2))) + `</td>
                        <td class='amount total-bg'>` + thousands_separators(parseFloat(document_total_current.toFixed(2))) + `</td>
                        <td class='amount total-bg'>` + thousands_separators(parseFloat(document_total_to_date.toFixed(2))) + `</td>
                        <td class='amount total-bg'>` + thousands_separators(parseFloat(document_total_balance.toFixed(2))) + `</td>
                        <td class='amount total-bg'>` + thousands_separators(document_total_utilization) + '%' + `</td>
                        <td class='total-bg'  ></td>
                        <td class='total-bg'  ></td>
                        <td class='total-bg'  ></td>
                        </tr>`
                $('#fur_table tbody').append(row)
            }




            if (
                total_allotment != 0 ||
                total_to_date != 0
            ) {
                if (total_allotment != 0) {
                    total_utilization = parseFloat(total_to_date) / parseFloat(total_allotment) * 100

                } else {
                    total_utilization = 0
                }

                row = `<tr class='data_row' style='background-color:#ffcc99'>
                        <td   colspan='' style='font-weight:bold'>`+major_name +` Total</td>
                        <td class='amount '>` + thousands_separators(parseFloat(total_allotment.toFixed(2))) + `</td>
                        <td class='amount '>` + thousands_separators(parseFloat(total_prev.toFixed(2))) + `</td>
                        <td class='amount '>` + thousands_separators(parseFloat(total_current.toFixed(2))) + `</td>
                        <td class='amount '>` + thousands_separators(parseFloat(total_to_date.toFixed(2))) + `</td>
                        <td class='amount '>` + thousands_separators(parseFloat(total_balance.toFixed(2))) + `</td>
                        <td class='amount '>` + thousands_separators(total_utilization) + '%' + `</td>
                        <td   ></td>
                        <td   ></td>
                        <td   ></td>
                        </tr>`
                $('#fur_table tbody').append(row)
            }


        }


    }

    function addToSummaryTable(conso) {
        $('#summary_table tbody').html('')
        var total_beginning_balance = 0
        var total_prev = 0
        var total_current = 0
        var total_to_date = 0
        var total_utilization = 0
        var total_balance = 0
        for (var i = 0; i < conso.length; i++) {
            var beginning_balance = parseFloat(conso[i]['beginning_balance'])
            var prev = parseFloat(conso[i]['prev'])
            var current = parseFloat(conso[i]['current'])
            var to_date = parseFloat(conso[i]['to_date'])
            var utilization = to_date / beginning_balance * 100
            var balance = beginning_balance - to_date
            var row = `<tr>
                <td  >` + conso[i]['mfo_name'] + `</td>
                <td >` + conso[i]['document'] + `</td>
                <td >` + conso[i]['book'] + `</td>
                <td class='amount'>` + thousands_separators(beginning_balance) + `</td>
                <td class='amount'>` + thousands_separators(prev) + `</td>
                <td class='amount'>` + thousands_separators(current) + `</td>
                <td class='amount'>` + thousands_separators(to_date) + `</td>
                <td class='amount'>` + thousands_separators(balance) + `</td>
                <td class='amount'>` + thousands_separators(utilization) + '%' + `</td>
            </tr>`
            $('#summary_table tbody').append(row)
            total_beginning_balance += beginning_balance
            total_prev += prev
            total_current += current
            total_to_date += to_date

            total_balance += balance
        }
        total_utilization = total_to_date / total_beginning_balance * 100
        row = `<tr>
                <td class='total-bg' style='font-weight:bold' colspan='3'>Total</td>
                <td class='amount total-bg'>` + thousands_separators(total_beginning_balance.toFixed(2)) + `</td>
                <td class='amount total-bg'>` + thousands_separators(total_prev.toFixed(2)) + `</td>
                <td class='amount total-bg'>` + thousands_separators(total_current.toFixed(2)) + `</td>
                <td class='amount total-bg'>` + thousands_separators(total_to_date.toFixed(2)) + `</td>
                <td class='amount total-bg'>` + thousands_separators(total_balance.toFixed(2)) + `</td>
                <td class='amount total-bg'>` + thousands_separators(total_utilization.toFixed(2)) + '%' + `</td>
            </tr>`
        $('#summary_table tbody').append(row)
    }
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
    var month= ''
    var year=''
    var province={
        'adn' : 'Agusan Del Norte',
        'ads' : 'Agusan Del Sur',
        'sdn' : 'Surigao Del Norte',
        'sds' : 'Surigao Del Sur',
        'pdi' : 'Province of Dinagat Islands',
    }
JS;
$this->registerJs($script);
?>
