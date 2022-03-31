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
$row_number = 1;
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
    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, '_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-4">

                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'invoice_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]

                ]) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'invoice_number')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
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
        <table id="pr_iar_items_table" class="table table-striped">
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
            <tbody>

                <?php
                foreach ($iar_items as $val) {
                    echo "<tr>
                     
                            <td style='display:none'><input type='hidden' value='{$val['aoq_entry_id']}' class='aoq_entry_id' name='aoq_entry_id[{$row_number}]'></td>
                            <td>{$val['payee']}</td>
                            <td>
                            <span> {$val['description']}</span>
                            <br>
                            <span>{$val['specification']}</span>
                            </td>
                            <td>{$val['bac_code']}</td>
                            <td>{$val['remark']}</td>
                            <td>{$val['unit_of_measure']}</td>
                            <td>{$val['unit_cost']}</td>
                            <td>{$val['quantity']}</td>
                            <td><input type='number' name='quantity[{$row_number}]' class='form-control' value='{$val['quantity_recieve']}'></td>
                            <td style='float:right;' >
                                <a class='remove_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                            </td>


                        </tr>";
                    $row_number++;
                }
                ?>
            </tbody>
        </table>
        <table id="po_items_table" class="table table-striped">
            <thead>
                <th>Payee</th>
                <th>Description/Specification</th>
                <th>BAC Code</th>
                <th>Remarks</th>
                <th>Unit of Measure</th>
                <th>Unit Cost</th>
                <th>Quantity</th>
            </thead>
            <tbody>

            </tbody>
        </table>



        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
<style>
    .container {
        background-color: white;
    }
</style>
<?php

$csrfToken  = Yii::$app->request->csrfToken;
$csrf = Yii::$app->request->csrfParam;
?>
<script>
    let row_number = 0;
    $(document).ready(function() {
        row_number = <?php echo $row_number ?>;
        $("#priar-fk_pr_purchase_order_id").change(function() {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=pr-iar/get-po-items',
                data: {
                    id: $(this).val(),
                    '<?= $csrf ?>': '<?= $csrfToken ?>'
                },
                success: function(data) {
                    displayPoItems(JSON.parse(data))
                }
            })
        })
        $('#pr_iar_items_table').on('click', '.remove_row', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('#po_items_table').on('click', '.add_accounting_entry_row', function(event) {
            event.preventDefault();
            const source = $(this).closest('tr');
            // source.find('.chart-of-accounts').select2('destroy')
            const clone = source.clone(true);
            const debit = clone.find('.debit')
            const credit = clone.find('.credit')
            const chart_of_account = clone.find('.chart-of-accounts')
            clone.find('.add_accounting_entry_row').closest('td').remove()
            clone.append(` <td><input type='number' name='quantity[${row_number}]' class='form-control'></td>`)
            clone.append(` <td> <a class='remove_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a></td>`)
            clone.find('.aoq_entry_id').attr('name', `aoq_entry_id[${row_number}]`)
            // chart_of_account.attr('name', `object_code[${accounting_entry_row}]`)
            // debit.attr('name', `debit[${accounting_entry_row}]`)
            // credit.attr('name', `credit[${accounting_entry_row}]`)
            // chart_of_account.val('')
            // debit.val('')
            // credit.val('')
            $('#pr_iar_items_table tbody').append(clone)
            // maskAmount()
            // accountingCodesSelect()
            row_number++;
        });
        $("#priar-fk_pr_purchase_order_id").trigger('change')
    })

    function displayPoItems(data) {
        $("#po_items_table tbody").html('')

        $.each(data, function(key, val) {

            let row = `<tr>
                <td style='display:none'><input type='hidden' value='${val.aoq_entry_id}' class='aoq_entry_id'></td>
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
                <td style='float:right;' >
                    <a class='add_accounting_entry_row btn btn-primary btn-xs' type='button' ><i class='fa fa-plus fa-fw'></i> </a>
                </td>
            </tr>`;
            $('#po_items_table tbody').append(row)
        })
        // <td><input type='number' name='quantity[${val.aoq_entry_id}]' class='form-control'></td>

    }
</script>