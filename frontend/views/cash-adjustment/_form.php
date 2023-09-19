<?php

use app\models\Books;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\CashAdjustment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-adjustment-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'readonly' => true,
        'options' => [
            'style' => 'background-color:white'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>
    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'readonly' => true,
        'options' => [
            'style' => 'background-color:white'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm',
            'minViewMode'=>'months',
        ]
    ]) ?>
    <?= $form->field($model, 'book_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'name' => 'book',
        'pluginOptions' => [
            'placeholder' => 'Select Book',
        ],

    ]) ?>

    <?= $form->field($model, 'particular')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
        'options' => [
            'class' => 'amounts',
        ],
        'pluginOptions' => [
            'prefix' => 'PHP ',
            'allowNegative' => true
        ],
    ]) ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>