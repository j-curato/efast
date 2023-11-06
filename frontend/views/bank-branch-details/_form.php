<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\BankBranchDetails */
/* @var $form yii\widgets\ActiveForm */

$branch = ArrayHelper::toArray($model->bankBranch);
$defaultBranch[] =  !empty($model->fk_bank_branch_id) ? $branch : '';
?>

<div class="bank-branch-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_bank_branch_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($defaultBranch, 'id', 'branch_name'),
        'options' => [
            'placeholder' => 'Search for a Bank Branch...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=bank-branches/search-bank-branch',
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

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bank_manager')->textInput(['maxlength' => true]) ?>

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