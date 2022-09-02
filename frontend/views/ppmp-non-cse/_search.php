<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PpmpNonCseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppmp-non-cse-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'project_name') ?>

    <?= $form->field($model, 'target_month') ?>

    <?= $form->field($model, 'fk_source_of_fund_id') ?>

    <?= $form->field($model, 'fk_end_user') ?>

    <?php // echo $form->field($model, 'fk_pap_code_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
