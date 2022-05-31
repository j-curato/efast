<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MonthlyLiquidationProgramSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monthly-liquidation-program-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'book_id') ?>

    <?= $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'fund_source_type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
