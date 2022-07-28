<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RequestForInspectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-for-inspection-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'rfi_number') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'fk_chairperson') ?>

    <?= $form->field($model, 'fk_inspector') ?>

    <?php // echo $form->field($model, 'fk_property_unit') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
