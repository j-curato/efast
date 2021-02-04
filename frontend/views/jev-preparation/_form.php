<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;



/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-preparation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $r_center = Yii::$app->db->createCommand(
        "SELECT * from responsibility_center"
    )->queryAll();

    ?>


    <?= $form->field($model, 'responsibility_center_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($r_center, 'id', 'name'),
        'options' => ['placeholder' => 'Select a Fund Source'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'responsibility_center_id')->textInput() ?>

    <?= $form->field($model, 'fund_cluster_code_id')->textInput() ?>

    <?= $form->field($model, 'reporting_period')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'jev_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lddap_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entity_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'explaination')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>