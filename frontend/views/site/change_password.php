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
        // 'enableAjaxValidation' => true,
        // 'enableClientValidation' => false,
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
// SweetAlertAsset::register($this);
// $js = <<< JS
// $("#changePass").on("beforeSubmit", function (event) {
//     event.preventDefault();
//     var form = $(this);
//     var inputIds = $('#changePass :input').map(function() {
//         if (this.id){
//         return this.id;
//         }
//         }).get();

//     $.ajax({
//         url: form.attr("action"),
//         type: form.attr("method"),
//         data: form.serialize(),
//         success: function (data) {
//             const response = JSON.parse(data)
//             // console.log(inputIds)
//             if (response.success) {
//                     // success
//             } else {
//                 // console.log(response.errors)
//                 $.each(inputIds,(key,val)=>{

//                     const error =  response.errors[val.split('-').slice(1).join('-')]? response.errors[val.split('-').slice(1).join('-')]:''
//                     // console.log(response.errors[val])
//                   if (error[0]){
//                     $('#'+val).parent().find('.help-block').text(error)
//                     $('#'+val).parent().find('.help-block').css('color','red')
//                     $('#'+val).parent().find('.control-label').css('color','red')
//                     $('#'+val).parent().find('.glyphicon').css('color','red')
//                     $('#'+val).css('border','1px solid red')
//                   }


//                 })

//             }
//             // let res = JSON.parse(data)
//             // console.log(res)
//             // swal({
//             //     icon: 'error',
//             //     title: res.error,
//             //     type: "error",
//             //     timer: 3000,
//             //     closeOnConfirm: false,
//             //     closeOnCancel: false
//             // })
//         },
//         error: function (data) {

//         }
//     });
//     return false;
// });
// JS;
// $this->registerJs($js);
?>