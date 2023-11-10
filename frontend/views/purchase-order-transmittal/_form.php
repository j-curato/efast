<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use app\components\helpers\MyHelper;
use aryelds\sweetalert\SweetAlertAsset;


/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-order-transmittal-form card" style="padding: 1rem;">

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


                return "<input type='text' class='po_id' value='{$model->id}' name='pr_purchase_order_item_ids[]'>";
            }
        ],
        'serial_number',
        'payee',
        'purpose',

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
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]);
            ?>

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
            <th>PO Number</th>
            <th>Payee</th>
            <th>Purpose</th>
        </thead>
        <tbody>
            <?php
            $item_row = 1;
            if (!empty($model->id)) {
                foreach ($model->getTransmittalItems() as $val) {
                    $po_id = $val['po_id'];
                    $id = $val['id'];
                    $serial_number = $val['serial_number'];
                    $payee = $val['payee'];
                    $purpose = $val['purpose'];
                    echo "<tr>
                        <td style='display:none'>
                        <input type='text' class='item_id' value='{$id}' name='items[$item_row][id]'>
                        <input type='text' class='po_id' value='{$po_id}' name='items[$item_row][fk_purchase_order_item_id]'>
                        </td>
                        <td>$serial_number</td>
                        <td>$payee</td>
                        <td>$purpose</td>
                        <td><button type='button' class='remove btn-xs btn-danger'><i class='fa fa-times'></i></button></td>
                    </tr>";
                    $item_row++;
                }
            }
            ?>
        </tbody>

    </table>
    <div class="row justify-content-center">

        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    let row_num = 0;

    function addRow(row) {
        const $this = $(row)
        const clone = $this.closest('tr').clone()
        clone.find('.po_id').attr('name', `items[${row_num}][fk_purchase_order_item_id]`)
        // console.log(clone.find('.po_id').attr('name'))
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
$script = <<< JS
   

   $(document).ready(()=>{
        $("#PoTransmittalToCoa").on("beforeSubmit", function (event) {
            event.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function (data) {
                    let res = JSON.parse(data)
                    console.log(res)
                    swal({
                        icon: 'error',
                        title: res.error,
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
    })
JS;
$this->registerJs($script);
?>