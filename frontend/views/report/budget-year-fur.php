<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;


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
            $user_province =  strtolower(Yii::$app->user->identity->province);
            if (
                Yii::$app->user->can('super-user') ||
                $user_province === 'ro'
            ) {

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
                <?php

                if (
                    Yii::$app->user->can('super-user') ||
                    $user_province === 'adn' ||
                    $user_province === 'ads' ||
                    $user_province === 'pdi' ||
                    $user_province === 'sdn' ||
                    $user_province === 'sds'
                ) {
               
                echo " <label for='division'>Division</label>";
                    echo Select2::widget([
                        'name' => 'division',
                        'id' => 'division',
                        'data' => [
                            'all' => 'All',
                            'idd' => 'IDD',
                            'sdd' => 'SDD',
                            'cpd' => 'CPD',
                            'fad' => 'FAD',
                            'ord' => 'ORD',
                        ],
                        'options' => ['placeholder' => 'Select a Division'],

                    ]);
                }

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
                <th>Division</th>
                <th>Beginning Balance</th>
                <th>Cash Advance for the Period</th>
                <th>Total Liquidation for the Month</th>
                <th>Ending Balance</th>
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
    var province_full = {
        'adn': 'Agusan Del Norte',
        'ads': 'Agusan Del Sur',
        'sdn': 'Surigao Del Norte',
        'sds': 'Surigao Del Sur',
        'pdi': 'Province of Dinagat Islands',
    }

    $('#generate').click((e) => {
        e.preventDefault();
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=report/budget-year-fur',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                // var conso = res.conso
                console.log(res)
                addData(res)
                // addToSummaryTable(conso)
                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function addData(res) {
        $("#fur_table tbody").html('');
        var province_keys = Object.keys(res)
        // console.log(res[2021]['2021-03'].length)
        for (var i = 0; i < province_keys.length; i++) {
            var province = province_keys[i]
            row = `<tr class='data_row'>
                        <td colspan='1' style='text-align:left;font-weight:bold'>` + province_full[province.toLowerCase()] + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
            $('#fur_table tbody').append(row)
            var division_keys = Object.keys(res[province])

            for (var x = 0; x < division_keys.length; x++) {

                // console.log(res[object[i]][year[x]])
                var division = division_keys[x]
                row = `<tr class='data_row'>
                        <td  style='text-align:left;font-weight:bold'>` + division.toUpperCase() + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
                $('#fur_table tbody').append(row)
                var total_witdrawal = 0
                var total_cash_advances_for_the_period = 0
                var total_balance = 0
                var total_begin_balance = 0

                for (var y = 0; y < res[province][division].length; y++) {

                    row = `<tr class='data_row'>
                        <td></td>
                        <td>` + res[province][division][y]['fund_source_type'] + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[province][division][y]['prev_amount'])) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[province][division][y]['current_advances_amount'])) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[province][division][y]['total_withdrawals'])) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(res[province][division][y]['ending_balance'])) + `</td>
 
                    </tr>`
                    $('#fur_table tbody').append(row)
                    total_witdrawal += parseFloat(res[province][division][y]['total_withdrawals'])
                    total_cash_advances_for_the_period += parseFloat(res[province][division][y]['current_advances_amount'])
                    total_balance += parseFloat(res[province][division][y]['ending_balance'])
                    total_begin_balance += parseFloat(res[province][division][y]['prev_amount'])
                }
                row = `<tr class='data_row'>
                        <td colspan='2' style ='font-weight:bold'>Total</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_begin_balance).toFixed(2)) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_cash_advances_for_the_period).toFixed(2)) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_witdrawal).toFixed(2)) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_balance).toFixed(2)) + `</td>
                 
                        </tr>`
                $('#fur_table tbody').append(row)
            }


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







JS;
$this->registerJs($script);
?>