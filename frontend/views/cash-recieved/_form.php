<?php

use app\models\Books;
use app\models\DocumentRecieve;
use app\models\MfoPapCode;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashRecieved */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-recieved-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'name' => 'date',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => "yyyy-mm-dd"
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'name' => "reporting_period",
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => "yyyy-mm",
                    'minViewMode' => "months",
                    'startView' => 'year'
                ]
            ]) ?>
        </div>
    </div>



    <?= $form->field($model, 'document_recieved_id')->widget(Select2::class, [

        'data' => ArrayHelper::map(DocumentRecieve::find()->asArray()->all(), 'id', 'name'),

        'pluginOptions' => [
            'placeholder' => "Select Document Recieve"
        ]
    ]) ?>

    <?= $form->field($model, 'book_id')->widget(Select2::class, [
        'name' => 'book_id',
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => "Select Book"
        ]
    ]) ?>

    <?= $form->field($model, 'mfo_pap_code_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(MfoPapCode::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => "Select MFO/PAP Code"
        ]
    ]) ?>

    <?= $form->field($model, 'nca_no')->textInput(['maxlength' => true]) ?>
    <!-- <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'nta_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nft_no')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($model, 'purpose')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
        'name' => 'amount_ph_1',
        'value' => null,
        'options' => [
            'placeholder' => 'Enter a valid amount...'
        ],
        'pluginOptions' => [
            'prefix' => '₱ ',
            'precision' => 2
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
    $('#cashrecieved-document_recieved_id').change(function(){
        var document_id=$('#cashrecieved-document_recieved_id').val()
        $.ajax({
            type:"POST",
            url:window.location.pathname +"?r=document-recieve/find-document",
            data:{document_id:document_id},
            success:function(data){
                var res = JSON.parse(data)
                console.log(res.result)

                if ( res.result === 'nta' ||res.result === 'nca' ||res.result === 'nft'){
                    $("label[for=cashrecieved-nca_no]").text(res.result.toUpperCase() + " No.")
                    $("label[for=cashrecieved-nca_no]").prop('disabled', true);
                }
            }
        })
    })
JS;
$this->registerJs($script);
?>