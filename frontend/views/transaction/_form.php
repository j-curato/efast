<?php

use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */

$iar_data = [];
$iar_val = '';
if ($model->type == 'single' || $model->type == 'multiple') {
    $query = Yii::$app->db->createCommand("SELECT transaction_iars.fk_iar_id,iar.iar_number FROM transaction_iars
    LEFT JOIN iar ON transaction_iars.fk_iar_id = iar.id
     WHERE transaction_iars.fk_transaction_id = :id")
        ->bindValue(':id', $model->id)
        ->queryAll();
    $iar_data = ArrayHelper::map($query, 'fk_iar_id', 'iar_number');
    // var_dump();
    if ($model->type == 'multiple') {
        if (!empty($query)) {
            $iar_val = array_column($query, 'fk_iar_id');
        }
    } else if ($model->type == 'single') {
        if (!empty($query)) {
            $iar_val = key($iar_data);
        }
    }
}
?>

<div class="transaction-form">

    <?php
    $r_center = (new \yii\db\Query())->select('*')
        ->from('responsibility_center');


    $user = strtolower(Yii::$app->user->identity->province);
    $division = strtolower(Yii::$app->user->identity->division);

    if (

        $user === 'ro' &&
        $division === 'sdd' ||
        $division === 'cpd' ||
        $division === 'idd' ||
        $division === 'ord'


    ) {
        $r_center->where('name LIKE :name', ['name' => $division]);
    }
    $respons_center = $r_center->all();
    $payee = (new \yii\db\Query())->select('*')->from('payee')->where('isEnable=1')->all();
    ?>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->widget(Select2::class, [
        'name' => 'float_state_01',
        'data' => ['no-iar' => 'No IAR', 'single' => 'Single IAR', 'multiple' => 'Multiple Iar'],
        'options' => ['placeholder' => 'Select Transaction Type...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    <div class="row" id="multiple">
        <div class="col-sm-12">
            <label for="multiple_iar"> Iar's</label>
            <?php
            echo Select2::widget([
                'name' => 'multiple_iar',
                'data' => $iar_data,
                'value' => $iar_val,
                'options' => ['placeholder' => 'Select IARs...', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=iar/search-iar',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row" id="single">
        <div class="col-sm-12">
            <label for="single_iar"> Iar's</label>
            <?php
            echo Select2::widget([
                'name' => 'single_iar',
                'id' => 'single_iar',
                'data' => $iar_data,
                'value' => $iar_val,
                'options' => ['placeholder' => 'Select a IAR...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=iar/search-iar',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <?php

        if (
            !Yii::$app->user->can('super-user')


        ) {
        } else {

        ?>
            <div class="col-sm-4">


                <?= $form->field($model, 'responsibility_center_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($respons_center, 'id', 'name'),
                    'options' => ['placeholder' => 'Select  Responsibility Center'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        <?php } ?>

        <div class="col-sm-4">
            <?= $form->field($model, 'payee_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($payee, 'id', 'account_name'),
                'options' => ['placeholder' => 'Select  Payee'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'transaction_date')->widget(DatePicker::class, [
                'name' => 'date',
                'value' => date("m-d-Y"),
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'mm-dd-yyyy'
                ]

            ]) ?>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-6">
            <?= $form->field($model, 'earmark_no')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-sm-6">

            <?= $form->field($model, 'payroll_number')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <?= $form->field($model, 'particular')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'gross_amount')->widget(MaskMoney::class, [
        'options' => [
            'class' => 'amounts',
        ],
        'pluginOptions' => [
            'prefix' => 'PHP ',
            'allowNegative' => true
        ],
    ]) ?>






    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    #multiple,
    #single {
        display: none;
    }
</style>
<script>
    function transactionType() {
        const transaction_type = $('#transaction-type').val()
        if (transaction_type == 'single') {
            $('#single').show()
            $('#multiple').hide()
        } else if (transaction_type == 'multiple') {
            $('#multiple').show()
            $('#single').hide()
            // $("#transaction-payee_id").val('').trigger('change')
            // $("#transaction-gross_amount-disp").val('')
            // $("#transaction-gross_amount").val('')
            // $("#transaction-particular").val('')



        }
    }
    $(document).ready(function() {
        transactionType()
        $('#transaction-type').change(function() {

            transactionType()

        })
        $("#single_iar").on('change', function() {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + "?r=transaction/iar-details",
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    $("#transaction-payee_id").val(res.id).trigger('change')
                    $("#transaction-gross_amount-disp").val(res.amount)
                    $("#transaction-gross_amount").val(res.amount)
                    $("#transaction-particular").val(res.purpose)

                }
            })
        })


    })
</script>