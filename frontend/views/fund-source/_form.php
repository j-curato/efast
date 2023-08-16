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

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>

    <div class="row">
        <div class="form-group col-sm-2 col-sm-offset-5">
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
    textarea{
        max-width: 100%;
    }
</style>