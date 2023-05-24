<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
    <div class="row">

        <div class="form-group col-sm-1 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>