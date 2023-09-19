<?php

use app\models\Banks;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */

$banks = Yii::$app->db->createCommand("SELECT id,UPPER(banks.name) as `name` FROM banks")->queryAll();
?>

<div class="books-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fk_bank_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($banks, 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => 'Select Bank'
        ]
    ]) ?>
    <?= $form->field($model, 'type')->widget(Select2::class, [
        'data' => [
            'mds regular' => 'MDS Regular',
            'mds trust' => 'MDS Trust',
            'current account' => 'Current Account'
        ],
        'pluginOptions' => [
            'placeholder' => 'Select Account Type'
        ]
    ]) ?>
    <?= $form->field($model, 'funding_source_code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lapsing')->widget(
        Select2::class,
        [
            'data' => [
                'quarterly lapsing' => 'Quarterly Lapsing',
                'mds trust' => 'MDS Trust',
                'annual lapsing' => 'Annual Lapsing',
                'does not lapse' => 'Does Not Lapse'
            ],
            'pluginOptions' => [
                'placeholder' => 'Select Lapsing'
            ]
        ],
    ) ?>

    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

    <div class="row justify-content-center">

        <div class="form-group ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>