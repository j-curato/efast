<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\FundSourceType;
use app\models\MajorAccounts;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\data\ActiveDataProvider;
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

            <?php
            if (Yii::$app->user->can('super-user')) {

            ?>
                <div class="col-sm-2">
                    <label for="province">Province</label>
                    <?php

                    echo Select2::widget([
                        'name' => 'province',
                        'id' => 'province',
                        'data' => [
                            'all' => 'All',
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
                    ]);

                    ?>
                </div>
            <?php } ?>
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
                <label for="fund_source_type">Fund Source Type</label>
                <?php
                echo Select2::widget([
                    'name' => 'fund_source_type',
                    'id' => 'fund_source_type',
                    'data'=>ArrayHelper::map(FundSourceType::find()->asArray()->all(),'name','name'),
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
                <th>Date</th>
                <th>Province</th>
                <th>Fund Source</th>
                <th>Beginning Balance</th>
                <th>Cash Advance for the Period</th>
                <th>Total Liquidation for the Month</th>
                <th>Ending Balance</th>
                <th>Particulars</th>
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

    /* #con {
        display: none;
    } */

    .amount {
        text-align: right;
    }

    @media print {

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
            url: window.location.pathname + '?r=report/fund-source-fur',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                var detailed = res.detailed
                var conso = res.conso
                console.log(conso)
                addData(detailed)
                addToSummaryTable(conso)
                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function addData(res) {
        $("#fur_table tbody").html('');
        var year_object = Object.keys(res)
        // console.log(res[2021]['2021-03'].length)
        for (var i = 0; i < year_object.length; i++) {
            var year = year_object[i]
            row = `<tr class='data_row'>
                        <td colspan='8' style='text-align:left'>` + 'Budget Year ' + year + `</td>
                        </tr>`
            $('#fur_table tbody').append(row)
            var reporting_period_keys = Object.keys(res[year])
            var total_witdrawal = 0
            var total_amount = 0
            var total_balance = 0
            var total_begin_balance = 0
            for (var x = 0; x < reporting_period_keys.length; x++) {

                // console.log(res[object[i]][year[x]])
                var reporting_period = reporting_period_keys[x]

                for (var y = 0; y < res[year][reporting_period].length; y++) {

                    row = `<tr class='data_row'>
                        <td>` + res[year][reporting_period][y]['reporting_period'] + `</td>
                        <td>` + res[year][reporting_period][y]['province'] + `</td>
                        <td>` + res[year][reporting_period][y]['fund_source'] + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[year][reporting_period][y]['begin_balance'])) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[year][reporting_period][y]['amount'])) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[year][reporting_period][y]['total_withdrawals'])) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[year][reporting_period][y]['balance'])) + `</td>
                        <td>` + res[year][reporting_period][y]['particular'] + `</td>
                    </tr>`
                    $('#fur_table tbody').append(row)
                    total_witdrawal += parseFloat(res[year][reporting_period][y]['total_withdrawals'])
                    total_amount += parseFloat(res[year][reporting_period][y]['amount'])
                    total_balance += parseFloat(res[year][reporting_period][y]['balance'])
                    total_begin_balance += parseFloat(res[year][reporting_period][y]['begin_balance'])
                }
            }
            row = `<tr class='data_row'>
                        <td colspan='3'>Total</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_begin_balance.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_amount.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_witdrawal.toFixed(2))) + `</td>
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
           var  row = `<tr>
                <td>` + province + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_begin_balance']) + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_amount']) + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_withdrawals']) + `</td>
                <td class='amount'>` + thousands_separators(conso[province]['grand_total_balance']) + `</td>
            </tr>`
            $('#summary_table tbody').append(row)
            total_begin_balance += parseFloat(conso[province]['grand_total_begin_balance'])
            total_amount += parseFloat(conso[province]['grand_total_amount'])
            total_withdrawals += parseFloat(conso[province]['grand_total_withdrawals'])
            total_balance += parseFloat(conso[province]['grand_total_balance'])
        }
        row = `<tr>
                <td>Total</td>
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