<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SupervisorValidationNotesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supervisor-validation-notes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'employee_name') ?>

    <?= $form->field($model, 'evaluation_period') ?>

    <?= $form->field($model, 'ttl_suc_msr') ?>

    <?= $form->field($model, 'valid_msr') ?>

    <?php // echo $form->field($model, 'accomplishments') ?>

    <?php // echo $form->field($model, 'pgs_rating') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'passion') ?>

    <?php // echo $form->field($model, 'integrety') ?>

    <?php // echo $form->field($model, 'competence') ?>

    <?php // echo $form->field($model, 'creativity') ?>

    <?php // echo $form->field($model, 'synergy') ?>

    <?php // echo $form->field($model, 'love_of_country') ?>

    <?php // echo $form->field($model, 'int_gbl_olk') ?>

    <?php // echo $form->field($model, 'del_solution') ?>

    <?php // echo $form->field($model, 'net_link') ?>

    <?php // echo $form->field($model, 'del_exl_res') ?>

    <?php // echo $form->field($model, 'collaborating') ?>

    <?php // echo $form->field($model, 'agility') ?>

    <?php // echo $form->field($model, 'proflsm_int') ?>

    <?php // echo $form->field($model, 'dev_intervention') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
