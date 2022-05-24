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
                <?= $form->field($model, 'po_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_pr_aoq_id')->widget(Select2::class, [
                    'data' => $aoq_id,
                    'options' => ['placeholder' => 'Search for a AOQ Number'],
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
            <div class="col-sm-6">
                <?= $form->field($model, 'place_of_delivery')->textInput() ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'delivery_date')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'delivery_term')->textInput() ?>

            </div>
            <div class="col-sm-6">

                <?= $form->field($model, 'payment_term')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

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
        <?php $bac_display = 'display:none;';
        if (!empty($model->bac_date) || !empty($model->fk_bac_composition_id)) {
            $bac_display = '';
        }
        ?>
        <div class="row" id="change_bac" style="<?= $bac_display ?>">
            <div class="col-sm-3">

                <?= $form->field($model, 'bac_date')->widget(DatePicker::class, [

                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ],
                    'pluginOptions' =>
                    [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>

            </div>
            <div class="col-sm-3">


                <?= $form->field($model, 'fk_bac_composition_id')->widget(Select2::class, [

                    'data' => ArrayHelper::map(BacComposition::find()->asArray()->all(), 'id', 'rso_number'),
                    'pluginOptions' => [
                        'placeholder' => 'Select RSO Number'
                    ]
                ]) ?>

            </div>
        </div>
        <div class="table-responsive">

            <table id='lowest_table' class="table table-hover table-striped">
                <thead>
                    <tr class="success">
                        <th colspan="7">
                            <h3>
                                Lowest

                            </h3>
                        </th>

                    </tr>
                    <tr class="success">
                        <th>Payee</th>
                        <th>BAC Code</th>
                        <th>Description</th>
                        <th>Specification</th>
                        <th>Remark</th>
                        <th>Unit Cost</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody></tbody>

            </table>
            <table id="aoq_items_table" class="table table-hover ">

                <thead>
                    <tr>
                        <th colspan="9" class="info">
                            <h3>Abstract Items</h3>
                        </th>
                    </tr>

                    <tr class="info">
                        <th></th>
                        <th>Payee</th>
                        <th>BAC Code</th>
                        <th>Description</th>
                        <th>Specification</th>
                        <th>Remark</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Amount</th>

                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-sm-5"></div>
            <div class="form-group col-sm-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
            </div>
            <div class="col-sm-5"></div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>
</div>

<style>
    .add_new_row {
        display: none;
    }

    td,
    th {
        text-align: center;
    }

    .amount {
        text-align: right;
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

        $('#lowest_table').on('click', '.remove_this_row', function(event) {
            event.stopPropagation();
            event.stopImmediatePropagation();
            $(this).closest('tr').remove();
        });
        $('#aoq_items_table').on('click', '.add_new_row', function(event) {
            event.stopPropagation();
            event.stopImmediatePropagation();
            var source = $(this).closest('tr');
            var clone = source.clone(true);
            clone.attr('class', 'success')
            clone.children('td').find('.add_new_row').remove()
            clone.children('td').find('.quantity').closest('td').remove()
            clone.children('td').find('.quantity').remove()
            clone.children('td').find('.total_amount').closest('td').remove()
            clone.children('td').find('.total_amount').remove()
            clone.children('td').find('.add_new_row').closest('td').remove()
            const aoq_item_id = clone.children('td').find('.rfq_item_id').val()
            clone.children('td').find('.qqq').attr('name', "aoq_id[" + aoq_item_id + "]")

            clone.children('td').eq(0).remove()
            clone.children('.total_amount').remove()
            clone.children('.quantity').remove()
            clone.append("<td><a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a></td>")
            $('#lowest_table').append(clone);
        });

    })

    function changeLowest() {

        if ($('#change_bac').is(":visible") && $("#prpurchaseorder-bac_date").val() == '') {
            $('#change_bac').hide()
            $('#change_lowest').val('Change')
            $('#change_lowest').prop('class', 'btn btn-info')
        } else {
            $('#change_bac').show()
        }
        if ($('.add_new_row').is(":visible")) {
            $('.add_new_row').hide()
            // $(":add_new_row").attr("checked", true);
            // $(":add_new_row").attr("checked", false);
            // $(':add_new_row').each(function() {

            //     this.checked = false;
            // });
        } else {

            $('.add_new_row').show()
        }


        // $('#bac_date').prop('required', true)
        // $('#bac_rso_number').prop('required', true)

    }

    function displayLowest(data) {

        $('#lowest_table tbody').html('')
        $.each(data, function(key, val) {
            const pr_aoq_entries_id = val.aoq_entry_id
            console.log(val.specification)

            let row = `<tr class='success'>
                <td style='display:none;'><input type='hidden' name='aoq_id[${val.rfq_item_id}]' value='${pr_aoq_entries_id}'></td>   
                <td>${val.payee}</td>
                <td>${val.bac_code}</td>

                <td>${val.description}</td>
                <td>${val.specification}</td>
                <td>${val.remark}</td>
                <td class='amount'>${val.unit_cost}</td>
                <td><a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a></td>
            </tr>`

            $('#lowest_table tbody').append(row)
        })
    }



    function displayAoqItems(data) {
        $("#aoq_items_table tbody").html('')
        $.each(data, function(key, val) {

            let total_amount = parseFloat(val.unit_cost) * parseInt(val.quantity)
            let q = ''
            if (parseInt(val.is_lowest) == 1) {
                q = 'checked'
            }
            let row = `<tr class='info'>
                <td><input type='button' class='add_new_row btn-xs btn-warning' value='+'></td>
                <td style='display:none;'>
                <input type='hidden' class='qqq' value='${val.id}' >
                <input type='hidden' class='rfq_item_id' value='${val.rfq_item_id}' >
                </td>
                <td>${val.payee}</td>
                <td>${val.bac_code}</td>
                <td>${val.description}</td>
                <td>${val.specification}</td>
                <td>${val.remark}</td>
                <td class='quantity' ><span class='quantity'>${val.quantity}</span></td>
                <td class='amount unit_cost'>${val.unit_cost}</td>
                <td class='amount total_amount'><span class='total_amount'>${total_amount}</span></td>
            </tr>`

            $("#aoq_items_table tbody").append(row)
        })

    }
</script>