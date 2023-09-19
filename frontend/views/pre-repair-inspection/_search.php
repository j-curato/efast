<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PreRepairInspectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pre-repair-inspection-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'findings') ?>

    <?= $form->field($model, 'recommendation') ?>

    <?php // echo $form->field($model, 'fk_requested_by') ?>

    <?php // echo $form->field($model, 'fk_accountabler_person') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
