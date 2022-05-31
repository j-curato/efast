<?php

use app\models\Books;
use app\models\DvAucs;
use app\models\PoTransmittal;
use app\models\ReportType;
use kartik\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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
                    <label for="province">Province</label>
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
                    <label for="book">Book</label>
                    <?php

                    echo Select2::widget([
                        'name' => 'book',
                        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                        'pluginOptions' => [
                            'placeholder' => 'Report Type'
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="reporting_period">Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'autoclose' => true,
                            'minViewMode' => 'months'
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
                <th>Fund Source Type</th>
                <th>Target for Liquidation</th>
                <th>Actual Liquidation</th>
                <th>Variance</th>

            </thead>
            <tbody>

            </tbody>
        </table>
        <div id="dots5">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
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

    th {
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    #dots5 {
        display: none;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        $('#filter').submit(function(e) {
            $('#data_table').hide()
            $('#dots5').show()
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $('#filter').serialize(),
                success: function(data) {
                    displayData(JSON.parse(data))

                    $('#data_table').show()
                    $('#dots5').hide()
                }
            })
        })
    })

    function displayData(data) {
        console.log(data)
        $('#data_table tbody').html('')
        let total_target_liquidation = 0
        let total_actual_liquidation = 0
        let total_variance = 0
        $.each(data, function(key, val) {

            


            const fund_source_type = val.fund_source_type
            const actual_liquidation = parseFloat(val.actual_liquidation)
            const target_liquidation = parseFloat(val.target_liquidation)
            const variance = parseFloat(val.variance)
            const begin_balance = parseFloat(val.begin_balance)
            const total_advances = parseFloat(val.total_advances)
            const balance = parseFloat(val.balance)
            total_target_liquidation += target_liquidation
            total_actual_liquidation += actual_liquidation
            total_variance += variance
            const data_row = `<tr>
            <td>${fund_source_type}</td>
            <td class='amount'>${thousands_separators(target_liquidation.toFixed(2))}</td>
            <td class='amount'>${thousands_separators(actual_liquidation.toFixed(2))}</td>
            <td class='amount'>${thousands_separators(variance.toFixed(2))}</td>
        
            </tr>`;
            $('#data_table tbody').append(data_row)
        })

        // if (total_withdrawals != 0 && total_amount != 0)
        //     total_utilization = (total_withdrawals / total_amount) * 100
        const total_row = `<tr>
            <td style='font-weight:bold;text-align:center'>Total</td>
            <td class='amount' style='font-weight:bold'>${thousands_separators(total_target_liquidation.toFixed(2))}</td>
            <td class='amount' style='font-weight:bold'>${thousands_separators(total_actual_liquidation.toFixed(2))}</td>
            <td class='amount' style='font-weight:bold'>${thousands_separators(total_variance.toFixed(2))}</td>
            </tr>`;
        $('#data_table tbody').append(total_row)
    }
</script>