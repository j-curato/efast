<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\User;
use yii\web\JsExpression;
use kartik\date\DatePicker;

use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransaction */
/* @var $form yii\widgets\ActiveForm */

$iarItems = $model->getIarItemsA();
?>

<div class="po-transaction-form">

    <?php
    $user_data = User::getUserDetails();
    $province = strtolower($user_data->employee->office->office_name);
    if (!Yii::$app->user->can('ro_accounting_admin')) {
        $respons_center = (new \yii\db\Query())->select('*')
            ->from('po_responsibility_center')
            ->where('province =:province', ['province' => $province])
            ->all();
    } else {
        $respons_center = (new \yii\db\Query())->select('*')->from('po_responsibility_center')->all();
    }
    ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-sm-6">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'autoclose' => true,
                    'minViewMode' => 'months',
                    'startView' => "months",
                ],
                'options' => [
                    'readOnly' => true,
                    'style' => 'background-color:white'
                ]
            ]); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'po_responsibility_center_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($respons_center, 'id', 'name'),
                'options' => ['placeholder' => 'Select  Responsibility Center'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>


    <label> IAR's </label>
    <?= Select2::widget([
        'name' => 'iars',
        'data' => ArrayHelper::map($iarItems, 'fk_iar_id', 'iar_number'),
        'value' => ArrayHelper::getColumn($iarItems, 'fk_iar_id'),
        'options' => ['placeholder' => 'Select IARs...', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=iar/search-iar',
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {q:params.term,page:params.page ||1}; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
        ],
    ]) ?>


    <?= $form->field($model, 'payee')->textInput() ?>
    <?= $form->field($model, 'particular')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'fk_requested_by')->widget(Select2::class, [
        'data' => ArrayHelper::map($requestedBy, 'employee_id', 'fullName'),
        'options' => ['placeholder' => 'Search for a Employee ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['employee/search-employee']),
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
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
                'options' => [
                    'class' => 'amounts',
                ],
                'pluginOptions' => [
                    'prefix' => 'PHP ',
                    'allowNegative' => true
                ],
            ]) ?>
        </div>

        <div class="col-sm-6">
            <?= $form->field($model, 'payroll_number')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="form-group col-sm-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>