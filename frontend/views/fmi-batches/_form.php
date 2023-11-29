<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBatches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fmi-batches-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>


    <?= $form->field($model, 'batch_name')->textarea(['rows' => 6]) ?>

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#FmiBatches").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            swal({
                icon: 'error',
                title: data,
                type: "error",
                timer: 3000,
                closeOnConfirm: false,
                closeOnCancel: false
            })
        },
        error: function (data) {
     
        }
    });
    return false;
});
JS;
$this->registerJs($js);
?>