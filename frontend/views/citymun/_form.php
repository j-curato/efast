<?php

use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Citymun */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="citymun-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'city_mun')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
        'pluginOptions' => [
            'placeholder' => 'Select Office'
        ]
    ]) ?>


    <div class="row">
        <div class="col-sm-3 col-sm-offset-5">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>