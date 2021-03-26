<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessBursSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="process-burs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'transaction_id') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?= $form->field($model, 'obligation_number') ?>

    <?php // echo $form->field($model, 'funding_code') ?>

    <?php // echo $form->field($model, 'document_recieve_id') ?>

    <?php // echo $form->field($model, 'mfo_pap_code_id') ?>

    <?php // echo $form->field($model, 'fund_source_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
