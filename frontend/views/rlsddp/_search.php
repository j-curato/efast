<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RlsddpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rlsddp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'fk_acctbl_offr') ?>

    <?= $form->field($model, 'is_blottered') ?>

    <?php // echo $form->field($model, 'police_station') ?>

    <?php // echo $form->field($model, 'fk_property_status_id') ?>

    <?php // echo $form->field($model, 'fk_supvr') ?>

    <?php // echo $form->field($model, 'circumstances') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
