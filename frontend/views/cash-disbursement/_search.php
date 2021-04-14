<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-disbursement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'book_id') ?>

    <?= $form->field($model, 'dv_aucs_id') ?>

    <?= $form->field($model, 'reporting_period') ?>

    <?= $form->field($model, 'mood_of_payment') ?>

    <?php // echo $form->field($model, 'check_or_ada_no') ?>

    <?php // echo $form->field($model, 'is_cancelled') ?>

    <?php // echo $form->field($model, 'issuance_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
