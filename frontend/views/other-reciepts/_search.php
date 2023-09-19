<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\OtherRecieptsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="other-reciepts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'report') ?>

    <?= $form->field($model, 'province') ?>

    <?= $form->field($model, 'fund_source') ?>

    <?= $form->field($model, 'advance_type') ?>

    <?php // echo $form->field($model, 'object_code') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
