<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
    $sub_major = Yii::$app->db->createCommand("SELECT * from sub_major_accounts");
    ?>


    <?= $form->field($model, 'major_account_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($major, 'id', 'name'),
        'options' => ['placeholder' => 'Select a Fund Source'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'uacs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'general_ledger')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'major_account_id')->textInput() ?> -->

    <?= $form->field($model, 'sub_major_account')->textInput() ?>

    <?= $form->field($model, 'account_group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_noncurrent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'enable_disable')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>