<?php

use app\models\BacComposition;
use app\models\PrContractType;
use app\models\PrModeOfProcurement;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\widgets\DatePicker as WidgetsDatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseOrder */
/* @var $form yii\widgets\ActiveForm */






$auth_official = '';
$accounting_unit = '';
$aoq_id = '';
$model_id = '';
if (!empty($model->id)) {
    $auth_official_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_auth_official)
        ->queryAll();
    $auth_official = ArrayHelper::map($auth_official_query, 'employee_id', 'employee_name');
    $accounting_unit_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_accounting_unit)
        ->queryAll();
    $accounting_unit = ArrayHelper::map($accounting_unit_query, 'employee_id', 'employee_name');
    $aoq_id_query   = Yii::$app->db->createCommand("SELECT id, aoq_number FROM pr_aoq WHERE id = :id")
        ->bindValue(':id', $model->fk_pr_aoq_id)
        ->queryAll();
    $aoq_id = ArrayHelper::map($aoq_id_query, 'id', 'aoq_number');
    $model_id = $model->id;
}
?>

<div class="pr-purchase-order-form">
    <div class="con">

        <input type="hidden" name="" id="model_id" value="<?= $model_id ?>">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_pr_aoq_id')->widget(Select2::class, [
                    'data' => $aoq_id,
                    'options' => ['placeholder' => 'Search for a RFQ'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=pr-aoq/search-aoq',
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
            <div class="col-sm-3">
                <?= $form->field($model, 'delivery_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_contract_type_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(PrContractType::find()->asArray()->all(), 'id', 'contract_name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Contract'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_mode_of_procurement_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(PrModeOfProcurement::find()->asArray()->all(), 'id', 'mode_name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Mode'
                    ]
                ]) ?>
            </div>


        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'place_of_delivery')->textInput() ?>

            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'delivery_term')->textInput() ?>

            </div>
        </div>
        <?= $form->field($model, 'payment_term')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'fk_auth_official')->widget(Select2::class, [
                    'data' => $auth_official,
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
            <div class="col-sm-6">
                <?= $form->field($model, 'fk_accounting_unit')->widget(Select2::class, [
                    'data' => $accounting_unit,
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



        <input type="button" id="change_lowest" class="btn btn-info" value='Change'>
        <div class="row" id="change_bac">
            <div class="col-sm-3">
                <label for="bac_date">RBAC Date</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'bac_date',
                    'id' => 'bac_date',
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ],
                    'pluginOptions' =>
                    [
                        'autoclose' => true
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <label for="bac_rso_number">RSO Number</label>

                <?php
                echo Select2::widget([
                    'name' => 'bac_rso_number',
                    'id' => 'bac_rso_number',
                    'data' => ArrayHelper::map(BacComposition::find()->asArray()->all(), 'id', 'rso_number'),
                    'pluginOptions' => [
                        'placeholder' => 'Select RSO Number'
                    ]
                ])
                ?>
            </div>
        </div>
        <table id='lowest_table' class="table table striped">
            <thead>
                <tr>
                    <th colspan="4">
                        Lowest
                    </th>

                </tr>
                <tr>
                    <th>Payee</th>
                    <th>Payee Address</th>
                    <th>Payee Tin</th>
                    <th>Unit Cost</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <table id="aoq_items_table" class="table table-striped">

            <thead>
                <th>Payee</th>
                <th>BAC Code</th>
                <th>Description</th>
                <th>Specification</th>
                <th>Remark</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Amount</th>
            </thead>
            <tbody></tbody>
        </table>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<style>
    td,
    th {
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    #change_bac {
        display: none;
    }

    .con {
        background-color: white;
        border: 1px solid black;
        padding: 2em;
    }
</style>
<?php
$script = <<<JS
    $(document).ready(function(){
        if ($("#model_id").val() != '') {
            console.log('qweqwe')
            $('#prpurchaseorder-fk_pr_aoq_id').trigger('change')
        }
    })
JS;
$this->registerJs($script);

?>
<script>
    $(document).ready(function() {
        $("#change_lowest").click(function(e) {
            e.preventDefault()
            changeLowest()
        })
        $('#prpurchaseorder-fk_pr_aoq_id').change(function() {
            console.log('qweq')
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=pr-purchase-order/aoq-info',
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res)
                    displayLowest(res.lowest)
                    displayAoqItems(res.aoq_items)
                }
            })
        })

    })

    function changeLowest() {

        if ($('#change_bac').is(":visible")) {
            $('#change_bac').hide()
            $('#change_lowest').val('Change')
            $('#change_lowest').prop('class', 'btn btn-info')
        } else {

            $('#change_lowest').val('Hide')
            $('#change_lowest').prop('class', 'btn btn-danger')
            $('#change_bac').show()
        }
        if ($('.checkBox').is(":visible")) {
            $('.checkBox').hide()
            // $(":checkbox").attr("checked", true);
            // $(":checkbox").attr("checked", false);
            $(':checkbox').each(function() {

                this.checked = false;
            });
        } else {

            $('.checkBox').show()
        }


        // $('#bac_date').prop('required', true)
        // $('#bac_rso_number').prop('required', true)

    }

    function displayLowest(data) {
        $('#lowest_table tbody').html('')
        $.each(data, function(key, val) {
            let row = `<tr>
                <td>${val.payee}</td>
                <td>${val.address}</td>
                <td>${val.tin_number}</td>
                <td>${val.unit_cost}</td>
            </tr>`

            $('#lowest_table tbody').append(row)
        })
    }

    function displayAoqItems(data) {
        $("#aoq_items_table tbody").html('')
        console.log(data)
        $.each(data, function(key, val) {

            let total_amount = parseFloat(val.unit_cost) * parseInt(val.quantity)
            let row = `<tr>
                <td><input type='checkbox'value='${val.id}' class='checkBox' style='display:none' name='new_lowest[]'></td>
                <td>${val.payee}</td>
                <td>${val.bac_code}</td>
                <td>${val.description}</td>
                <td>${val.specification}</td>
                <td>${val.remark}</td>
                <td>${val.quantity}</td>
                <td class='amount'>${val.unit_cost}</td>
                <td class='amount'>${total_amount}</td>
            </tr>`

            $("#aoq_items_table tbody").append(row)
        })

    }
</script>