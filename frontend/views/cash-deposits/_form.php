<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\widgets\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CashDeposits */
/* @var $form yii\widgets\ActiveForm */

$mgrfr = [];

if (!empty($model->fk_mgrfr_id)) {
    $mgrfr[] = $model->mgrfr->getMgrfrDetails();
}
?>

<div class="cash-deposits-form">
    <div class="container card p-2">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'fk_mgrfr_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($mgrfr, 'id', 'serial_number'),
                    'options' => ['placeholder' => 'Search for a Bank ...', 'style' => 'height:30em'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=mgrfrs/search-mgrfr-serial-number',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ])  ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months',
                        'autoclose' => true
                    ]
                ]) ?></div>
            <div class="col-sm-4">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true

                    ]
                ]) ?>
            </div>

            <div class="col-sm-4">
                <?= $form->field($model, 'matching_grant_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>
            <div class="col-sm-4">

                <?= $form->field($model, 'equity_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'other_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'particular')->textarea(['rows' => 4]) ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="form-group col-sm-1">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>