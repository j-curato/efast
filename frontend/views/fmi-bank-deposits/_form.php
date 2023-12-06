<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Office;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use app\models\FmiBankDepositTypes;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDeposits */
/* @var $form yii\widgets\ActiveForm */

$subprojectData = [
    [
        'id' => $model->fk_fmi_subproject_id ?? null,
        'serial_number' => $model->fmiSubproject->serial_number ?? null
    ]
]
?>

<div class="fmi-bank-deposits-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'deposit_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'autoclose' => true,

                ]
            ]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'minViewMode' => 'months',
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm',
                ]
            ]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'fk_fmi_bank_deposit_type_id')->dropDownList(
                ArrayHelper::map(FmiBankDepositTypes::getAllTypesA(), 'id', 'deposit_type'),
                ['prompt' => 'Select Bank Deposit Type']
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'fk_office_id')->dropDownList(
                ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                ['prompt' => 'Select Office']
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'fk_fmi_subproject_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($subprojectData, 'id', 'serial_number'),
                'options' => ['placeholder' => 'Search for a Bank ...', 'style' => 'height:30em'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['fmi-subprojects/search-subproject']),
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {text:params.term,page:params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'deposit_amount')->widget(MaskMoney::class, [
                'pluginOptions' => [
                    'prefix' => '₱ ',
                    'allowNegative' => false
                ]
            ]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'particular')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
SweetAlertAsset::register($this);
$this->registerJsFile("@web/frontend/modules/js/activeFormAjaxSubmit.js", ['depends' => [JqueryAsset::class]]);
$js = <<<JS

    $(document).ready(function(){
        $("#FmiBankDeposits").on("beforeSubmit", function(event) {
            event.preventDefault();
            var form = $(this);
            ajaxSubmit(form)
            return false;
        });
      
    })
JS;
$this->registerJs($js);

?>