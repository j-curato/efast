<?php

use app\models\Banks;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payee */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="payee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file_link')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <div class="col-sm-3 col-sm-offset-4">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:11rem']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>