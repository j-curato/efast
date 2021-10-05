<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MfoPapCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mfo-pap-code-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'division')->widget(Select2::class, [
        'data' =>    [
            'cpd' => 'CPD',
            'fad' => 'FAD',
            'idd' => 'IDD',
            'ord' => 'ORD',
            'sdd' => 'SDD'
        ],
        'name' => 'division',
        'pluginOptions' => [
            'placeholder' => 'Select Division'
        ]

    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>