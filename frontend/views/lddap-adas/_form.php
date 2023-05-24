<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LddapAdas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lddap-adas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_cash_disbursement_id')->textInput() ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
