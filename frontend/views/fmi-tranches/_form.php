<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FmiTranches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fmi-tranches-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'tranche_number')->textInput(['maxlength' => true]) ?>

    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>