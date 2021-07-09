<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descriptions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->widget(Datepicker::class, [
        'name' => 'start_date',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ],
        'options' => [
            'style' => 'background-color:white',
            'readOnly' => true
        ]
    ]) ?>
    <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
        'name' => 'end_date',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ],
        'options' => [
            'style' => 'background-color:white',
            'readOnly' => true
        ]

    ]) ?>

    <div class="form-group row">
        <div class="col-sm-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

        </div>
        <div class="col-sm-2 align-self-end">
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>





    <?php ActiveForm::end(); ?>

</div>