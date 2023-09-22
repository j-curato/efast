<?php

use app\models\Office;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */
/* @var $form yii\widgets\ActiveForm */

$pr = '';
$employee = '';
$items = json_encode([]);
$checked_items = [];
if (!empty($pr_items)) {
    $items = json_encode($pr_items);
}

if (!empty($error)) {
    echo SweetAlert::widget([
        'options' => [
            'title' => "Error",
            'text' => "$error",
            'type' => "error"
        ]
    ]);
}

if (!empty($model->id)) {

    $pr_query   = Yii::$app->db->createCommand("SELECT id,pr_number   FROM pr_purchase_request WHERE id = :id")
        ->bindValue(':id', $model->pr_purchase_request_id)
        ->queryAll();
    $pr = ArrayHelper::map($pr_query, 'id', 'pr_number');
    $employee_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->employee_id)
        ->queryAll();
    $employee = ArrayHelper::map($employee_query, 'employee_id', 'employee_name');

    $items = json_encode(Yii::$app->db->createCommand("SELECT pr_purchase_request_item_id as id FROM pr_rfq_item WHERE pr_rfq_item.pr_rfq_id = :id")
        ->bindValue(':id', $model->id)
        ->queryAll());
}
if (!empty($model->pr_purchase_request_id)) {
    $pr_query   = Yii::$app->db->createCommand("SELECT id,pr_number   FROM pr_purchase_request WHERE id = :id")
        ->bindValue(':id', $model->pr_purchase_request_id)
        ->queryAll();
    $pr = ArrayHelper::map($pr_query, 'id', 'pr_number');
}
?>

<div class="pr-rfq-form">

    <div class="card" style="padding: 1rem;">

        <ul>
            <li>Note</li>
            <li>The RFQ number is updated every time the date changes.</li>
        </ul>


        <div class="panel-body">

            <?php $form = ActiveForm::begin([
                'id' => $model->formName()
            ]); ?>

            <div class="row">
                <?php
                if (Yii::$app->user->can('super-user')) {
                ?>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                            'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                            'pluginOptions' => [
                                'placeholder' => 'Select Office',
                            ],

                        ]) ?>
                    </div>
                <?php } ?>
                <div class="col-sm-2">
                    <?= $form->field($model, '_date')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true
                        ],
                        'options' => [
                            'readonly' => true,
                            'style' => 'background-color:white'
                        ]
                    ]) ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'deadline')->widget(DateTimePicker::class, [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd HH:ii P',
                            'autoclose' => true
                        ],
                        'options' => [
                            'readonly' => true,
                            'style' => 'background-color:white'
                        ]

                    ]) ?>

                </div>
                <?php if (empty($model->pr_purchase_request_id)) : ?>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'pr_purchase_request_id')->widget(Select2::class, [
                            'data' => $pr,
                            'options' => ['placeholder' => 'Search for a Purchase Request'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => Yii::$app->request->baseUrl . '?r=pr-purchase-request/search-pr',
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
                <?php endif; ?>

                <div class="col-sm-3">
                    <?= $form->field($model, 'employee_id')->widget(Select2::class, [
                        'data' => $employee,
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
            <?= $form->field($model, 'project_location')->textarea() ?>
            <table id="pr_data" class="table" style="margin-top: 3rem;margin-bottom:3rem">
                <tbody>
                    <tr>
                        <td>
                            <span class='pr_data_header'>
                                Date Propose:
                            </span>
                            <span id="date_propose"></span>
                        </td>
                        <td>
                            <span class='pr_data_header'> PR Number:</span>
                            <span id="pr_number"></span>
                        </td>
                        <td>
                            <span class='pr_data_header'> Book:</span>
                            <span id="book"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class='pr_data_header'>
                                Purpose:
                            </span>
                            <span id="purpose"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class='pr_data_header'>Office:</span>
                            <span id="office"></span>
                        </td>
                        <td>
                            <span class='pr_data_header'>Division:</span>
                            <span id="division"></span>
                        </td>
                        <td>
                            <span class='pr_data_header'>Unit:</span>
                            <span id="unit"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class='pr_data_header'>Prepared By:</span>
                            <span id="prepared_by"></span>
                        </td>
                        <td>
                            <span class='pr_data_header'>Requested By:</span>
                            <span id="requested_by"></span>
                        </td>
                        <td>
                            <span class='pr_data_header'>Approved By:</span>
                            <span id="approved_by"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="data-table" class="table table-striped">
                <thead>
                    <th>Checkbox</th>
                    <th>Stock Number</th>
                    <th>Description</th>
                    <th>Unit of Measure</th>
                    <th>Specification</th>
                    <th>Unit Cost</th>
                    <th>Quantity</th>
                    <th>Total Unit Cost</th>
                </thead>
                <tbody>
                    <?php
                    $prDetailes = [];
                    if (!empty($model->id)) {
                        $prDetailes = !empty($model->id) ? $model->purchaseRequest->getPrDetails() : [];
                        $items = $model->getItems();
                        $prItemIds = array_column($items, 'prItemId');
                        $prItems = $model->purchaseRequest->getPrItems();
                        $itemsNotSelected = array_filter($prItems, function ($item) use ($prItemIds) {
                            return  !in_array($item['item_id'], $prItemIds);
                        });
                        foreach ($items as $itm) {
                            $grossAmt = floatval($itm['unit_cost']) * intval($itm['quantity']);
                            $specs =   preg_replace('#\[n\]#', "<br>", $itm['specification']);
                            echo "<tr>
                                <td style='text-align:center;'>
                                    <input type='checkbox' class='form-check-input' value='{$itm['prItemId']}' name='items[][pr_id]' checked >
                                </td>
                                <td>
                                    {$itm['bac_code']}
                                </td>
                                <td>
                                    {$itm['stock_title']}
                                </td>
                                <td>
                                    {$itm['unit_of_measure']}
                                </td>
                                <td>
                                    {$specs}
                                </td>
                                <td>
                                    
                                    " . number_format($itm['unit_cost'], 2) . "
                                </td> 
                                <td>
                                    {$itm['quantity']}
                                </td>
                                <td>
                                    " . number_format($grossAmt, 2) . "
                                </td>
                            </tr>";
                        }
                        foreach ($itemsNotSelected as $itm) {
                            $grossAmt = floatval($itm['unit_cost']) * intval($itm['quantity']);
                            $specs =   preg_replace('#\[n\]#', "<br>", $itm['specification']);
                            echo "<tr>
                                    <td style='text-align:center;'>
                                        <input type='checkbox' class='form-check-input' value='{$itm['item_id']}' name='items[][pr_id]'  >
                                    </td>
                                    <td>
                                        {$itm['bac_code']}
                                    </td>
                                    <td>
                                        {$itm['stock_title']}
                                    </td>
                                    <td>
                                        {$itm['unit_of_measure']}
                                    </td>
                                    <td>
                                        {$specs}
                                    </td>
                                    <td>
                                        
                                        " . number_format($itm['unit_cost'], 2) . "
                                    </td> 
                                    <td>
                                        {$itm['quantity']}
                                    </td>
                                    <td>
                                        " . number_format($grossAmt, 2) . "
                                    </td>
                                </tr>";
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

    </div>
</div>

<style>
    .pr_data_header {
        font-weight: bold;
    }

    li {
        color: red;
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>
<script>
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    function displayPrItems(data) {
        $.each(data, (key, val) => {
            var myStr = val.specification
            var row = `<tr>
                            <td style='text-align:center;'>
                                <input type='checkbox' class='form-check-input' value='${val.item_id}' name='items[${key}][pr_id]'  data-value='${val.item_id}'>
                            </td>
                            <td>
                                ${val.bac_code}
                            </td>
                            <td>
                                ${val.stock_title}
                            </td>
                            <td>
                                ${val.unit_of_measure}
                            </td>
                            <td>
                                ${val.specification}
                            </td>
                            <td>
                                ${thousands_separators(val.unit_cost)}
                            </td> 
                            <td>
                                ${val.quantity}
                            </td>
                            <td>
                                ${thousands_separators(val.total_cost)}
                            </td>
                        </tr>`
            $('#data-table tbody').append(row)

        })

    }

    function DisplayPrDetails(data) {

        $('#date_propose').text(data['date_propose'])
        $('#pr_number').text(data['pr_number'])
        $('#book').text(data['book_name'])
        $('#project_title').text(data['project_title'])
        $('#purpose').text(data['purpose'])
        $('#office').text(data['office_name'])
        $('#division').text(data['division'])
        $('#unit').text(data['unit'])
        $('#prepared_by').text(data['prepared_by'])
        $('#requested_by').text(data['requested_by'])
        $('#approved_by').text(data['approved_by'])
    }

    function getPrItems() {
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=pr-rfq/get-pr-items',
            data: {
                id: $('#prrfq-pr_purchase_request_id').val(),
                _csrf: csrfToken
            },
            success: function(data) {
                $('#data-table tbody').html('')
                var res = JSON.parse(data)
                // $('#date_propose').text(res.prDetails['date_propose'])
                // $('#pr_number').text(res.prDetails['pr_number'])
                // $('#book').text(res.prDetails['book_name'])
                // $('#project_title').text(res.prDetails['project_title'])
                // $('#purpose').text(res.prDetails['purpose'])
                // $('#office').text(res.prDetails['office'])
                // $('#division').text(res.prDetails['division'])
                // $('#unit').text(res.prDetails['unit'])
                // $('#prepared_by').text(res.prDetails['prepared_by'])
                // $('#requested_by').text(res.prDetails['requested_by'])
                // $('#approved_by').text(res.prDetails['approved_by'])
                displayPrItems(res.prItems)
                DisplayPrDetails(res.prDetails)
                // if (itms.length > 0) {

                //     for (var i = 0; i < itms.length; i++) {
                //         item_id = itms[i]['id']
                //         $(`input[data-value='${item_id}']`).prop('checked', true)
                //     }
                // }



            }
        })

        return true

    }
    $(document).ready(function(e) {
        DisplayPrDetails(<?= json_encode($prDetailes) ?>)
        $('#prrfq-pr_purchase_request_id').on('change', function(e) {
            e.preventDefault()
            getPrItems()
        })

    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<<JS
$(document)
$('#PrRfq').on('beforeSubmit', function(e) {
    var \$form = $(this);
    // if (\$form.yiiActiveForm('validate')) {
    //     console.log('true')
    //     return true;
    // } else {
    //     console.log('false')
    //     return false;
    // }
    $.ajax({
        url: \$form.attr("action"),
        type: \$form.attr("method"),
        data: \$form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            swal({
                icon: 'error',
                title: res.errors,
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