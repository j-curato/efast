<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sub-accounts1-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'chart_of_account_id')->textInput() ?>

    <?= $form->field($model, 'object_code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class,[
        'pluginOptions'=>[
            'autoclose'=>true,
            'format'=>'yyyy-mm',
            'minViewMode'=>'months'
        ]
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'is_active')->widget(
        Select2::class,
        [
            'data' => [1 => 'True', 0 => 'False'],
            'name' => 'is_active'
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>