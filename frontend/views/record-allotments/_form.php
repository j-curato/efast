<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="record-allotments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'document_recieve_id')->textInput() ?>

    <?= $form->field($model, 'fund_cluster_code_id')->textInput() ?>

    <?= $form->field($model, 'financing_source_code_id')->textInput() ?>

    <?= $form->field($model, 'fund_category_and_classification_code_id')->textInput() ?>

    <?= $form->field($model, 'authorization_code_id')->textInput() ?>

    <?= $form->field($model, 'mfo_pap_code_id')->textInput() ?>

    <?= $form->field($model, 'fund_source_id')->textInput() ?>

    <?= $form->field($model, 'reporting_period')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'allotment_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_issued')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valid_until')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'particulars')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
