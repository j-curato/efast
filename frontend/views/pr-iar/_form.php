<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrIar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-iar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, '_date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]) ?>

    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'pluginOptions' => [
            'minViewMode' => 'months',
            'autoclose' => true,
            'format' => 'yyyy-mm'
        ]
    ]) ?>

    <?= $form->field($model, 'invoice_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'autoclose' => true
        ]

    ]) ?>

    <?= $form->field($model, 'fk_pr_purchase_order_id')->widget(Select2::class, [
        'options' => ['placeholder' => 'Search for a Employee ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=pr-purchase-order/search-purchase-order',
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
        ],
    ]) ?>


    <?= $form->field($model, 'fk_insepection_officer')->textInput() ?>

    <?= $form->field($model, 'fk_property_custodian')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>