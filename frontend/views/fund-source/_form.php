<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\FundSource */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-source-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
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
<style>
    textarea {
        max-width: 100%;
    }
</style>