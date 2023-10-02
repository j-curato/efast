<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts2 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sub-accounts2-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'name')->textarea() ?>
    <?= $form->field($model, 'is_active')->widget(Select2::class, [
        'data' => [1 => 'True', 0 => 'False']
    ]) ?>
    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>