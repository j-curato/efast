<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Divisions */
/* @var $form yii\widgets\ActiveForm */

$division_chief = [];

if (!empty($model->fk_division_chief)) {

    $query = YIi::$app->db->createCommand("SELECT employee_id,employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_division_chief)
        ->queryAll();

    $division_chief = ArrayHelper::map($query, 'employee_id', 'employee_name');
}
?>


<div class="divisions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'division')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fk_division_chief')->widget(Select2::class, [
        'data' => $division_chief,
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
    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>