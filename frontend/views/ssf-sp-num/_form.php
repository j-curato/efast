<?php

use app\models\Citymun;
use app\models\Office;
use app\models\SsfSpStatus;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\SsfSpNum */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="ssf-sp-num-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_citymun_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Citymun::find()->asArray()->all(), 'id', 'city_mun'),
                'pluginOptions' => [
                    'placeholder' => 'Select City/Municipal'
                ]
            ]) ?>
        </div>
        <?php if (Yii::$app->user->can('ro_property_admin')) { ?>
            <div class="col-sm-6">
                <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Office'
                    ]
                ]) ?>

            </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'readonly' => true,
                'options' => [
                    'style' => 'background-color:white;'
                ],

                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                    'todayHighlight' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'budget_year')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy',
                    'minViewMode' => 'years',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
                'pluginOptions' => [
                    'prefix' => '₱ ',
                    'allowNegative' => false
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_ssf_sp_status_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(SsfSpStatus::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Status'
                ]
            ]) ?>
        </div>
    </div>



    <?= $form->field($model, 'cooperator')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'project_name')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'equipment')->textarea(['rows' => 4]) ?>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>