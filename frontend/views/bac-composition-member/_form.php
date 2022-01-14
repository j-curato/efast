<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BacCompositionMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bac-composition-member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bac_composition_id')->textInput() ?>

    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bac_position_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
