<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */


$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>


<div class="" style="">

    <?php $form = ActiveForm::begin([
        'id' => 'changePass',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]); ?>

    <div class="row">

        <div class="col-sm-3">
            <?= $form
                ->field($model, 'old_password')
                ->textInput(['placeholder' => $model->getAttributeLabel('current '),]) ?>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-3">
            <?= $form
                ->field($model, 'new_password', $fieldOptions2)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('new '), 'options' => ['autocomplete' => 'off']]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <?= $form
                ->field($model, 'repeat_password', $fieldOptions2)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('re-type new'), 'options' => ['autocomplete' => 'off']]) ?>

        </div>
    </div>


    <div class="row">

        <div class="form-group">
            <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success', 'name' => 'login-button', 'style' => 'margin-left:6rem']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$('#changePass').on('submit', function(e) {
        e.preventDefault()
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(data) {
                console.log(data)
                console.log(data.success)
                if (data.success) {
                    // Form submitted successfully, you can redirect or perform other actions here.
                    form[0].reset();
                    swal({
                        icon: 'success',
                        title: 'Success',
                        type: "success",
                        timer: 3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }).then(function(){
                        location.reload(true)
                    })
                } else {
                    form.yiiActiveForm('updateMessages', data);
                }
            }
        });
        return false;
    });
JS;
$this->registerJs($js);
?>