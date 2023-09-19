<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MajorAccounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="major-accounts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'object_code')->textInput(['maxlength' => true]) ?>

    <div class="row justify-content-center">

        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>