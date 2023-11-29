<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDepositTypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fmi-bank-deposit-types-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deposit_type')->textInput(['maxlength' => true]) ?>

    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>