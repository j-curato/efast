<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ChartOfAccounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chart-of-accounts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $major = Yii::$app->db->createCommand("SELECT * from major_accounts")->queryAll();
    $sub_major = Yii::$app->db->createCommand("SELECT * from sub_major_accounts")->queryAll();
    ?>


    <div class="row">
        <div class="col">
            <?= $form->field($model, 'major_account_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($major, 'id', 'name'),
                'options' => ['placeholder' => 'Select  Major Account'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'sub_major_account')->widget(Select2::class, [
                'data' => ArrayHelper::map($sub_major, 'id', 'name'),
                'options' => ['placeholder' => 'Select a Fund Source'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>


    </div>


    <?= $form->field($model, 'uacs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'general_ledger')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'account_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_noncurrent')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-sm-3">

            <?= $form->field($model, 'is_active')->widget(Select2::class, [
                'data' => [1 => 'True', 0 => 'false'],
                'name' => 'is_active',
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'normal_balance')->widget(
                Select2::class,
                [
                    'data' => ['Debit' => 'Debit', 'Credit' => 'Credit'],

                    'options' => ['placeholder' => 'Select a Normal Balance'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            ) ?>
        </div>
        <div class="col-sm-5">
            <?= $form->field($model, 'is_province_visible')->widget(
                Select2::class,
                [
                    'data' => ['False', 'True'],
                    'options' => ['placeholder' => 'Select '],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]
            ) ?>
        </div>
    </div>

    <div class="row justify-content-center">

        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>