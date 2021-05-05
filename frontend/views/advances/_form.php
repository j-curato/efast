<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advances-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cash_disbursement_id')->textInput() ?>

    <?= $form->field($model, 'sub_account1_id')->textInput() ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'report_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'particular')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
