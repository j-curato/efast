<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\ModeOfPayments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mode-of-payments-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'check_type')->widget(Select2::class, [
        'data' => ['1' => 'LBP check', '0' => 'eCheck'],
        'pluginOptions' => [
            'placeholder' => 'Select Mode of Payment'
        ]
    ]) ?>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>