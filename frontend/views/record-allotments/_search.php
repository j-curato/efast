<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotmentsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="record-allotments-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'document_recieve_id') ?>

    <?= $form->field($model, 'fund_cluster_code_id') ?>

    <?= $form->field($model, 'financing_source_code_id') ?>

    <?= $form->field($model, 'fund_category_and_classification_code_id') ?>

    <?php // echo $form->field($model, 'authorization_code_id') ?>

    <?php // echo $form->field($model, 'mfo_pap_code_id') ?>

    <?php // echo $form->field($model, 'fund_source_id') ?>

    <?php // echo $form->field($model, 'reporting_period') ?>

    <?php // echo $form->field($model, 'serial_number') ?>

    <?php // echo $form->field($model, 'allotment_number') ?>

    <?php // echo $form->field($model, 'date_issued') ?>

    <?php // echo $form->field($model, 'valid_until') ?>

    <?php // echo $form->field($model, 'particulars') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
