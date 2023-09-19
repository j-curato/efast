<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\RemittancePayee */
/* @var $form yii\widgets\ActiveForm */

$payee = [];
$object_code = [];
if (!empty($model->id)) {
    $payee_query   = Yii::$app->db->createCommand("SELECT id,account_name FROM payee WHERE id = :id")
        ->bindValue(':id', $model->payee_id)
        ->queryAll();
    $payee = ArrayHelper::map($payee_query, 'id', 'account_name');
    $object_code_query   = Yii::$app->db->createCommand("SELECT CONCAT(object_code,'-',account_title) as account,object_code FROM accounting_codes WHERE object_code = :object_code")
        ->bindValue(':object_code', $model->object_code)
        ->queryAll();
    $object_code = ArrayHelper::map($object_code_query, 'object_code', 'account');
}
?>

<div class="remittance-payee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payee_id')->widget(Select2::class, [
        'data' => $payee,
        'options' => ['placeholder' => 'Search for a Employee ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=payee/search-payee',
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

    <?= $form->field($model, 'object_code')->widget(Select2::class, [
        'options' => ['placeholder' => 'Search for a Object Code ...'],
        'data' => $object_code,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=chart-of-accounts/search-general-ledger',
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
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>