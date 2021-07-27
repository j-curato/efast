<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FundSource */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-source-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end();

    // echo DateTimePicker::widget([
    //     'name'=>'date',
    //     'options' => ['placeholder' => 'Enter event time ...','readOnly'=>true],
    //     'pluginOptions' => [
    //         'autoclose' => true
    //     ]
    // ]);




    ?>


</div>