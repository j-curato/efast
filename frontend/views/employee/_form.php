<?php

use app\models\Divisions;
use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_office_id')->widget(Select2::class, ['data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'), 'pluginOptions' => [
                'placeholder' => 'Select Office/Province'
            ]]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_division_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Divisions::find()->asArray()->all(), 'id', 'division'),
                'options' => [
                    'class' => 'capitalize-input'
                ],
                'pluginOptions' => [
                    'placeholder' => 'Select Division'
                ]
            ]) ?>
        </div>
    </div>
    <?= $form->field($model, 'employee_number')->textInput(['class' => 'capitalize-input form-control']) ?>

    <?= $form->field($model, 'f_name')->textInput(['class' => 'capitalize-input form-control']) ?>
    <?= $form->field($model, 'l_name')->textInput(['class' => 'capitalize-input form-control']) ?>
    <?= $form->field($model, 'm_name')->textInput(['class' => 'capitalize-input form-control']) ?>
    <?= $form->field($model, 'suffix')->textInput(['class' => 'capitalize-input form-control']) ?>
    <?= $form->field($model, 'status')->textInput(['class' => 'capitalize-input form-control']) ?>
    <?= $form->field($model, 'position')->textInput(['class' => 'capitalize-input form-control']) ?>
    <?= $form->field($model, 'property_custodian')->widget(Select2::class, [
        'data' => [0 => 'False', 1 => 'True'],
    ]) ?>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>