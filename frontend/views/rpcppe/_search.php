<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RpcppeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rpcppe-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'rpcppe_number') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?= $form->field($model, 'book_id') ?>

    <?= $form->field($model, 'certified_by') ?>

    <?= $form->field($model, 'approved_by') ?>

    <?php // echo $form->field($model, 'verified_by') ?>

    <?php // echo $form->field($model, 'verified_pos') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
