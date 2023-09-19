<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\DocumentTrackerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-tracker-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date_recieved') ?>

    <?= $form->field($model, 'document_type') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'document_number') ?>

    <?php // echo $form->field($model, 'document_date') ?>

    <?php // echo $form->field($model, 'details') ?>

    <?php // echo $form->field($model, 'responsible_office_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
