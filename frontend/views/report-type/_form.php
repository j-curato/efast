<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\ReportType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-type-form">

    <?php
    $report = [
        'Advances for Operating Expenses' => 'Advances for Operating Expenses',
        'Advances to Special Disbursing Officer' => 'Advances to Special Disbursing Officer',

    ];
    $form = ActiveForm::begin();

    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'advance_type')->widget(
        Select2::class,
        [
            'data' => $report,
            'name' => 'report',
            'id' => 'report',
            'pluginOptions' => [
                'placeholder' => 'Select Report'
            ],
            'options' => []
        ]
    ) ?>

    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>