<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrModeOfProcurement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-mode-of-procurement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mode_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>