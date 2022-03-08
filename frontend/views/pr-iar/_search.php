<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrIarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-iar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, '_date') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?= $form->field($model, 'invoice_number') ?>

    <?= $form->field($model, 'invoice_date') ?>

    <?php // echo $form->field($model, 'fk_pr_purchase_order_id') ?>

    <?php // echo $form->field($model, 'fk_insepection_officer') ?>

    <?php // echo $form->field($model, 'fk_property_custodian') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
