<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\CashFlow */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-flow-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'major_cashflow')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_cashflow1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_cashflow2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'specific_cashflow')->textInput(['maxlength' => true]) ?>

    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>