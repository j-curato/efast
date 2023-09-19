<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\recordAllotmentEntriesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="record-allotment-entries-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'record_allotment_id') ?>

    <?= $form->field($model, 'chart_of_account_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'lvl') ?>

    <?php // echo $form->field($model, 'object_code') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
