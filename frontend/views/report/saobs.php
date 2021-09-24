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
                <label for="mfo_code">MFO/PAP Code</label>
                <?php

                $data = Yii::$app->db->createCommand("SELECT code,CONCAT(code,'-',`name`) as new_text FROM mfo_pap_code ")->queryAll();
                // ->where('code IN (100000100001000,)')
                // var_dump($data);
                echo Select2::widget([
                    'name' => 'mfo_code',
                    'id' => 'mfo_code',
                    'data' => ArrayHelper::map($data, 'code', 'new_text'),
                    'options' => ['placeholder' => 'Select MFO/PAP'],

                ]);
                ?>
            </div>
            <div class="col-sm-2">
                <label for="document_recieve">Document Recive</label>
                <?php

                $data = Yii::$app->db->createCommand("SELECT id,`name`FROM document_recieve ")->queryAll();
                // ->where('code IN (100000100001000,)')
                // var_dump($data);
                echo Select2::widget([
                    'name' => 'document_recieve',
                    'id' => 'document_recieve',
                    'data' => ArrayHelper::map($data, 'name', 'name'),
                    'options' => ['placeholder' => 'Search for a Fund Source ...'],

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
                <th>Province</th>
                <th>Beginning Balance</th>
                <th>Cash Advance for the period</th>
                <th>Total Liquidation For the Month</th>
                <th>Ending Balance</th>
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
            url: window.location.pathname + '?r=report/saobs',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                // var detailed = res.detailed
                // var conso = res.conso
                console.log(res.major_allotments)
                // console.log(res.result)
                addData(res.result, res.major_allotments)
                // addToSummaryTable(conso)
                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function addData(res, major) {
        $("#fur_table tbody").html('');
        var major_keys = Object.keys(res)
        var major_allotments = []
        for (var i = 0; i < major_keys.length; i++) {
            var major_name = major_keys[i]
            row = `<tr class='data_row'>
                        <td colspan='' style='text-align:left;font-weight:bold'>` + major_name + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
            $('#fur_table tbody').append(row)
            var sub_major_keys = Object.keys(res[major_name])
            var total_allotment = 0
            var total_prev = 0
            var total_current = 0
            var total_to_date = 0
            var total_balance = 0

            for (var x = 0; x < sub_major_keys.length; x++) {

                var sub_major_name = sub_major_keys[x]
                row = `<tr class='data_row'>
                        <td colspan='' style='text-align:center;font-weight:bold'>` + sub_major_name + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
                $('#fur_table tbody').append(row)
                var uacs_keys = Object.keys(res[major_name][sub_major_name])
                for (var y = 0; y < uacs_keys.length; y++) {
                    var uacs = uacs_keys[y]

                    var allotment = parseFloat(res[major_name][sub_major_name][uacs]['total_allotment'])
                    var prev = parseFloat(res[major_name][sub_major_name][uacs]['prev_total'])
                    var current = parseFloat(res[major_name][sub_major_name][uacs]['current_total'])
                    var to_date = parseFloat(res[major_name][sub_major_name][uacs]['ors_to_date'])
                    var balance = 0
                    if (allotment == 0) {
                        console.log()
                        var major_amount = major[res[major_name][sub_major_name][uacs]['major_object_code']]
                        balance = major_amount - to_date
                        major[res[major_name][sub_major_name][uacs]['major_object_code']] = balance
                    } else if (uacs == 5010000000 || uacs == 5020000000 || uacs == 5060000000) {
                        balance = 0
                    } else {

                        balance = allotment - to_date
                    }
                    row = `<tr class='data_row'>
                        <td style ='text-align:right'>` + uacs + ' ' + res[major_name][sub_major_name][uacs]['general_ledger'] + `</td>
                        <td class='amount'>` + thousands_separators(allotment) + `</td>
                        <td class='amount'>` + thousands_separators(prev) + `</td>
                        <td class='amount'>` + thousands_separators(current) + `</td>
                        <td class='amount'>` + thousands_separators(to_date) + `</td>
                        <td class='amount'>` + thousands_separators(balance) + `</td>
                        <td></td>
                    </tr>`
                    $('#fur_table tbody').append(row)
                   
                    total_allotment +=allotment
                    total_prev +=prev
                    total_current +=current
                    total_to_date +=to_date
                    total_balance +=balance
                }
            }
            row = `<tr class='data_row'>
                        <td colspan='' style='font-weight:bold'>Total</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_allotment.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_prev.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_current.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_to_date.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_balance.toFixed(2))) + `</td>
                        <td ></td>
                        </tr>`
            $('#fur_table tbody').append(row)

        }


    }

    function addToSummaryTable(conso) {
        $('#summary_table tbody').html('')
        var conso_object = Object.keys(conso)
        var total_begin_balance = 0
        var total_amount = 0
        var total_withdrawals = 0
        var total_balance = 0
        for (var i = 0; i < conso_object.length; i++) {
            var province = conso_object[i]
            var row = `<tr>
                <td>` + province + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_begin_balance']) + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_cash_advances_for_the_period']) + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_withdrawals']) + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_balance']) + `</td>
            </tr>`
            $('#summary_table tbody').append(row)
            total_begin_balance += parseFloat(conso[province]['grand_total_begin_balance'])
            total_amount += parseFloat(conso[province]['grand_total_cash_advances_for_the_period'])
            total_withdrawals += parseFloat(conso[province]['grand_total_withdrawals'])
            total_balance += parseFloat(conso[province]['grand_total_balance'])
        }
        row = `<tr>
                <td style='font-weight:bold'>Total</td>
                <td class='amount'>` + thousands_separators(total_begin_balance.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_amount.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_withdrawals.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_balance.toFixed(2)) + `</td>
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