<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */
/* @var $form ActiveForm */
?>
<div class="can">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'payee_id') ?>
        <?= $form->field($model, 'responsibility_center_id') ?>
        <?= $form->field($model, 'po_transaction_id') ?>
        <?= $form->field($model, 'particular') ?>
        <?= $form->field($model, 'check_date') ?>
        <?= $form->field($model, 'check_number') ?>
        <?= $form->field($model, 'province') ?>
        <?= $form->field($model, 'reporting_period') ?>
        <?= $form->field($model, 'dv_number') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- can -->
