<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\SupplementalPpmpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplemental-ppmp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serial_number') ?>

    <?= $form->field($model, 'budget_year') ?>

    <?= $form->field($model, 'cse_type') ?>

    <?= $form->field($model, 'part') ?>

    <?php // echo $form->field($model, 'early_procurement') ?>

    <?php // echo $form->field($model, 'fk_office_id') ?>

    <?php // echo $form->field($model, 'fk_division_id') ?>

    <?php // echo $form->field($model, 'fk_division_program_unit_id') ?>

    <?php // echo $form->field($model, 'fk_mfo_pap_code_id') ?>

    <?php // echo $form->field($model, 'fk_mode_of_procurement_id') ?>

    <?php // echo $form->field($model, 'fk_fund_source') ?>

    <?php // echo $form->field($model, 'fk_prepared_by') ?>

    <?php // echo $form->field($model, 'fk_reviewed_by') ?>

    <?php // echo $form->field($model, 'fk_approved_by') ?>

    <?php // echo $form->field($model, 'fk_certified_funds_available_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
