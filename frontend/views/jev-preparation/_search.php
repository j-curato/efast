<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-preparation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'responsibility_center_id') ?>

    <?= $form->field($model, 'fund_cluster_code_id') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'jev_number') ?>

    <?php // echo $form->field($model, 'dv_number') ?>

    <?php // echo $form->field($model, 'lddap_number') ?>

    <?php // echo $form->field($model, 'entity_name') ?>

    <?php // echo $form->field($model, 'explaination') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
