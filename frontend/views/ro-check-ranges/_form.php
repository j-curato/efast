<?php

use app\models\Books;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RoCheckRange */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ro-check-range-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
    ]); ?>

    <?= $form->field($model, 'fk_book_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => 'Select Book'
        ]
    ]) ?>

    <?= $form->field($model, 'check_type')->widget(Select2::class, [
        'data' => ['1' => 'LBP check', '0' => 'eCheck'],
        'pluginOptions' => [
            'placeholder' => 'Select Mode of Payment'
        ]
    ]) ?>
    <?= $form->field($model, 'from')->textInput() ?>

    <?= $form->field($model, 'to')->textInput() ?>

    <div class="row">

        <div class="form-group col-sm-1 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#RoCheckRanges").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            swal({
                icon: 'error',
                title: res.error_message,
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