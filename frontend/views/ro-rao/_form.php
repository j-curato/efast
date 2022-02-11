<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RoRao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ro-rao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'pluginOptions' => [
            'format' => 'yyyy-mm',
            'minViewMode' => 'months',
            'autoclose' => true
        ]
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>