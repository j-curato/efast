<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SaobSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="saob-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'from_reporting_period') ?>

    <?= $form->field($model, 'to_reporting_period') ?>

    <?= $form->field($model, 'mfo_pap_code_id') ?>

    <?= $form->field($model, 'document_recieve_id') ?>

    <?php // echo $form->field($model, 'book_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
