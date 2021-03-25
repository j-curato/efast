<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrsEntries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="process-ors-entries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'chart_of_account_id')->textInput() ?>

    <?= $form->field($model, 'process_ors_id')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
