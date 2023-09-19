<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\JevReportingPeriod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-reporting-period-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'name' => 'reporting_period',
        'pluginOptions' => [
            'format' => 'yyyy-mm',
            'startView' => 'months',
            'minViewMode' => 'months',
            'autoclose'=>true
        ]
    ]) ?>

    <?= $form->field($model, 'is_disabled')->widget(Select2::class,[
        'name'=>'is_disabled',
        'data'=>[true=>'True',false=>"False"]
    ]) ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>