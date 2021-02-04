<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'responsibility_center_id')->textInput() ?>

    <?= $form->field($model, 'payee_id')->textInput() ?>

    <?= $form->field($model, 'particular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gross_amount')->textInput() ?>

    <?= $form->field($model, 'tracking_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'earnark_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payroll_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_time')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
