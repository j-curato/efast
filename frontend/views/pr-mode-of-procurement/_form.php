<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PrModeOfProcurement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-mode-of-procurement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mode_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'is_bidding')->dropDownList(
        [
            '0' => 'No',
            '1' => 'Yes',
        ],
    ) ?>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>