<?php

use app\models\DvAucs;
use app\models\PoTransmittal;
use kartik\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Fund Source";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

    <div class="container">


        <form id='filter'>

            <div class="row">
                <div class="col-sm-3">
                    <label for="year">Year</label>
                    <?php
                    echo Select2::widget([
                        'name' => 'province',
                        'data' => [
                            'adn' => 'ADN',
                            'ads' => 'ADS',
                            'pdi' => 'PDI',
                            'sdn' => 'SDN',
                            'sds' => 'SDS',
                        ],
                        'pluginOptions' => [
                            'placeholder' => 'Select Province'
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="year">Year</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'year',
                        'pluginOptions' => [
                            'format' => 'yyyy',
                            'autoclose' => true,
                            'minViewMode' => 'years'
                        ]
                    ])
                    ?>
                </div>

                <div class="col-sm-2">
                    <button class="btn btn-primary" type="submit" style="margin-top: 23px;">
                        Generate
                    </button>
                </div>
            </div>
        </form>

        <table id="data_table">
            <thead>
                <th>Fund Source</th>
                <th>Fund Source Amount</th>
                <th>Withdrawals</th>
                <th>Balance</th>
                <th>Utilization</th>

            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    #data_table {
        margin-top: 3rem;
    }

    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }

    .amount {
        text-align: right;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        $('#filter').submit(function(e) {
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $('#filter').serialize(),
                success: function(data) {
                    displayData(JSON.parse(data))
                }
            })
        })
    })

    function displayData(data) {
        $('#data_table tbody').html('')
        let total_amount = 0
        let total_withdrawals = 0
        let total_balannce = 0
        let total_utilization = 0
        $.each(data, function(key, val) {
            const amount = parseFloat(val.amount)
            const withdrawals = parseFloat(val.withdrawals)
            const balance = parseFloat(val.balance)
            const utilization = parseFloat(val.utilization)

            total_amount += amount
            total_withdrawals += withdrawals
            total_balannce += balance
            const data_row = `<tr>
            <td>${val.fund_source}</td>
            <td class='amount'>${thousands_separators(amount.toFixed(2))}</td>
            <td class='amount'>${thousands_separators(withdrawals.toFixed(2))}</td>
            <td class='amount'>${thousands_separators(balance.toFixed(2))}</td>
            <td class='amount'>${thousands_separators(utilization.toFixed(2))}%</td>
        
            </tr>`;
            $('#data_table tbody').append(data_row)
        })

        total_utilization = (total_withdrawals / total_amount) * 100
        const total_row = `<tr>
            <td style='font-weight:bold;text-align:center'>Total</td>
            <td class='amount' style='font-weight:bold'>${thousands_separators(total_amount.toFixed(2))}</td>
            <td class='amount' style='font-weight:bold'>${thousands_separators(total_withdrawals.toFixed(2))}</td>
            <td class='amount' style='font-weight:bold'>${thousands_separators(total_balannce.toFixed(2))}</td>
            <td class='amount' style='font-weight:bold'>${thousands_separators(total_utilization.toFixed(2))}%</td>
        
            </tr>`;
        $('#data_table tbody').append(total_row)
    }
</script>