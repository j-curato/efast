<?php

use app\models\Books;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-disbursement-form">

    <?php
    $dv_aucs = (new \yii\db\Query())
        ->select(["dv_aucs.id", "dv_aucs.dv_number"])
        ->from("dv_aucs")
        ->all();
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'book_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => "Select Book"]
    ]) ?>

    <?= $form->field($model, 'dv_aucs_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($dv_aucs, 'id', 'dv_number'),

        'name' => "qwe",
        'options' => [
            'placeholder' => "Select DV",
        ],
        'pluginOptions' => [
            'allowClear' => true,

        ]

    ]) ?>



    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'name' => 'reporting_period',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => "mm-dd-yyyy"
        ]
    ]) ?>

    <?= $form->field($model, 'mode_of_payment')->widget(Select2::class, [
        'data' => ['Check', 'ADA'],
        'options' => [
            'placeholder' => "Select Mode of Payment"
        ]
    ]) ?>

    <?= $form->field($model, 'check_or_ada_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_cancelled')->widget(Select2::class, [
        'data' => ["Good", "Cancelled"],
        'options' => [
            'placeholder' => "Good/Cancelled"
        ]
    ]) ?>

    <?= $form->field($model, 'issuance_date')->widget(DatePicker::class, [
        'name' => 'issuance_date',
        'pluginOptions' => [
            "format" => "mm-dd-yyyy",
            "autoclose" => true
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



<?php

$script = <<<JS
            var q=$("select[name='CashDisbursement[dv_aucs_id]']")
            q.change(function(){
                console.log(q.val())
                $.ajax({
                type:"POST",
                url:window.location.pathname +"?r=cash-disbursement/get-dv",
                data:{dv_id:q.val()},
                success:function(data){
                    console.log(data)
                }
                })
            })

    $(document).ready(function(){

        // $.

    })
JS;
$this->registerJs($script);
?>