<?php

use app\models\Banks;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\BankBranches */
/* @var $form yii\widgets\ActiveForm */

$bank = ArrayHelper::toArray($model->bank);
$defaultBank[] =  !empty($model->fk_bank_id) ? $bank : '';
?>

<div class="bank-branches-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'fk_bank_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($defaultBank, 'id', 'name'),
        'options' => [
            'placeholder' => 'Search for a Bank ...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=banks/search-bank',
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
        ],
    ]) ?>

    <?= $form->field($model, 'branch_name')->textarea(['rows' => 6]) ?>

    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<style>
    .select2-selection {
        text-transform: uppercase;
    }
</style>