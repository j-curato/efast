<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\MaintenanceJobRequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maintenance-job-request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fk_responsibility_center_id') ?>

    <?= $form->field($model, 'fk_employee_id') ?>

    <?= $form->field($model, 'date_requested') ?>

    <?= $form->field($model, 'problem_description') ?>

    <?php // echo $form->field($model, 'recomendation') ?>

    <?php // echo $form->field($model, 'action_taken') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
