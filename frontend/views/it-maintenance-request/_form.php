<?php

use app\components\helpers\MyHelper;
use app\models\Divisions;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItMaintenanceRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="it-maintenance-request-form panel panel-default ">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date_requested')->widget(
                DatePicker::class,
                ['pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]]
            ) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'date_accomplished')->widget(
                DatePicker::class,
                ['pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]]
            ) ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_division_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Divisions::find()->asArray()->all(), 'id', 'division'),
                'pluginOptions' => [
                    'placeholder' => 'Select Division'
                ]
            ]) ?>

        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'type')->widget(Select2::class, [
                'data' => [
                    'ir' => 'Incident Request',
                    'ta' => 'Technical Assistance'
                ],
                'pluginOptions' => [
                    'placeholder' => 'Select Type'
                ]
            ]) ?>
        </div>

    </div>


    <div class="row">
        <div class="col-sm-6">

            <?= $form->field($model, 'fk_requested_by')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_requested_by, 'all'), 'employee_id', 'employee_name'),
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

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_worked_by')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_worked_by, 'all'), 'employee_id', 'employee_name'),
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
        </div>
    </div>






    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'action_taken')->textarea(['rows' => 6]) ?>



    <div class="row">
        <div class="form-group col-sm-1 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
<style>
    .container {
        padding: 2rem;
    }
</style>