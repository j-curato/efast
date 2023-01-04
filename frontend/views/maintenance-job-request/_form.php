<?php

use app\models\ResponsibilityCenter;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MaintenanceJobRequest */
/* @var $form yii\widgets\ActiveForm */

$requested_by = [];
if (!empty($model->id)) {

    $query = Yii::$app->db->createCommand("SELECT employee_id,employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_employee_id)
        ->queryAll();
    $requested_by = ArrayHelper::map($query, 'employee_id', 'employee_name');
}
?>

<div class="maintenance-job-request-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'fk_responsibility_center_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(ResponsibilityCenter::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => 'Select Responsibility Center'
        ]
    ]) ?>

    <?= $form->field($model, 'fk_employee_id')->widget(Select2::class, [
        'data' => $requested_by,
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
                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
        ],


    ]) ?>
    <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
        'data' => $requested_by,
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
                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
        ],


    ]) ?>

    <?= $form->field($model, 'date_requested')->widget(DatePicker::class, [
        'options' => [
            'readonly' => true,
            'style' => 'background-color:white'
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true
        ]
    ]) ?>

    <?= $form->field($model, 'problem_description')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'recommendation')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'action_taken')->textarea(['rows' => 4]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>