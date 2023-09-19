<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PreRepairInspection */
/* @var $form yii\widgets\ActiveForm */

$requested_by = [];
$accountable_person = [];
if (!empty($model->id)) {

    $req_by_query = YIi::$app->db->createCommand("SELECT employee_id,employee_name FROM employee_search_view WHERE employee_id = :requested_by")->bindValue(':requested_by', $model->fk_requested_by)->queryAll();
    $requested_by = ArrayHelper::map($req_by_query, 'employee_id', 'employee_name');
    $act_person_query = YIi::$app->db->createCommand("SELECT employee_id,employee_name FROM employee_search_view WHERE employee_id = :requested_by")->bindValue(':requested_by', $model->fk_accountable_person)->queryAll();
    $accountable_person = ArrayHelper::map($act_person_query, 'employee_id', 'employee_name');
}
?>

<div class="pre-repair-inspection-form">

    <?php $form = ActiveForm::begin(); ?>



    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'options' => [
                    'readonly' => true,
                    'style' => 'background-color:white',
                ],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
    </div>


    <?= $form->field($model, 'findings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'recommendation')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'fk_requested_by')->widget(Select2::class, [
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
        </div>
        <div class="col">

            <?= $form->field($model, 'fk_accountable_person')->widget(Select2::class, [
                'data' => $accountable_person,
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


    <div class="row justify-content-center">
        <div class="form-group col-sm-2 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>