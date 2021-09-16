<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PoResponsibilityCenter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="po-responsibility-center-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'province')->widget(Select2::class,[
        'data'=>[
            'adn'=>'ADN',
            'ads'=>'ADS',
            'sdn'=>'SDN',
            'sds'=>'SDS',
            'pdi'=>'PDI',
        ],
        'pluginOptions'=>[
            'placeholder'=>'Select Province'
        ]
    ]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
