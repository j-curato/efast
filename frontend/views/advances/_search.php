<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdvancesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advances-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cash_disbursement_id') ?>

    <?= $form->field($model, 'sub_account1_id') ?>

    <?= $form->field($model, 'province') ?>

    <?= $form->field($model, 'report_type') ?>

    <?php // echo $form->field($model, 'particular') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
