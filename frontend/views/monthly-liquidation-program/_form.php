<?php

use app\models\Books;
use app\models\FundSourceType;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\MonthlyLiquidationProgram */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monthly-liquidation-program-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reporting_period')->widget(
        DatePicker::class,
        [
            'pluginOptions' => [
                'format' => 'yyyy-mm',
                'minViewMode' => 'months',
                'autoclose' => true
            ]
        ]
    ) ?>

    <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
        'options' => [
            'class' => 'amounts',
        ],
        'pluginOptions' => [
            'prefix' => 'PHP ',
            'allowNegative' => true
        ],
    ]) ?>

    <?= $form->field($model, 'book_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => 'Select Book'
        ]
    ]) ?>

    <?= $form->field($model, 'province')->widget(Select2::class, [
        'data' => [
            'adn' => 'ADN',
            'ads' => 'ADs',
            'pdi' => 'PDI',
            'sdn' => 'SDN',
            'sds' => 'SDS',
        ],
        'pluginOptions' => [
            'placeholder' => 'Select Province'
        ]
    ]) ?>

    <?= $form->field($model, 'fund_source_type')->widget(
        Select2::class,
        [
            'data' => ArrayHelper::map(FundSourceType::find()->asArray()->all(), 'name', 'name'),
            'pluginOptions' => [
                'placeholder' => 'Select Fund Source Type',

            ]
        ]
    ) ?>


    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>