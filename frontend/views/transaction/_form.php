<?php

use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-form">

    <?php
    $respons_center = (new \yii\db\Query())->select('*')->from('responsibility_center')->all();
    $payee = (new \yii\db\Query())->select('*')->from('payee')->all();
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-4">

            <?= $form->field($model, 'responsibility_center_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($respons_center, 'id', 'name'),
                'options' => ['placeholder' => 'Select  Responsibility Center'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'payee_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($payee, 'id', 'account_name'),
                'options' => ['placeholder' => 'Select  Payee'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'transaction_date')->widget(DatePicker::class, [
                'name' => 'date',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'mm-dd-yyyy'
                ]

            ]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'tracking_number')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'earmark_no')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-sm-4">

            <?= $form->field($model, 'payroll_number')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <?= $form->field($model, 'particular')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'gross_amount')->widget(MaskMoney::class, [
        'options' => [
            'class' => 'amounts',
        ],
        'pluginOptions' => [
            'prefix' => 'PHP ',
            'allowNegative' => true
        ],
    ]) ?>






    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>