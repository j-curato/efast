<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrOffice */
/* @var $form yii\widgets\ActiveForm */


$office = [
    'RO' => 'RO',
    'ADN' => 'ADN',
    'ADS' => 'ADS',
    'PDI' => 'PDI',
    'SDN' => 'SDN',
    'SDS' => 'SDS',
];
$division = [
    'CPD' => 'CPD',
    'FAD' => 'FAD',
    'IDD' => 'IDD',
    'OPD' => 'OPD',
    'ORD' => 'ORD',
    'SDD' => 'SDD',
    'MSSU' => 'MSSU',
];

?>

<div class="pr-office-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'office')->widget(Select2::class, [
        'data' => $office,
        'options' => ['placeholder' => 'Select Office'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>


    <?= $form->field($model, 'division')->widget(Select2::class, [
        'data' => $division,
        'options' => ['placeholder' => 'Select Division'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]) ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>