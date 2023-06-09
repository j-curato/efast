<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItMaintenanceRequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="it-maintenance-request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fk_requested_by') ?>

    <?= $form->field($model, 'fk_worked_by') ?>

    <?= $form->field($model, 'fk_division_id') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?php // echo $form->field($model, 'date_requested') ?>

    <?php // echo $form->field($model, 'date_accomplished') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
