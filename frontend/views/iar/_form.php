<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Iar */
/* @var $form yii\widgets\ActiveForm */

$end_user = '';

if (!empty($model->fk_end_user)) {
    $query = Yii::$app->db->createCommand("SELECT employee_id,employee_name FROM employee_search_view WHERE employee_id = :id ")->bindValue(':id', $model->fk_end_user)->queryAll();
    $end_user = ArrayHelper::map($query, 'employee_id', 'employee_name');
}
?>

<div class="iar-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'fk_end_user')->widget(Select2::class, [
        'data' => $end_user,
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


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>