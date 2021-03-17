<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="process-ors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transaction_id')->textInput() ?>

    <?= $form->field($model, 'reporting_period')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'obligation_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'funding_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'document_recieve_id')->textInput() ?>

    <?= $form->field($model, 'mfo_pap_code_id')->textInput() ?>

    <?= $form->field($model, 'fund_source_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
