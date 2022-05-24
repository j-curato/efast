<?php

use aryelds\sweetalert\SweetAlert;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */
/* @var $form yii\widgets\ActiveForm */

$model->project_location = 'DTI Regional Office XIII, Butuan City';
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

    <div class="panel panel-primary">


        <div class="panel-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">

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
                    <?= $form->field($model, 'deadline')->widget(DatePicker::class, [
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

                <div class="col-sm-4">
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
                                Project Title:
                            </span>
                            <span id="project_title"></span>
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

                </tbody>
            </table>
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

<style>
    .pr_data_header {
        font-weight: bold;
    }
</style>
<script>
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $('#w0').on('submit', function(e) {
        console.log($('#w0').serialize())
    })

    function prItems() {
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=pr-purchase-request/get-items',
            data: {
                id: $('#prrfq-pr_purchase_request_id').val(),
                _csrf: csrfToken
            },
            success: function(data) {
                $('#data-table tbody').html('')
                var res = JSON.parse(data)
                console.log(res.pr_data)
                $('#date_propose').text(res.pr_data['date_propose'])
                $('#pr_number').text(res.pr_data['pr_number'])
                $('#book').text(res.pr_data['book_name'])
                $('#project_title').text(res.pr_data['project_title'])
                $('#purpose').text(res.pr_data['purpose'])
                $('#office').text(res.pr_data['office'])
                $('#division').text(res.pr_data['division'])
                $('#unit').text(res.pr_data['unit'])
                $('#prepared_by').text(res.pr_data['prepared_by'])
                $('#requested_by').text(res.pr_data['requested_by'])
                $('#approved_by').text(res.pr_data['approved_by'])


                for (var i = 0; i < res.pr_items_data.length; i++) {
                    var myStr = res.pr_items_data[i]['specification']
                    var row = `
                        <tr>
                            <td>
                                <input type='checkbox' class='form-check-input' value='${res.pr_items_data[i]['pr_item_id']}' name='pr_purchase_request_item_id[]' data-value = '${res.pr_items_data[i]['pr_item_id']}'>
                            </td>
                            <td>
                                ${res.pr_items_data[i]['bac_code']}
                            </td>
                            <td>
                                ${res.pr_items_data[i]['stock_title']}
                            </td>
                            <td>
                                ${res.pr_items_data[i]['unit_of_measure']}
                            </td>
                            <td>
                                ${res.pr_items_data[i]['specification']}
                            </td>
                            <td>
                                ${res.pr_items_data[i]['unit_cost']}
                            </td> 
                            <td>
                                ${res.pr_items_data[i]['quantity']}
                            </td>
                            <td>
                                ${res.pr_items_data[i]['total_cost']}
                            </td>
                        </tr>
                        
                        `
                    $('#data-table tbody').append(row)

                }



                var itms = <?php echo $items ?>;


                if (itms.length > 0) {

                    for (var i = 0; i < itms.length; i++) {

                        item_id = itms[i]['id']

                        $(`input[data-value='${item_id}']`).attr('checked', true)


                    }
                }



            }
        })

        return true

    }
    $(document).ready(function(e) {


        $('#prrfq-pr_purchase_request_id').on('change', function(e) {
            e.preventDefault()

            prItems()


        })
        // 

        // if ($("#prrfq-pr_purchase_request_id").val() != '') {
        //     console.log($("#prrfq-pr_purchase_request_id").val())
        //     prItems()
        // }
    })
</script>

<?php
$script = <<<JS
    
    $(document).ready(function(e) {
        if ($("#prrfq-pr_purchase_request_id").val() != '') {
           
            var pr_items = $items
            var item_id = ''
            if ( prItems()){
                var rowCount = $('#data-table tr').length
            }

        }
    })
JS;
$this->registerJs($script)
?>