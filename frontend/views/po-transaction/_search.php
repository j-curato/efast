<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PoTransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="po-transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'responsibility_center_id') ?>

    <?= $form->field($model, 'payee') ?>

    <?= $form->field($model, 'particular') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'payroll_number') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
