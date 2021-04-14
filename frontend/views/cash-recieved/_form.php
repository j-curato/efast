<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashRecieved */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-recieved-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'document_recieved_id')->textInput() ?>

    <?= $form->field($model, 'book_id')->textInput() ?>

    <?= $form->field($model, 'mfo_pap_code_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reporting_period')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nca_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nta_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nft_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purpose')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
