<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JevAccountingEntriesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-accounting-entries-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jev_preparation_id') ?>

    <?= $form->field($model, 'chart_of_account_id') ?>

    <?= $form->field($model, 'debit') ?>

    <?= $form->field($model, 'credit') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
