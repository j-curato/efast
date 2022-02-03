<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>


    <?php
    $province = Yii::$app->user->identity->province;
    if (
        $province === 'adn' ||
        $province === 'ads' ||
        $province === 'pdi' ||
        $province === 'sdn' ||
        $province === 'sds'
    ) {
    } else {

    ?>
        <?= $form->field($model, 'province')->widget(
            Select2::class,
            [
                'data' => [
                    'adn' => 'ADN',
                    'ads' => 'ADS',
                    'pdi' => 'PDI',
                    'sdn' => 'SDN',
                    'sds' => 'SDS',
                ],
                'pluginOptions' => [
                    'placeholder' => 'Select Province'
                ]
            ]

        ) ?>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>