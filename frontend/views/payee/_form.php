<?php

use app\models\Banks;
use app\models\Office;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payee */
/* @var $form yii\widgets\ActiveForm */

$banks = YIi::$app->db->createCommand("SELECT UPPER(banks.name) as bank_name,banks.id FROM banks")
    ->queryAll();
?>

<div class="payee-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="row">
        <div class="col">

            <?php if (Yii::$app->user->can('super-user')) : ?>
                <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Office'
                    ]
                ]) ?>
            <?php endif; ?>
        </div>

        <div class="col">
            <?= $form->field($model, 'isEnable')->widget(Select2::class, [
                'data' => [true => 'True ', false => 'False'],
                'pluginOptions' => [
                    // 'placeholder' => "Select"
                ]
            ]) ?>
        </div>
        <div class="col">

            <?= $form->field($model, 'fk_bank_id')->widget(Select2::class, [

                'data' => ArrayHelper::map($banks, 'id', 'bank_name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Bank'
                ]
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registered_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tin_number')->textInput(['maxlength' => true]) ?>


    <div class="row justify-content-center">

        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
SweetAlertAsset::register($this);
$js = <<< JS

    $('#Payee').on('beforeSubmit',function(e){
        e.preventDefault()
        const form  =$(this)
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            data:form.serialize(),
            success:function(data){
                swal({
                    icon:'error',
                    title:data,
                    timer: 3000,
                    closeOnConfirm: false,
                    closeOnCancel: false
                })
            }
        })
        return false;
    })
JS;
$this->registerJs($js);
?>