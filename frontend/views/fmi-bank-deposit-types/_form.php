<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDepositTypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fmi-bank-deposit-types-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <?= $form->field($model, 'deposit_type')->textInput(['maxlength' => true]) ?>

    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
SweetAlertAsset::register($this);
$this->registerJsFile("@web/frontend/modules/js/activeFormAjaxSubmit.js", ['depends' => [JqueryAsset::class]]);
$js = <<<JS

    $(document).ready(function(){
        $("#FmiBankDepositTypes").on("beforeSubmit", function(event) {
            event.preventDefault();
            var form = $(this);
            ajaxSubmit(form)
            return false;
        });
      
    })
JS;
$this->registerJs($js);

?>