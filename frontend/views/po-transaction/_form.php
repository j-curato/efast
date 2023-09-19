<?php

use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="po-transaction-form">

    <?php
    $user_data = Yii::$app->memem->getUserData();
    $province = strtolower($user_data->office->office_name);
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
    <?= $form->field($model, 'po_responsibility_center_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($respons_center, 'id', 'name'),
        'options' => ['placeholder' => 'Select  Responsibility Center'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'payee')->textInput() ?>


    <?= $form->field($model, 'particular')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
        'options' => [
            'class' => 'amounts',
        ],
        'pluginOptions' => [
            'prefix' => 'PHP ',
            'allowNegative' => true
        ],
    ]) ?>

    <?= $form->field($model, 'payroll_number')->textInput(['maxlength' => true]) ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>