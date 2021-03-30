<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dv-aucs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'process_ors_id')->textInput() ?>

    <?= $form->field($model, 'raoud_id')->textInput() ?>

    <?= $form->field($model, 'dv_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reporting_period')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tax_withheld')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_trust_liability_withheld')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'net_amount_paid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
