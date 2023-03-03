<?php

use app\models\Divisions;
use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'is_nc')->widget(Select2::class, [
        'data' => [
            '0' => 'Office',
            '1' => 'NC'
        ],
        'pluginOptions' => [
            'placeholder' => 'Select Office/NC'
        ]
    ]) ?>

    <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
        'pluginOptions' => [
            'placeholder' => 'Select Office'
        ]
    ]) ?>
    <?= $form->field($model, 'fk_division_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Divisions::find()->asArray()->all(), 'id', 'division'),
        'pluginOptions' => [
            'placeholder' => 'Select Division'
        ]
    ]) ?>
    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>