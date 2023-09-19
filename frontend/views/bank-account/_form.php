<?php

use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-account-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    if (Yii::$app->user->can('ro_accounting_admin')) {
    ?>
        <?= $form->field($model, 'fk_office_id')->widget(
            Select2::class,
            [
                'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Province'
                ]
            ]

        ) ?>
    <?php } ?>
    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>




    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>