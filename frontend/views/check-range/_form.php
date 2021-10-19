<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CheckRange */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="check-range-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->textInput() ?>

    <?= $form->field($model, 'to')->textInput() ?>
    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'name' => 'reporting_period',
        'pluginOptions' => [
            'autoclose' => true,
            'startView' => 'months',
            'minViewMode' => 'months',
            'format' => 'yyyy-mm'
        ],
        'options' => [
            'readOnly' => true,
            'style' => 'background-color:white;'
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>