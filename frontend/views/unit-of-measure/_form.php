<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnitOfMeasure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-of-measure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'unit_of_measure')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
