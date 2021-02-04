<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ChartOfAccountsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chart-of-accounts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uacs') ?>

    <?= $form->field($model, 'general_ledger') ?>

    <?= $form->field($model, 'major_account_id') ?>

    <?= $form->field($model, 'sub_major_account') ?>

    <?php // echo $form->field($model, 'account_group') ?>

    <?php // echo $form->field($model, 'current_noncurrent') ?>

    <?php // echo $form->field($model, 'enable_disable') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
