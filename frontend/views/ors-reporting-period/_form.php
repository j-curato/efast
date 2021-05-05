<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrsReportingPeriod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ors-reporting-period-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'name' => 'reporting_period',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm',
            // 'startView'=>'years',
            'minViewMode' => 'months'
        ]
    ]) ?>

    <?= $form->field($model, 'disabled')->widget(Select2::class, [

        'data' => [ 1 => 'True' ,0 => 'False'],
        'name' => 'disabled'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>