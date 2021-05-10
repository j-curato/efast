<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LiquidataionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="liquidation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'payee_id') ?>

    <?= $form->field($model, 'responsibility_center_id') ?>

    <?= $form->field($model, 'check_date') ?>

    <?= $form->field($model, 'check_number') ?>

    <?php // echo $form->field($model, 'dv_number') ?>

    <?php // echo $form->field($model, 'particular') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
