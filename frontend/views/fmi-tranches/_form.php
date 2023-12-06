<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\FmiTranches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fmi-tranches-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>


    <?= $form->field($model, 'tranche_number')->textInput(['maxlength' => true]) ?>

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
        $("#FmiTranches").on("beforeSubmit", function(event) {
            event.preventDefault();
            var form = $(this);
            ajaxSubmit(form)
            return false;
        });
      
    })
JS;
$this->registerJs($js);

?>