<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tin_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'isEnable')->widget(Select2::class, [
        'data' => [true => 'True ', false => 'False'],
        'pluginOptions' => [
            // 'placeholder' => "Select"
        ]
    ]) ?>

    <div class="row">
        <div class="col-sm-3 col-sm-offset-2">

        </div>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success','style'=>'width:11rem']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>