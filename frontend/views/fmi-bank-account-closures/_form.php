<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Office;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;
// use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankAccountClosure */
/* @var $form yii\widgets\ActiveForm */

$subprojectData = [
    [
        'id' => $model->fk_fmi_subproject_id ?? null,
        'serial_number' => $model->fmiSubproject->serial_number ?? null
    ]
];
?>

<div class="fmi-bank-account-closure-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>



    <?= $form->field($model, 'fk_office_id')->dropDownList(
        ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
        ['prompt' => 'Select Office']
    ) ?>
    <div class="col-12">
        <?= $form->field($model, 'fk_fmi_subproject_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($subprojectData, 'id', 'serial_number'),
            'options' => ['placeholder' => 'Search for Subproject ...', 'style' => 'height:30em'],
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
    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'pluginOptions' => [
            'format' => 'yyyy-mm',
            'todayHighlight' => true,
            'autoclose' => true,
            'todayHighlight' => true,
            'minViewMode' => 'months'
        ]
    ]) ?>
    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'autoclose' => true,
            'todayBtn' => true,
            'todayHighlight' => true,
        ]
    ]) ?>
    <?= $form->field($model, 'bank_certification_link')->textarea(['rows' => 6]) ?>

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
        $("#FmiBankAccountClosures").on("beforeSubmit", function(event) {
            event.preventDefault();
            var form = $(this);
            ajaxSubmit(form)
            return false;
        });
      
    })
JS;
$this->registerJs($js);

?>