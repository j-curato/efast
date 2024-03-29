<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\ProcessBurs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="process-burs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transaction_id')->textInput() ?>

    <?= $form->field($model, 'reporting_period')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'obligation_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'funding_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'document_recieve_id')->textInput() ?>

    <?= $form->field($model, 'mfo_pap_code_id')->textInput() ?>

    <?= $form->field($model, 'fund_source_id')->textInput() ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
