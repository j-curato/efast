<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
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
                    'initValueText' => 1001,
                    'options' => [ 'placeholder' => 'Search for a Fund Source ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=fund-source-type/search',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-3" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>

    </form>

    <!-- <div id="con"> -->

    <div id='con'>

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
                addData(res)
                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function addData(res) {
        console.log(res)
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
            for (var x = 0; x < reporting_period_keys.length; x++) {

                // console.log(res[object[i]][year[x]])
                var reporting_period = reporting_period_keys[x]

                for (var y = 0; y < res[year][reporting_period].length; y++) {

                    row = `<tr class='data_row'>
                        <td>` + res[year][reporting_period][y]['reporting_period'] + `</td>
                        <td>` + res[year][reporting_period][y]['province'] + `</td>
                        <td>` + res[year][reporting_period][y]['fund_source'] + `</td>
                        <td class='amount'>` +thousands_separators(parseFloat(res[year][reporting_period][y]['begin_balance']))  + `</td>
                        <td class='amount'>` +thousands_separators(parseFloat(res[year][reporting_period][y]['amount']))  + `</td>
                        <td class='amount'>` +thousands_separators(parseFloat(res[year][reporting_period][y]['total_withdrawals']))  + `</td>
                        <td class='amount'>` +thousands_separators(parseFloat(res[year][reporting_period][y]['balance']))  + `</td>
                        <td>` + res[year][reporting_period][y]['particular'] + `</td>
                    </tr>`
                    $('#fur_table tbody').append(row)
                    total_witdrawal += parseFloat(res[year][reporting_period][y]['total_withdrawals'])
                    total_amount += parseFloat(res[year][reporting_period][y]['amount'])
                    total_balance += parseFloat(res[year][reporting_period][y]['balance'])
                }
            }
            row = `<tr class='data_row'>
                        <td colspan='4'>Total</td>
                        <td class='amount'>` +thousands_separators(parseFloat(total_amount.toFixed(2)))  + `</td>
                        <td class='amount'>` +thousands_separators(parseFloat(total_witdrawal.toFixed(2)))  + `</td>
                        <td class='amount'>` +thousands_separators(parseFloat(total_balance.toFixed(2)))  + `</td>
                        <td ></td>
                        </tr>`
            $('#fur_table tbody').append(row)

        }

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