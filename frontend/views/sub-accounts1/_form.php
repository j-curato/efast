<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sub-accounts1-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textarea() ?>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>