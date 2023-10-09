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
            <?php $user = Yii::$app->user->can('ro_budget_admin');
            if ($user) { ?>
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
            <?php }; ?>
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

        <table class="" id="fur_table" style="margin-top: 30px;">
            <thead>
                <tr>

                    <th>Division</th>
                    <th style="width: 250px;">MFO/PAP </th>
                    <th>Account</th>
                    <th>Beginning Balance</th>
                    <th>Allotment Recieved</th>
                    <th>Obligation Incured</th>
                    <th> Balance</th>
                    <th> FUR%</th>
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
    var mfo = []
    var allotment_balances = []
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
                mfo = res.mfo_pap
                allotment_balances = res.allotments
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
                var total_allotment = 0
                var total_ors = 0
                var total_begin_balance = 0
                var total_to_date = 0
                var qqq = 1

                var str = mfo_name.toLowerCase().replace(/\(.*?\)/g, "-");
                str = mfo_loop + '_' + str.replace(/[\. ,:-]+/g, "-")
                row = `<tr class='data_row'  id='${str}'>
                <td colspan='' style='font-weight:bold;background-color:#cccccc' class='major-header'>` + division_name.toUpperCase() + `</td>
                      <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc' >` + mfo[mfo_name][0]['code'] +' - '+mfo_name+ `</td>
                    <td colspan='' style='text-align:left;font-weight:bold;background-color:#cccccc' class='major-header'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                        </tr>`

                $('#fur_table tbody').append(row)
                var document_keys = Object.keys(res[division_name][mfo_name])
                for (var document_loop = 0; document_loop < document_keys.length; document_loop++) {
                    var document_name = document_keys[document_loop];

                    row = `<tr class='data_row' >
                    <td colspan='' style='text-align:left;font-weight:bold;background-color:#cccccc'>` + document_name + `</td>
                    <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        </tr>`
                    $('#fur_table tbody').append(row)
                    var major = res[division_name][mfo_name][document_name]
                    for (var major_loop = 0; major_loop < major.length; major_loop++) {
                        var major_name = res[division_name][mfo_name][document_name][major_loop]['major_name'];
                        var allotment = res[division_name][mfo_name][document_name][major_loop]['allotment'];
                        var current_total_ors = res[division_name][mfo_name][document_name][major_loop]['current_total_ors'];
                        var prev_total_ors = res[division_name][mfo_name][document_name][major_loop]['prev_total_ors'];
                        var balance = res[division_name][mfo_name][document_name][major_loop]['balance'];
                        var begin_balance = res[division_name][mfo_name][document_name][major_loop]['begin_balance'];
                        var ors_to_date = res[division_name][mfo_name][document_name][major_loop]['ors_to_date'];
                        var utilization = res[division_name][mfo_name][document_name][major_loop]['utilization'];
                        // var utilization = ors_to_date / allotment
                        if (utilization == null) {
                            utilization = 0
                        }

                        row = `<tr class='data_row'>
                      
                        <td colspan=''  >` + major_name + `</td>
                        <td class='amount'>` + thousands_separators(begin_balance) + `</td>
                        <td class='amount'>` + thousands_separators(allotment) + `</td>
                        <td class='amount'>` + thousands_separators(current_total_ors) + `</td>
                        <td class='amount'>` + thousands_separators(balance) + `</td>
                        <td class='amount'>` + thousands_separators(utilization) + '%' + `</td>
                        </tr>`

                        $('#fur_table tbody').append(row)
                        total_allotment += parseFloat(allotment)
                        total_ors += parseFloat(current_total_ors)
                        total_begin_balance += parseFloat(begin_balance)
                        total_to_date += parseFloat(ors_to_date)
                        qqq++

                    }
                    qqq++
                    var mfo_description = `<td rowspan='${major.length}'></td>`




                }
                row = `<tr class='data_row'>
                        <td rowspan='${qqq}'></td>
                        <td rowspan='${qqq}' style='padding:5px;text-align:left'>` + mfo[mfo_name][0]['description'] + `</td>
             
                        </tr>`

                $(`#${str}`).after(row)
                var ut = parseFloat(total_to_date) / parseFloat(total_allotment)
                row = `<tr class='data_row'>
                        <td ></td>
                        <td ></td>
                        <td style='font-weight:bold'>Total</td>
                        <td class='amount'>` + thousands_separators(total_begin_balance) + `</td>
                        <td class='amount'>` + thousands_separators(total_allotment) + `</td>
                        <td class='amount'>` + thousands_separators(total_ors) + `</td>
                        <td class='amount'>` + thousands_separators(total_begin_balance - total_ors) + `</td>
                        <td class='amount'>`  + thousands_separators(ut)+ '%' + `</td>
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