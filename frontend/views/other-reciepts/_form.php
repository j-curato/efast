<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OtherReciepts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="other-reciepts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'report')->widget(
        Select2::class,[
            'data'=>['gj','ss'],
            'name'=>'report',
            'pluginOptions'=>[
                'placeholder'=>'Select Report'
            ]
        ]
    ) ?>

    <?= $form->field($model, 'province')->widget(
        Select2::class,
        [
            'data' => ['adn', 'sdb'],
            'name' => 'province',
            'pluginOptions'=>[
                'placeholder'=>'Select Province'
            ]

        ]
    ) ?>

    <?= $form->field($model, 'fund_source')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'advance_type')->widget(
        Select2::class,[
            'data'=>['q','w'],
            'name'=>'advance_type',
            'pluginOptions'=>[
                'placeholder'=>'Select Advance Type'
            ]
        ]
    ) ?>

    <?= $form->field($model, 'object_code')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>