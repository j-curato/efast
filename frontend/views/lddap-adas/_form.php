<?php

use yii\helpers\Html;
use app\models\Employee;
use yii\web\JsExpression;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LddapAdas */
/* @var $form yii\widgets\ActiveForm */

$approved_by = [];
$cerified_correct_by = [];
$accounting_head = [];
$emp = new Employee();
if (!empty($model->fk_certified_correct_by)) {
    $cerified_correct_by[] =  $emp->getEmployeeById($model->fk_certified_correct_by);
}
if (!empty($model->fk_approved_by)) {
    $approved_by[] =  $emp->getEmployeeById($model->fk_approved_by);
}
if (!empty($model->fk_accounting_head)) {
    $accounting_head[] =  $emp->getEmployeeById($model->fk_accounting_head);
}
?>

<div class="lddap-adas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_certified_correct_by')->widget(Select2::class, [
        'data' => ArrayHelper::map($cerified_correct_by, 'employee_id', 'employee_name'),
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
        'size' => Select2::SMALL

    ]) ?>
    <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
        'data' => ArrayHelper::map($approved_by, 'employee_id', 'employee_name'),
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
        'size' => Select2::SMALL
    ]) ?>
    <?= $form->field($model, 'fk_accounting_head')->widget(Select2::class, [
        'data' => ArrayHelper::map($accounting_head, 'employee_id', 'employee_name'),
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
        'size' => Select2::SMALL
    ]) ?>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>