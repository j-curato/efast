<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\OtherReciepts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="other-reciepts-form">

    <?php $form = ActiveForm::begin();

    $province = [
        'ADN' => 'ADN',
        'ADS' => 'ADS',
        'SDN' => 'SDN',
        'SDS' => 'SDS',
        'PDI' => 'PDI'
    ];
    $report = [
        'Advances for Operating Expenses' => '101 OPEX CDR',
        'Advances to Special Disbursing Officer' => '101 SDO CDR',
        'RAPID LP SDO CDR' => 'RAPID LP SDO CDR',
        'GJ' => 'GJ'
    ];
    $sub_accounts = Yii::$app->db->createCommand("SELECT * FROM sub_accounts_view")->queryAll();
    ?>

    <?= $form->field($model, 'report')->widget(
        Select2::class,
        [
            'data' => $report,
            'name' => 'report',
            'pluginOptions' => [
                'placeholder' => 'Select Report'
            ]
        ]
    ) ?>

    <?= $form->field($model, 'province')->widget(
        Select2::class,
        [
            'data' => $province,
            'name' => 'province',
            'pluginOptions' => [
                'placeholder' => 'Select Province'
            ]

        ]
    ) ?>

    <?= $form->field($model, 'fund_source')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'sl_object_code')->widget(Select2::class,
    [

        'data'=>ArrayHelper::map($sub_accounts,'object_code','object_code')
    ]
    ) ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>