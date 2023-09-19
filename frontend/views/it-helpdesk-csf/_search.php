<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\ItHelpdeskCsfSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="it-helpdesk-csf-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?= $form->field($model, 'fk_it_maintenance_request') ?>

    <?= $form->field($model, 'fk_client_id') ?>

    <?= $form->field($model, 'contact_num') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'clarity') ?>

    <?php // echo $form->field($model, 'skills') ?>

    <?php // echo $form->field($model, 'professionalism') ?>

    <?php // echo $form->field($model, 'courtesy') ?>

    <?php // echo $form->field($model, 'response_time') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'age_group') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'vd_reason') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
