<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-aoq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'aoq_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pr_rfq_id')->widget(
        Select2::class,

        [
            'options' => ['placeholder' => 'Search for a Purchase Request'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Yii::$app->request->baseUrl . '?r=pr-rfq/search-rfq',
                    'dataType' => 'json',
                    'delay' => 250,
                    'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                    'cache' => true
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
            ],
        ]
    ) ?>

    <?= $form->field($model, 'pr_date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm',
            'minViewMode' => 'months'
        ]
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    $(document).ready(function() {
        $('#praoq-pr_rfq_id').on('change', function() {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=pr-aoq/get-rfq-info',
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(data)
                }
            })
        })

    })
</script>