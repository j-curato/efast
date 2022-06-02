<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrApr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-apr-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pr_purchase_request_id')->textInput() ?>

    <?= $form->field($model, 'apr_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
