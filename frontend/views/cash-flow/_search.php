<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashFlowSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-flow-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'major_cashflow') ?>

    <?= $form->field($model, 'sub_cashflow1') ?>

    <?= $form->field($model, 'sub_cashflow2') ?>

    <?= $form->field($model, 'specific_cashflow') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
