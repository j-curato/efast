<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use app\models\Divisions;
use app\models\Office;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h3><?= Html::encode($this->title) ?></h3>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Office'
                ]
            ]) ?>
            <?= $form->field($model, 'fk_division_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Divisions::find()->asArray()->all(), 'id', 'division'),
                'pluginOptions' => [
                    'placeholder' => 'Select Division'
                ]
            ]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php
// SweetAlertAsset::register($this);
// $js = <<< JS
// $("#form-signup").on("beforeSubmit", function (event) {
//     event.preventDefault();
//     var form = $(this);
//     var inputIds = $('#form-signup :input').map(function() {
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
//                     console.log(val)
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