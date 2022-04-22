<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Alphalist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alphalist-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'check_range')->widget(DatePicker::class, [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>
    <?= $form->field($model, 'province')->widget(Select2::class, [
        'data' => [
            'adn' => 'ADN',
            'ads' => 'ADS',
            'sdn' => 'SDN',
            'sds' => 'SDS',
            'pdi' => 'pdi',
        ],
        'pluginOptions'=>[
            'placeholder'=>'Select Province'
        ]
    ]) ?>

    <button class="btn btn-success" type='button' id="generate">Generate</button>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <table class="" id="conso_table">
        <tbody></tbody>
    </table>
    <table class="" id="detailed_table" style="margin-top: 5rem;">
        <thead>
            <th>DV Number</th>
            <th>Check Number</th>
            <th>Payee</th>
            <th>Gross Amount</th>
            <th>Withdrawals</th>
            <th>Total Sales Tax (VAT/Non-VAT)</th>
            <th>Income Tax (Expanded Tax)</th>
            <th>Liquidation Damages</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<style>
    table {
        width: 100%;
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

                    displayConsoHead(res.r)
                    displayConso(res.conso, res.r)
                    displayDetailed(res.detailed)
                }
            })
        })
    })


    
</script>