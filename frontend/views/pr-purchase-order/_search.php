<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-purchase-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'po_number') ?>

    <?= $form->field($model, 'fk_contract_type_id') ?>

    <?= $form->field($model, 'fk_mode_of_procurement_id') ?>

    <?= $form->field($model, 'fk_pr_aoq_id') ?>

    <?php // echo $form->field($model, 'place_of_delivery') ?>

    <?php // echo $form->field($model, 'delivery_date') ?>

    <?php // echo $form->field($model, 'payment_term') ?>

    <?php // echo $form->field($model, 'fk_auth_official') ?>

    <?php // echo $form->field($model, 'fk_accounting_unit') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
