<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\CashReceivedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-recieved-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'document_recieved_id') ?>

    <?= $form->field($model, 'book_id') ?>

    <?= $form->field($model, 'mfo_pap_code_id') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'reporting_period') ?>

    <?php // echo $form->field($model, 'nca_no') ?>

    <?php // echo $form->field($model, 'nta_no') ?>

    <?php // echo $form->field($model, 'nft_no') ?>

    <?php // echo $form->field($model, 'purpose') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
