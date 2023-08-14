<?php

use app\models\Divisions;
use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
                'pluginOptions' => [
                    'placeholder' => 'Select Division'
                ]
            ]) ?>
        </div>
    </div>
    <?= $form->field($model, 'employee_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'f_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'l_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'm_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'suffix')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'property_custodian')->widget(Select2::class, [
        'data' => [0 => 'False', 1 => 'True'],
    ]) ?>

    <div class="row">

        <div class="form-group col-sm-3 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>