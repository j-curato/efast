<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\recordAllotmentEntries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="record-allotment-entries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'record_allotment_id')->textInput() ?>

    <?= $form->field($model, 'chart_of_account_id')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
