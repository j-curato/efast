<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FundSourceType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-source-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'division')->widget(
        Select2::class,
        [
            'data' => [
                'ADN' => 'ADN',
                'ADS' => 'ADS',
                'PDI' => 'PDI',
                'SDN' => 'SDN',
                'SDS' => 'SDS'
            ],
            'pluginOptions' => [
                'placeholder' => 'Select Division'
            ]
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>