<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use app\components\helpers\MyHelper;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-order-transmittal-form">

    <?php


    $gridColumns = [

        [
            'label' => 'Action',
            'format' => 'raw',
            'value' => function ($model) {
                return "<button 
                            class = 'add_row btn-xs btn-primary'
                            type = 'button'
                            onclick = 'addRow(this)'
                            data-value = '{$model->id}'
                    ><i class='fa fa-plus'></i></button>";
            }
        ],
        [
            'attribute' => 'id',
            'format' => 'raw',
            'hidden' => true,
            'value' => function ($model) {


                return "<input type='text' class='iar_id' value='{$model->id}' name='items[]'>";
            }
        ],
        'iar_number',
        'ir_number',
        'rfi_number',
        'purpose',
        'division',
        'inspector_name',
        'requested_by_name',
        'end_user',
        'po_number',
        'payee_name',

    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            // 'heading' => 'List of Areas',
        ],
        'pjax' => true,
        'export' => false,
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',

        ],

        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'columns' => $gridColumns,
    ]); ?>

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',

                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_officer_in_charge')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_officer_in_charge, 'all'), 'employee_id', 'employee_name'),
                'options' => ['placeholder' => 'Search for a Employee ...'],
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
                        'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_approved_by, 'all'), 'employee_id', 'employee_name'),
                'options' => ['placeholder' => 'Search for a Employee ...'],
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
                        'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ])  ?>
        </div>
    </div>

    <table class="table table-striped" id="transaction_table" style="background-color: white;">
        <thead>
            <th>IAR NUmber</th>
            <th>IR NUmber</th>
            <th>RFI Number</th>
            <th>Purpose</th>
            <th>Division</th>
            <th>Inspector</th>
            <th>Requested By</th>
            <th>End-User</th>
            <th>PO Number</th>
            <th>Payee</th>
        </thead>
        <tbody>
            <?php
            $item_row = 1;
            if (!empty($model->id)) {
                foreach ($items as $val) {
                    $item_id = $val['item_id'];
                    $iar_id = $val['id'];
                    $iar_number = $val['iar_number'];
                    $ir_number = $val['ir_number'];
                    $rfi_number = $val['rfi_number'];
                    $end_user = $val['end_user'];
                    $purpose = $val['purpose'];
                    $inspector_name = $val['inspector_name'];
                    $division = $val['division'];
                    $po_number = $val['po_number'];
                    $payee_name = $val['payee_name'];
                    $requested_by_name = $val['requested_by_name'];

                    echo "<tr>
                        <td style='display:none'><input type='text' class='item_id' value='{$item_id}' name='items[$item_row][id]'>
                        <input type='text' class='po_id' value='{$iar_id}' name='items[$item_row][fk_iar_id]'></td>
                        <td>$iar_number</td>
                        <td>$ir_number</td>
                        <td>$rfi_number</td>
                        <td>$purpose</td>
                        <td>$division</td>
                        <td>$inspector_name</td>
                        <td>$requested_by_name</td>
                        <td>$end_user</td>
                        <td>$po_number</td>
                        <td>$payee_name</td>

                        <td><button type='button' class='remove btn-xs btn-danger'><i class='fa fa-times'></i></button></td>
                    </tr>";
                    $item_row++;
                }
            }
            ?>
        </tbody>

    </table>
    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%;margin:3rem 0 4rem 0']); ?>
    <?php ActiveForm::end(); ?>
</div>

<script>
    let row_num = <?= $item_row ?>;

    function addRow(row) {
        const $this = $(row)
        const clone = $this.closest('tr').clone()
        clone.find('.iar_id').attr('name', `items[${row_num}][fk_iar_id]`)
        clone.find('.add_row').parent().remove()
        clone.append('<td><button type="button" class="remove btn-xs btn-danger"><i class="fa fa-times"></i></button></td>')
        $('#transaction_table').append(clone)
        row_num++
    }
    $(document).ready(function() {

        $('#transaction_table').on('click', '.remove', function(e) {
            console.log(this)
            $(this).closest('tr').remove()
        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#IarTransmittal").on("beforeSubmit", function (event) {
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
                title: res,
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