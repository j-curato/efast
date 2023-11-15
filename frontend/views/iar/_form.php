<?php

use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Iar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="iar-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'date_generated')->widget(DatePicker::class, [
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true
        ],

    ]) ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>