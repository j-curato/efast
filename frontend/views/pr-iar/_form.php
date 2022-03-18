<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrIar */
/* @var $form yii\widgets\ActiveForm */

$purchase_order = '';
$inspect_officer = '';
$prop_custodian = '';
if (!empty($model->id)) {
    $inspect_officer_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_inspection_officer)
        ->queryAll();
    $inspect_officer = ArrayHelper::map($inspect_officer_query, 'employee_id', 'employee_name');
    $prop_custodian_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_property_custodian)
        ->queryAll();
    $prop_custodian = ArrayHelper::map($prop_custodian_query, 'employee_id', 'employee_name');
    $purchase_order_query   = Yii::$app->db->createCommand("SELECT id,po_number FROM pr_purchase_order WHERE id = :id")
        ->bindValue(':id', $model->fk_pr_purchase_order_id)
        ->queryAll();
    $purchase_order = ArrayHelper::map($purchase_order_query, 'id', 'po_number');
}
?>

<div class="pr-iar-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, '_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) ?>
        </div>
        <div class="col-sm-2">

            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'minViewMode' => 'months',
                    'autoclose' => true,
                    'format' => 'yyyy-mm'
                ]
            ]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'invoice_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]

            ]) ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'invoice_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'fk_pr_purchase_order_id')->widget(Select2::class, [
                'options' => ['placeholder' => 'Search Purchase Order Number'],
                'data' => $purchase_order,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=pr-purchase-order/search-purchase-order',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">

            <?= $form->field($model, 'fk_inspection_officer')->widget(Select2::class, [
                'options' => ['placeholder' => 'Search Purchase Order Number'],
                'data' => $inspect_officer,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ]
            ],) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_property_custodian')->widget(Select2::class, [
                'options' => ['placeholder' => 'Search Purchase Order Number'],
                'data' => $prop_custodian,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ]
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <table id="po_items_table" class="table table-striped">
        <thead>
            <th>Payee</th>
            <th>Description/Specification</th>
            <th>BAC Code</th>
            <th>Remarks</th>
            <th>Unit of Measure</th>
            <th>Unit Cost</th>
            <th>Quantity</th>
            <th>Quantity Recieve</th>
        </thead>
        <tbody></tbody>
    </table>
    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function() {

        $("#priar-fk_pr_purchase_order_id").change(function() {
            console.log('qwe')
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=pr-iar/get-po-items',
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    console.log(data)
                    displayPoItems(JSON.parse(data))
                }
            })
        })
    })

    function displayPoItems(data) {
        $("#po_items_table tbody").html('')

        $.each(data, function(key, val) {

            let row = `<tr>
            <td>${val.payee}</td>
            <td>
            <span> ${val.description}</span>
            <br>
            <span>${val.specification}</span>
            </td>
            <td>${val.bac_code}</td>
            <td>${val.remark}</td>
            <td>${val.unit_of_measure}</td>
            <td>${val.unit_cost}</td>
            <td>${val.quantity}</td>
            <td><input type='number' name='quantity[${val.aoq_entry_id}]' class='form-control'></td>
            

            </tr>`;
            $('#po_items_table tbody').append(row)
        })

    }
</script>