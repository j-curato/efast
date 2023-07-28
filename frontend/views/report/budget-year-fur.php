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
                <?php

                if (Yii::$app->user->can('ro_accounting_admin')||Yii::$app->user->can('po_accounting_admin')
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

        <h3>Summary Table</h3>
        <table class="" id="summary_table" style="margin-top: 30px;">
            <thead>
                <th>Province</th>
                <th>Division</th>
                <th>Beginning Balance</th>
                <th>Cash Advance for the Period</th>
                <th>Total Liquidation for the Month</th>
                <th>Ending Balance</th>
            </thead>
            <tbody>

            </tbody>
        </table>
        <h3>Detailed Table</h3>
        <table class="" id="fur_table" style="margin-top: 30px;">
            <thead>
                <th>Province</th>
                <th>Division</th>
                <th>Fund Source Type</th>
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
        padding: 8px;
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
        'ro': 'RO For Recon',
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
                // console.log(res)
                addData(res.detailed)
                addToSummaryTable(res.conso)
                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function addData(res) {
        $("#fur_table tbody").html('');
        var province_keys = Object.keys(res)
        for (var i = 0; i < province_keys.length; i++) {
            var province = province_keys[i]

            row = `<tr class='data_row'>
                        <td colspan='1' style='text-align:left;font-weight:bold'>` + province_full[province.toLowerCase()] + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
            $('#fur_table tbody').append(row)
            var year_keys = Object.keys(res[province])

            for (var x = 0; x < year_keys.length; x++) {

                // console.log(res[object[i]][year[x]])
                var year = year_keys[x]
                row = `<tr class='data_row'>
                        <td  style='text-align:center;font-weight:bold'>` + year.toUpperCase() + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
                $('#fur_table tbody').append(row)
                var division_keys = Object.keys(res[province][year])

                for (var y = 0; y < division_keys.length; y++) {
                    var division = division_keys[y]
                    // var begining_balance = parseFloat(res[province][year][division]['beginning_balance'])
                    // var current_advances = parseFloat(res[province][year][division]['current_advances_amount'])
                    // var withdrawals = parseFloat(res[province][year][division]['total_withdrawals'])
                    // var ending_balance = parseFloat(res[province][year][division]['ending_balance'])
                    var total_witdrawal_per_division = 0
                    var total_current_advances_per_division = 0
                    var total_ending_balance_per_division = 0
                    var total_begin_balance_per_division = 0
                    row = `<tr class='data_row'>
                    <td></td>
                    <td  style='text-align:left;font-weight:bold'>` + division.toUpperCase() + `</td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                       
                        </tr>`
                    $('#fur_table tbody').append(row)
                    var fund_type_keys = Object.keys(res[province][year][division])
                    for (var qqq = 0; qqq < fund_type_keys.length; qqq++) {
                        var fund_type = fund_type_keys[qqq]
                        var begining_balance = parseFloat(res[province][year][division][fund_type]['beginning_balance'])
                        var current_advances = parseFloat(res[province][year][division][fund_type]['current_advances_amount'])
                        var withdrawals = parseFloat(res[province][year][division][fund_type]['total_withdrawals'])
                        var ending_balance = parseFloat(res[province][year][division][fund_type]['ending_balance'])
                        row = `<tr class='data_row'>
                            <td></td>
                            <td></td>
                            <td  style='text-align:center;font-weight:bold'>` + fund_type.toUpperCase() + `</td>
                            <td class = 'amount'>` + thousands_separators(begining_balance) + `</td>
                            <td class = 'amount'>` + thousands_separators(current_advances) + `</td>
                            <td class = 'amount'>` + thousands_separators(withdrawals) + `</td>
                            <td class = 'amount'>` + thousands_separators(ending_balance) + `</td>
                        
                            </tr>`
                        $('#fur_table tbody').append(row)
                        total_begin_balance_per_division += begining_balance
                        total_current_advances_per_division += current_advances
                        total_witdrawal_per_division += withdrawals
                        total_ending_balance_per_division += ending_balance
                    }
                    row = `<tr class='data_row'>
                        <td colspan='3' style ='font-weight:bold'>Total</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_begin_balance_per_division).toFixed(2)) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_current_advances_per_division).toFixed(2)) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_witdrawal_per_division).toFixed(2)) + `</td>
                        <td class='amount'>` + thousands_separators(parseFloat(total_ending_balance_per_division).toFixed(2)) + `</td>

                        </tr>`
                    $('#fur_table tbody').append(row)


                }


            }



        }

    }

    function addToSummaryTable(res) {
        $('#summary_table tbody').html('')
        var province_keys = Object.keys(res)
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
            $('#summary_table tbody').append(row)
            var year_keys = Object.keys(res[province])
            var total_begining_balance = 0
            var total_current_advances = 0
            var total_withdrawals = 0
            var total_ending_balance = 0
            for (var x = 0; x < year_keys.length; x++) {

                // console.log(res[object[i]][year[x]])
                var year = year_keys[x]
                row = `<tr class='data_row'>
                        <td  style='text-align:center;font-weight:bold'>` + year.toUpperCase() + `</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>`
                $('#summary_table tbody').append(row)
                var division_keys = Object.keys(res[province][year])

                for (var y = 0; y < division_keys.length; y++) {
                    var division = division_keys[y]
                    var begining_balance = parseFloat(res[province][year][division]['beginning_balance'])
                    var current_advances = parseFloat(res[province][year][division]['current_advances_amount'])
                    var withdrawals = parseFloat(res[province][year][division]['total_withdrawals'])
                    var ending_balance = parseFloat(res[province][year][division]['ending_balance'])

                    row = `<tr class='data_row'>
                    <td></td>
                    <td  style='text-align:left;font-weight:bold'>` + division.toUpperCase() + `</td>
                        <td  class = 'amount'>` + thousands_separators(begining_balance) + `</td>
                        <td  class = 'amount'>` + thousands_separators(current_advances) + `</td>
                        <td  class = 'amount'>` + thousands_separators(withdrawals) + `</td>
                        <td  class = 'amount'>` + thousands_separators(ending_balance) + `</td>
                        </tr>`
                    $('#summary_table tbody').append(row)
                    total_begining_balance += begining_balance
                    total_current_advances += current_advances
                    total_withdrawals += withdrawals
                    total_ending_balance += ending_balance

                }


            }
            row = `<tr class='data_row'>
                    <td colspan='2' style='font-weight:bold'>Total</td>
                        <td  class = 'amount'>` + thousands_separators(total_begining_balance) + `</td>
                        <td  class = 'amount'>` + thousands_separators(total_current_advances) + `</td>
                        <td  class = 'amount'>` + thousands_separators(total_withdrawals) + `</td>
                        <td  class = 'amount'>` + thousands_separators(total_ending_balance) + `</td>
                        </tr>`
            $('#summary_table tbody').append(row)



        }
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