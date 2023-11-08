<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MgrfrsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mgrfrs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fk_bank_branch_detail_id') ?>

    <?= $form->field($model, 'fk_municipality_id') ?>

    <?= $form->field($model, 'fk_barangay_id') ?>

    <?= $form->field($model, 'fk_office_id') ?>

    <?php // echo $form->field($model, 'purok') ?>

    <?php // echo $form->field($model, 'authorized_personnel') ?>

    <?php // echo $form->field($model, 'contact_number') ?>

    <?php // echo $form->field($model, 'saving_account_number') ?>

    <?php // echo $form->field($model, 'email_address') ?>

    <?php // echo $form->field($model, 'investment_type') ?>

    <?php // echo $form->field($model, 'investment_description') ?>

    <?php // echo $form->field($model, 'project_consultant') ?>

    <?php // echo $form->field($model, 'project_objective') ?>

    <?php // echo $form->field($model, 'project_beneficiary') ?>

    <?php // echo $form->field($model, 'matching_grant_amount') ?>

    <?php // echo $form->field($model, 'equity_amount') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
