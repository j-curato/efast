<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDepositsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fmi-bank-deposits-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?= $form->field($model, 'deposit_date') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?= $form->field($model, 'fk_fmi_bank_deposit_type_id') ?>

    <?php // echo $form->field($model, 'fk_fmi_subproject_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
