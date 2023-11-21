<?php

use app\models\Office;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationToPay */
/* @var $form yii\widgets\ActiveForm */

$coordinator = [];
if (!empty($model->fk_coordinator)) {
    $coordinator[] = $model->coordinator->getEmployeeDetails();
}
$provincialDirector = [];
if (!empty($model->fk_provincial_director)) {
    $provincialDirector[] =  $model->provincialDirector->getEmployeeDetails();
}
$dueDiligence = [];
if (!empty($model->fk_due_diligence_report_id)) {
    $dueDiligence[] =  [
        'id' => $model->fk_due_diligence_report_id,
        'serial_number' => $model->dueDiligenceReport->serial_number
    ];
}
?>


<div class="notification-to-pay-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="container card">


        <div class="row">
            <div class="col-4">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [],

                ])  ?>

            </div>

            <div class="col-4">
                <?= $form->field($model, 'fk_due_diligence_report_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($dueDiligence, 'id', 'serial_number'),
                    'options' => ['placeholder' => 'Search for a MG RFR Serial No. ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [

                            'type' => 'GET',
                            'url' => Yii::$app->request->baseUrl . '?r=due-diligence-reports/search-due-diligence-report',
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


        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'fk_coordinator')->widget(Select2::class, [
                    'data' => ArrayHelper::map($coordinator, 'employee_id', 'fullName'),
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
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
            <div class="col-sm-6">
                <?= $form->field($model, 'fk_provincial_director')->widget(Select2::class, [
                    'data' => ArrayHelper::map($provincialDirector, 'employee_id', 'fullName'),
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
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
        </div>
        <div class="row">
            <div class="col-4">
                <?= $form->field($model, 'matching_grant_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ])  ?>
            </div>
            <div class="col-4">
                <?= $form->field($model, 'equity_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ])  ?>
            </div>
            <div class="col-4">
                <?= $form->field($model, 'other_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ])  ?>
            </div>
        </div>
        <div class="row justify-content-center">

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#NotificationToPay").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            swal({
                icon: 'error',
                title: data,
                type: "error",
                timer: 3000,
                closeOnConfirm: false,
                closeOnCancel: false
            })
        },
        error: function (data) {
     
        }
    });
    return false;
});
JS;
$this->registerJs($js);
?>