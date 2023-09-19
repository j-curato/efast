<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Alphalist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alphalist-form card" style="padding: 1rem;">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'check_range')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months'
                ]
            ]) ?>
        </div>
        <?php
        if (Yii::$app->user->can('ro_accounting_admin')) { ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'province')->widget(Select2::class, [
                    'data' => [
                        'adn' => 'ADN',
                        'ads' => 'ADS',
                        'sdn' => 'SDN',
                        'sds' => 'SDS',
                        'pdi' => 'pdi',
                    ],
                    'pluginOptions' => [
                        'placeholder' => 'Select Province'
                    ]
                ]) ?>
            </div>
        <?php } ?>

        <div class="col-sm-3">
            <button class="btn btn-warning" type='button' id="generate" style="margin-top: 24px;">Generate</button>

            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => "margin-top: 24px;"]) ?>
        </div>
    </div>





    <?php ActiveForm::end(); ?>
    <div class="con">

        <table class="" id="conso_table">
            <tbody></tbody>
        </table>
        <table class="" id="detailed_table" style="margin-top: 5rem;">
            <thead>
                <th>DV Number</th>
                <th>Check Date</th>
                <th>Check Number</th>
                <th>Payee</th>
                <th>Gross Amount</th>
                <th>Withdrawals</th>
                <th>Liquidation Damages</th>
                <th>Total Sales Tax (VAT/Non-VAT)</th>
                <th>Income Tax (Expanded Tax)</th>
                <th>Total Tax</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>
<style>
    #detailed_table {
        width: 100%;
    }

    #conso_table {
        width: 50%;
    }

    .total {
        font-weight: bold;
    }

    .con {
        background-color: white;
        padding: 2rem;
    }

    th,
    td {
        border: 1px solid black;
        padding: .8rem;
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    @media print {
        .btn {
            display: none
        }

        th,
        td {
            padding: 5px;
            font-size: 10px;
        }
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/alphalistJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {
        $('#generate').click(function() {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=alphalist/generate',
                data: {
                    range: $('#alphalist-check_range').val(),
                    province: $('#alphalist-province').val()
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    // console.log(res)
                    $('#conso_table tbody').html('')
                    $('#detailed_table tbody').html('')
                    header_rows_index = []
                    displayConsoHead(res.r)
                    displayConso(res.conso, res.r)
                    displayDetailed(res.detailed)
                }
            })
        })
    })
</script>