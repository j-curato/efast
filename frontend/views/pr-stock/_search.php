<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrStockSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-stock-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'stock') ?>

    <?= $form->field($model, 'bac_code') ?>

    <?= $form->field($model, 'unit_of_measure_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'chart_of_account_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
