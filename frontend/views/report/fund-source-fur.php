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
            if (Yii::$app->user->can('ro_accounting_admin')) {

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
                $data = [];
                if (
                    Yii::$app->user->can('ro_accounting_admin') || Yii::$app->user->can('po_accounting_admin')
                ) {
                    $data = FundSourceType::find()->all();
                } else if (Yii::$app->user->can('department-offices')) {
                    $data = FundSourceType::find()->where(
                        "division = :division",
                        ['division' => Yii::$app->user->identity->division]
                    )->all();
                }
                echo Select2::widget([
                    'name' => 'fund_source_type',
                    'id' => 'fund_source_type',
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
            url: window.location.pathname + '?r=report/fund-source-fur',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                var detailed = res.detailed
                var conso = res.conso
                console.log(conso)
                displayDetailed(detailed)
                addToSummaryTable(conso)
                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function displayDetailed(data) {
        $("#fur_table tbody").html('');
        const data_keys = Object.keys(data)
        for (let i = data_keys.length - 1; i >= 0; i--) {
            let total_current_total_advances = 0;
            let total_current_total_withdrawals = 0;
            let total_begin_balance = 0;
            let total_ending_balance = 0;
            let key = data_keys[i];
            const header_row = `<tr class='data_row'>
                        <td colspan='8' style='text-align:left;font-weight:bold'>` + 'Budget Year ' + key + `</td>
                        </tr>`
            $('#fur_table tbody').append(header_row)
            $.each(data[key], function(key2, val2) {
                const current_total_advances = parseFloat(val2['current_total_advances'])
                const current_total_withdrawals = parseFloat(val2['current_total_withdrawals'])
                const begin_balance = parseFloat(val2['begin_balance'])
                const ending_balance = (begin_balance + current_total_advances) - current_total_withdrawals
                let row = `<tr class='data_row'>
                        <td>` + val2['reporting_period'] + `</td>
                        <td>` + val2['province'] + `</td>
                        <td>` + val2['fund_source'] + `</td>
                        <td class='amount'>` + thousands_separators(begin_balance) + `</td>
                        <td class='amount'>` + thousands_separators(current_total_advances) + `</td>
                        <td class='amount'>` + thousands_separators(current_total_withdrawals) + `</td>
                        <td class='amount'>` + thousands_separators(ending_balance) + `</td>
                        <td>` + val2['particular'] + `</td>
                    </tr>`
                $('#fur_table tbody').append(row)

                total_current_total_advances += +current_total_advances;
                total_current_total_withdrawals += +current_total_withdrawals;
                total_begin_balance += +begin_balance;
                total_ending_balance += +ending_balance;

            })
            const total_row = `<tr class='data_row'>
                        <td colspan='3' style='font-weight:bold'>Total</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_begin_balance.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_current_total_advances.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_current_total_withdrawals.toFixed(2))) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_ending_balance.toFixed(2))) + `</td>
                        <td ></td>
                        </tr>`
            $('#fur_table tbody').append(total_row)


        }
    }


    function addToSummaryTable(conso) {
        $('#summary_table tbody').html('')
        var conso_object = Object.keys(conso)
        let total_begin_balance = 0
        let total_amount = 0
        let total_withdrawals = 0
        let total_balance = 0
        for (var i = 0; i < conso_object.length; i++) {
            var province = conso_object[i]
            let grand_total_begin_balance = parseFloat(conso[province]['grand_total_begin_balance']);
            let grand_total_cash_advances_for_the_period = parseFloat(conso[province]['grand_total_cash_advances_for_the_period']);
            let grand_total_withdrawals = parseFloat(conso[province]['grand_total_withdrawals']);
            let ending_balance = (grand_total_begin_balance + grand_total_cash_advances_for_the_period) - grand_total_withdrawals;
            let row = `<tr>
                <td>` + province + `</td>
                <td class='amount'>` + thousands_separators(grand_total_begin_balance) + `</td>
                <td class='amount'>` + thousands_separators(grand_total_cash_advances_for_the_period) + `</td>
                <td class='amount'>` + thousands_separators(grand_total_withdrawals) + `</td>
                <td class='amount'>` + thousands_separators(ending_balance) + `</td>
            </tr>`
            $('#summary_table tbody').append(row)
            total_begin_balance += grand_total_begin_balance
            total_amount += grand_total_cash_advances_for_the_period
            total_withdrawals += grand_total_withdrawals

        }
        total_balance = (total_begin_balance + total_amount) - total_withdrawals;
        let row = `<tr>
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
    const province={
        'adn' : 'Agusan Del Norte',
        'ads' : 'Agusan Del Sur',
        'sdn' : 'Surigao Del Norte',
        'sds' : 'Surigao Del Sur',
        'pdi' : 'Province of Dinagat Islands',
    }







JS;
$this->registerJs($script);
?>