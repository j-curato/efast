<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrStockType */
/* @var $form yii\widgets\ActiveForm */

$stock_part = ['part-1' => 'Part-1', 'part-2' => 'Part-2', 'part-3' => 'Part-3'];
$chart_of_account_id = '';
if (!empty($model->id)) {
    $chart_of_account_query   = Yii::$app->db->createCommand("SELECT
     id,
     CONCAT(uacs,'-',general_ledger)  as account 

    FROM chart_of_accounts WHERE id = :id")
        ->bindValue(':id', $model->fk_chart_of_account_id)
        ->queryAll();

    $chart_of_account_id = ArrayHelper::map($chart_of_account_query, 'id', 'account');
}
?>

<div class="pr-stock-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'part')->widget(Select2::class, [
        'data' => $stock_part,
        'pluginOptions' => [
            'placeholder' => 'Select Part'
        ]
    ]) ?>

    <?= $form->field($model, 'fk_chart_of_account_id')->widget(Select2::class, [
        'data' => $chart_of_account_id,
        'options' => ['placeholder' => 'Search for a Chart of Account ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=chart-of-accounts/search-chart-of-accounts',
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
    <?= $form->field($model, 'type')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>