<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PtrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ptr-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ptr_number') ?>

    <?= $form->field($model, 'par_number') ?>

    <?= $form->field($model, 'transfer_type') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'from') ?>

    <?php // echo $form->field($model, 'to') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
