<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="po-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'responsibility_center_id')->textInput() ?>
    <?= $form->field($model, 'payee')->textInput() ?>


    <?= $form->field($model, 'particular')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payroll_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
