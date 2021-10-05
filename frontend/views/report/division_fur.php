<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\FundSourceType;
use app\models\MajorAccounts;
use app\models\MfoPapCode;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "FUR";
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
                <label for="division">Division</label>
                <?php

                $data = [
                    'all' => 'All',
                    'cpd' => 'CPD',
                    'fad' => 'FAD',
                    'idd' => 'IDD',
                    'ord' => 'ORD',
                    'sdd' => 'SDD'
                ];
                // ->where('code IN (100000100001000,)')

                echo Select2::widget([
                    'name' => 'division',
                    'id' => 'division',
                    'data' => $data,
                    'options' => ['placeholder' => 'Select Division'],

                ]);
                ?>
            </div>
            <div class="col-sm-2">
                <label for="document_recieve">Document Recive</label>
                <?php

                $data = Yii::$app->db->createCommand("SELECT 'all' as id, 'ALL' as `name`
                UNION
                SELECT id,`name`FROM document_recieve 
                ")->queryAll();
                // ->where('code IN (100000100001000,)')
                // var_dump($data);
                echo Select2::widget([
                    'name' => 'document_recieve',
                    'id' => 'document_recieve',
                    'data' => ArrayHelper::map($data, 'id', 'name'),
                    'options' => ['placeholder' => 'Select Document'],

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

                    <th>Division</th>
                    <th>MFO/PAP Code</th>
                    <th>MFO/PAP Name</th>
                    <th>Beginning Balance</th>
                    <th>Allotment Recieved</th>
                    <th>Obligation Incured</th>
                    <th> Balance</th>
                    <th> FUR%</th>
                    <th> MFO/PAP </th>
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

    @media print {
        #summary_table {
            margin-top: 0;
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
            url: window.location.pathname + '?r=report/division-fur',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                // var detailed = res.detailed
                // var conso = res.conso
                addData(res.result)
                // addToSummaryTable(res.conso_saob)

                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function addData(res) {
        $("#fur_table tbody").html('');

        var division_keys = Object.keys(res)
        for (var i = 0; i < division_keys.length; i++) {
            var division_name = division_keys[i]

            var mfo_keys = Object.keys(res[division_name])
            for (var mfo_loop = 0; mfo_loop < mfo_keys.length; mfo_loop++) {
                var mfo_name = mfo_keys[mfo_loop];
                row = `<tr class='data_row'>
                <td colspan='' style='font-weight:bold;background-color:#cccccc' class='major-header'>` + division_name.toUpperCase() + `</td>
                      <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td colspan='' style='text-align:left;font-weight:bold;background-color:#cccccc' class='major-header'>` + mfo_name + `</td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        </tr>`
                $('#fur_table tbody').append(row)
                var major_keys = Object.keys(res[division_name][mfo_name])
                console.log(major_keys)
                for (var major_loop = 0; major_loop < major_keys.length; major_loop++) {
                    var major_name = major_keys[major_loop];
                    console.log(major_name)
                    row = `<tr class='data_row'>
                    <td ></td>
                    <td ></td>
                    <td colspan='' >` + major_name + `</td>
                    <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        </tr>`
                    $('#fur_table tbody').append(row)
                    var sub_major_keys = Object.keys(res[division_name][mfo_name][major_name])
                    for (var sub_major_loop = 0; sub_major_loop < sub_major_keys.length; sub_major_loop++) {
                        var sub_major_name = sub_major_keys[sub_major_loop];
                        row = `<tr class='data_row'>
                        <td ></td>
                        <td ></td>
                        <td colspan='' >` + sub_major_name + `</td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        </tr>`
                        if (sub_major_name == major_name) {
                            // $(`#${str}`).after(row)
                        } else {

                            $('#fur_table tbody').append(row)
                        }

                        var items = res[division_name][mfo_name][major_name][sub_major_name]
                        for (var items_loop = 0; items_loop < items.length; items_loop++) {
                            var uacs = res[division_name][mfo_name][major_name][sub_major_name][items_loop]['uacs']
                            var general_ledger = res[division_name][mfo_name][major_name][sub_major_name][items_loop]['general_ledger']
                            var ors_to_date = res[division_name][mfo_name][major_name][sub_major_name][items_loop]['ors_to_date']
                            var allotment = res[division_name][mfo_name][major_name][sub_major_name][items_loop]['allotment']
                            row = `<tr class='data_row'>
                            <td ></td>
                            <td ></td>
                            <td colspan='' >` + uacs + '-' + general_ledger + `</td>
                            <td ></td>
                            <td >` + allotment + `</td>
                            <td >` + ors_to_date + `</td>
                            <td ></td>
                            <td ></td>
                            <td ></td>
                        </tr>`
                            $('#fur_table tbody').append(row)


                        }
                    }

                }
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
            var utilization = to_date / beginning_balance
            var balance = beginning_balance - to_date
            var row = `<tr>
                <td>` + conso[i]['mfo_name'] + `</td>
                <td>` + conso[i]['document'] + `</td>
                <td class='amount'>` + thousands_separators(beginning_balance) + `</td>
                <td class='amount'>` + thousands_separators(prev) + `</td>
                <td class='amount'>` + thousands_separators(current) + `</td>
                <td class='amount'>` + thousands_separators(to_date) + `</td>
                <td class='amount'>` + thousands_separators(balance) + `</td>
                <td class='amount'>` + thousands_separators(utilization) + `</td>
            </tr>`
            $('#summary_table tbody').append(row)
            total_beginning_balance += beginning_balance
            total_prev += prev
            total_current += current
            total_to_date += to_date

            total_balance += balance
        }
        total_utilization = total_to_date / total_beginning_balance
        row = `<tr>
                <td style='font-weight:bold' colspan='2'>Total</td>
                <td class='amount'>` + thousands_separators(total_beginning_balance.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_prev.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_current.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_to_date.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_balance.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_utilization.toFixed(2)) + `</td>
            </tr>`
        $('#summary_table tbody').append(row)
    }
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
JS;
$this->registerJs($script);
?>