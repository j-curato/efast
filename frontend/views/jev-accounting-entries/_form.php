<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JevAccountingEntries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-accounting-entries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jev_preparation_id')->textInput() ?>

    <?= $form->field($model, 'chart_of_account_id')->textInput() ?>

    <?= $form->field($model, 'debit')->textInput() ?>

    <?= $form->field($model, 'credit')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
