<?php

use app\models\Books;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseRequest */
/* @var $form yii\widgets\ActiveForm */

$requested_by = '';
$approved_by = '';
if (!empty($model->id)) {
    $requested_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->requested_by_id)
        ->queryAll();

    $requested_by = ArrayHelper::map($requested_by_query, 'employee_id', 'employee_name');
    $approved_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->approved_by_id)
        ->queryAll();
    $approved_by = ArrayHelper::map($approved_by_query, 'employee_id', 'employee_name');
}
?>

<div class="pr-purchase-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pr_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'name' => 'date',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ],
        'options' => [
            'readonly' => true,
            'style' => 'background-color:white'
        ]
    ]) ?>

    <?= $form->field($model, 'book_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => 'Select Book'
        ]
    ]) ?>

    <?= $form->field($model, 'pr_project_procurement_id')->textInput() ?>

    <?= $form->field($model, 'purpose')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'requested_by_id')->widget(Select2::class, [
        'data' => $requested_by,
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



    <?= $form->field($model, 'approved_by_id')->widget(Select2::class, [
        'data' => $approved_by,
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
    <table class="table" id="form_fields_data">
        <thead>
            <tr>
                <th>Specification</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>

            <?php

            if (!empty($model->prItem)) {

                foreach ($model->prItem as $i => $val) {
                    echo "<tr>
                    <td>
    
                        <div class='row'>
                            <div class='col-sm-6'>
                                <label for='stocks'>Stock</label>
                                <select required name='pr_stocks_id[]' class='stocks' style='width: 100%'>
                                    <option value= '$val->pr_stock_id'>{$val->stock->stock}</option>
                                </select>
                            </div>
    
    
    
                            <div class='col-sm-4'>
                                <label for='amount'>Unit Cost</label>
                                <input type='text' class='amount form-control' value='" . number_format($val->unit_cost, 2) . "'>
                                <input type='hidden' name='unit_cost[]' class='unit_cost' value='$val->unit_cost'>
                            </div>
    
                            <div class='col-sm-2'>
    
                                <label for='quantity'>Quantity</label>
                                <input type='number' name='quantity[]' class='form-control quantity' value='$val->quantity'>
                            </div>
                            <table class='table'>
                                <thead>
                                    <th>Description</th>
                                    <th>Office</th>
                                </thead>
                            </table>
    
                        </div>
    
                    </td>
                    <td style='  text-align: center;'>
                        <div class='pull-left'>
                            <button class='add_new_row btn btn-success btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                            <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>
                </tr>";
                }
            } else {
            ?>
                <tr>
                    <td>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="stocks">Stock</label>
                                <select required name="pr_stocks_id[]" class="stocks" style="width: 100%">
                                    <option></option>
                                </select>
                            </div>



                            <div class="col-sm-4">
                                <label for="amount">Unit Cost</label>
                                <input type="text" class="amount form-control">
                                <input type="hidden" name="unit_cost[]" class="unit_cost">
                            </div>

                            <div class="col-sm-2">

                                <label for="quantity">Quantity</label>
                                <input type="number" name='quantity[]' class="form-control quantity">
                                <input type="text" class="x">

                            </div>

                        </div>
                        <table class="table specs-table">
                            <thead>
                                <th>Specification</th>
                                <th>Office</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" class="specs">
                                    </td>
                                    <td style='  text-align: center;'>
                                        <div class='pull-left'>
                                            <button class='specs_add_new_row btn btn-success btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                            <a class='specs_remove_this_row btn btn-danger btn-xs disabled'><i class='fa fa-times fa-fw'></i> </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                    <td style='  text-align: center;'>
                        <div class='pull-left'>
                            <button class='add_new_row btn btn-success btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                            <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <button type="button" id="bt"> Click</button>

</div>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script type="text/javascript">
    function stockSelect() {
        $('.stocks').select2({
            ajax: {
                url: window.location.pathname + '?r=pr-stock/search-stock',
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                },
            },

        });
    }

    function maskAmount() {

        $('.amount').maskMoney({
            allowNegative: true
        });


    }


    $(document).ready(function() {

        $('.amount').on('change keyup', function(e) {
            e.preventDefault()
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('tr');
            source.children('td').eq(0).find('.unit_cost').val(amount)

        })
        $('.stocks').on('change', function(e) {
            var source = $(this).closest('tr');
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=pr-stock/stock-info',
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    console.log(data)
                    var res = JSON.parse(data)
                    source.children('td').eq(0).find('.amount').val(res.amount).trigger('change')


                }
            })


        })
        stockSelect()
        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('.add_new_row').on('click', function(event) {
            event.preventDefault();
            $('.stocks').select2('destroy');
            $('.unit_cost').maskMoney('destroy');
            var source = $(this).closest('tr');
            var clone = source.clone(true);

            clone.children('td').eq(0).find('.quantity').val(0)
            clone.children('td').eq(0).find('.stocks').val('')
            clone.children('td').eq(0).find('.unit_cost').val(0)
            clone.children('td').eq(0).find('.amount').val(0)


            // clone.children('td').eq(0).find('.specification').val('')
            $('#form_fields_data').append(clone);
            clone.find('.remove_this_row').removeClass('disabled');
            stockSelect()
            maskAmount()
            clone.children('td').eq(0).find('.unit_cost').maskMoney('mask', 1111);


        });
        $('.specs_remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('.specs_add_new_row').on('click', function(event) {
            event.preventDefault();
            var source = $(this).closest('tr');
            var clone = source.clone(true);

            // clone.children('td').eq(0).find('.specification').val('')
            $(this).closest('.specs-table').append(clone);
            clone.find('.specs_remove_this_row').removeClass('disabled');
        });

        $('.specs').on('change', function(event) {
            event.preventDefault();
            var source = $(this).closest('.form_fields_data > tr ');



            var new_val = source.children('td').eq(0).find('.unit_cost').val() + '|' + $(this).val()
            console.log(new_val)
        });



    });

    $('#bt').on('click', function() {


        $.each($('.stocks'), function(key, val) {
            var source = $(this).closest('.specs-table > tbody > tr .specs');
            source.each(function(index, tr) {
                console.log(index);
                console.log(tr);
            });

        })

    })
</script>