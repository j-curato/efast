<?php

use app\models\Books;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Annex 3";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">
    <form id='filter'>
        <div class="row">
            <div class="col-sm-2">
                <label for="from_reporting_period">From Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'from_reporting_period',
                    'name' => 'from_reporting_period',
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
            <!-- <div class="col-sm-2">
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
            </div> -->
            <!-- <div class="col-sm-3">
                <label for="book">Book</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'name', 'name'),
                    'id' => 'book',
                    'name' => 'book',
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <label for="report_type">Advance Type</label>
                <?php

                echo Select2::widget([
                    'data' => [
                        'Advances for Operating Expenses' => 'OPEX',
                        'Advances to Special Disbursing Officer' => 'SDO'
                    ],
                    'name' => 'report_type',
                    'id' => 'report_type',
                    'pluginOptions' => [
                        'placeholder' => 'Select Advance Type'
                    ]
                ])
                ?>
            </div> -->
            <div class="col-sm-2">
                <button class="btn btn-success" id="generate" style="margin-top:23px">Generate</button>
            </div>
        </div>
    </form>


    <table id="annex_table">
        <thead>
            <th>Report Type</th>
            <th>Account Code</th>
            <th>Name</th>
            <th>Date CA Granted</th>
            <th>Particulars</th>
            <th>Reference (Check/ADA NO.)</th>
            <th>Total Amount</th>
            <th>Liquidation Previous Month</th>
            <th>Liquidation for the Month</th>
            <th>Total Liquidation</th>
            <th>Unliquidation as of to date</th>
            <th>Less than 30 days</th>
            <th>31-60 days</th>
        </thead>
        <tbody>

        </tbody>

    </table>
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<style>
    .amount {
        text-align: right;
        padding: 8px;
    }
table{
    margin-top:20px
}
    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    .header {
        border: none;
        font-weight: bold;

    }

    .t_head {
        text-align: center;
        font-weight: bold;
    }

    @media print {

        td,
        th {
            font-size: 10px;
            padding: 2px;
        }

        .amount {
            padding: 5px;
        }

        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>

<script>
    $('#generate').click((e) => {
        e.preventDefault()
        $('#annex_table').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + "?r=report/annex3",
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                console.log(res)
                displaData(res)
                $('#dots5').hide()
                $('#annex_table').show()
            }
        })
    })

    function displaData(data) {

        for (var i = 0; i < data.length; i++) {

            var report_type = data[i]['report_type']
            var account_name = data[i]['account_name']
            var check_number = data[i]['check_number']
            var check_date = data[i]['check_date']
            var particular = data[i]['particular']
            var advances_amount = data[i]['advances_amount']
            var current_liquidation = data[i]['current_liquidation']
            var prev_liquidation = data[i]['prev_liquidation']
            var total_liquidation_this_date = data[i]['total_liquidation_this_date']
            var unliquidated = data[i]['unliquidated']
            var row = `<tr class='data_row'>
                <td  >` + report_type + `</td>
                <td  >` + account_name + `</td>
                <td></td>
                <td  >` + check_date + `</td>
                <td  >` + particular + `</td>
                <td  >` + check_number + `</td>
                <td class='amount' >` + thousands_separators(advances_amount) + `</td>
                <td class='amount' >` + thousands_separators(prev_liquidation) + `</td>
                <td class='amount' >` + thousands_separators(current_liquidation) + `</td>
                <td class='amount' >` + thousands_separators(total_liquidation_this_date) + `</td>
                <td class='amount' >` + thousands_separators(unliquidated) + `</td>
                <td></td>
                <td  >` + '' + `</td>
                </tr>`
            $('#annex_table tbody').append(row)

        }

    }
</script>


<?php
SweetAlertAsset::register($this);
$script = <<< JS

JS;
$this->registerJs($script);
?>