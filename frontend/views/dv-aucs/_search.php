<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\DvAucsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dv-aucs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'process_ors_id') ?>

    <?= $form->field($model, 'raoud_id') ?>

    <?= $form->field($model, 'dv_number') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?php // echo $form->field($model, 'tax_withheld') ?>

    <?php // echo $form->field($model, 'other_trust_liability_withheld') ?>

    <?php // echo $form->field($model, 'net_amount_paid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
