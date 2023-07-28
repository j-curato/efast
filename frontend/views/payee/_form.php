<?php

use app\models\Banks;
use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payee */
/* @var $form yii\widgets\ActiveForm */

$banks = YIi::$app->db->createCommand("SELECT UPPER(banks.name) as bank_name,banks.id FROM banks")
    ->queryAll();
?>

<div class="payee-form">

    <?php $form = ActiveForm::begin();

    if (Yii::$app->user->can('super-user')) {

    ?>
        <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
            'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
            'pluginOptions' => [
                'placeholder' => 'Select Office'
            ]
        ]) ?>
    <?php } ?>

    <?= $form->field($model, 'fk_bank_id')->widget(Select2::class, [

        'data' => ArrayHelper::map($banks, 'id', 'bank_name'),
        'pluginOptions' => [
            'placeholder' => 'Select Bank'
        ]
    ]) ?>
    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tin_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'isEnable')->widget(Select2::class, [
        'data' => [true => 'True ', false => 'False'],
        'pluginOptions' => [
            // 'placeholder' => "Select"
        ]
    ]) ?>

    <div class="row">
        <div class="col-sm-3 col-sm-offset-2">

        </div>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:11rem']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>