<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="liquidation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payee_id')->textInput() ?>

    <?= $form->field($model, 'responsibility_center_id')->textInput() ?>

    <?= $form->field($model, 'check_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'check_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'particular')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
