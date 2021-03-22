<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Raouds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="raouds-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'record_allotment_id')->textInput() ?>

    <?= $form->field($model, 'process_ors_id')->textInput() ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reporting_period')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
