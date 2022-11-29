<?php

use app\models\Books;
use aryelds\sweetalert\SweetAlert;
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

echo $error;
if (!empty($error)) {
    echo SweetAlert::widget([
        'options' => [
            'title' => "Error",
            'text' => "$error",
            'type' => "error"
        ]
    ]);
}
$requested_by = '';
$approved_by = '';
$pr_project = '';
if (!empty($model->id)) {
    $requested_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->requested_by_id)
        ->queryAll();
    $requested_by = ArrayHelper::map($requested_by_query, 'employee_id', 'employee_name');
    $approved_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->approved_by_id)
        ->queryAll();
    $approved_by = ArrayHelper::map($approved_by_query, 'employee_id', 'employee_name');
    $pr_project_query   = Yii::$app->db->createCommand("SELECT id,title   FROM pr_project_procurement WHERE id = :id")
        ->bindValue(':id', $model->pr_project_procurement_id)
        ->queryAll();
    $pr_project = ArrayHelper::map($pr_project_query, 'id', 'title');
}
?>

<div class="pr-purchase-request-form" style="background-color: #e6f3ff;padding:3rem">

    <div class="panel-group">

        <div class="panel panel-primary">



            <div class="panel-body">

                <?php $form = ActiveForm::begin(); ?>
                <div class="row">


                    <div class="col-sm-6">
                        <?= $form->field($model, 'pr_project_procurement_id')->widget(Select2::class, [
                            'data' => $pr_project,
                            'options' => ['placeholder' => 'Search for a Activity/Project ...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => Yii::$app->request->baseUrl . '?r=pr-project-procurement/search-project',
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


                    <?php

                    if (empty($model->date)) {
                        echo "  <div class='col-sm-4'>";

                        echo $form->field($model, 'date')->widget(DatePicker::class, [
                            'name' => 'date',
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ],
                            'options' => [
                                'readonly' => true,
                                'style' => 'background-color:white'
                            ]
                        ]);
                        echo "</div>";
                    } ?>

                    <div class="col-sm-2">
                        <?= $form->field($model, 'book_id')->widget(Select2::class, [
                            'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                            'pluginOptions' => [
                                'placeholder' => 'Select Book'
                            ]
                        ]) ?>
                    </div>
                </div>






                <?= $form->field($model, 'purpose')->textarea(['rows' => 4]) ?>

                <div class="row">
                    <div class="col-sm-6">
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
                    </div>
                    <div class="col-sm-6">
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
                    </div>
                </div>

                <hr style="  position: relative;
                            top: 10px;
                            border: none;
                            height: 2px;
                            background: black;
                            margin-bottom: 20px;">



                <table class="table" id="form_fields_data">
                    <thead>
                        <tr>
                            <th colspan="2" style="text-align: center;">
                                <h3>Specification</h3>
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $row_num = 1;
                        if (!empty($model->prItem)) {
                            foreach ($model->prItem as $i => $val) {
                                $specification  = preg_replace('#\[n\]#', "\n", $val->specification);
                                echo "<tr class='panel panel-default spacer'>
                                        <td>

                                            <input type='hidden' name='pr_item_id[$i]'  class = 'pr_item_id' value='{$val->id}'>
                                            <div class='row' >
                                                <div class='col-sm-4'>
                                                    <label for='stocks'>Stock</label>
                                                    <select required name='pr_stocks_id[$i]' class='stocks' style='width: 100%'>
                                                    <option value= '$val->pr_stock_id'>{$val->stock->stock_title}</option>
                                                    </select>
                                                </div>
                                                <div class='col-sm-3'>
                                                <label for='unit_of_measure'>Unit of Measure</label>
                                                <select required name='unit_of_measure_id[$i]' class='unit_of_measure form-control' style='width: 100%'>
                                                <option value= '$val->unit_of_measure_id'>{$val->unitOfMeasure->unit_of_measure}</option>
                                                <option></option>
                                                </select>
                                            </div>
    
                                                <div class='col-sm-3'>
                                                    <label for='amount'>Unit Cost</label>
                                                    <input type='text' class='amount form-control' value='" . number_format($val->unit_cost, 2) . "'>
                                                    <input type='hidden' name='unit_cost[$i]' class='unit_cost' value='$val->unit_cost'>
                                                </div>

                                                <div class='col-sm-2'>

                                                    <label for='quantity'>Quantity</label>
                                                    <input type='number' name='quantity[$i]' class='form-control quantity' value='$val->quantity'>
                                                </div>

                                            </div>
                                            <div class='row'>
                                                <div class='col-sm-12'>
                                                    <label for='specs'>Specification</label>
                                                    <textarea rows='2' class='specs_view' id='q'>$specification</textarea>
                                       
                                                    <textarea name='specification[$i]' class='specs' type='hidden' style='display:none'  >$val->specification</textarea>
                                                </div>
                                            </div>


                                        </td>
                                        <td style='  text-align: center;'>
                                            <div class='pull-left'>
                                                <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                                <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                            </div>
                                        </td>
                                    </tr>";
                                echo " <tr>
                                            <td colspan='2'>
                                            <hr>
                                            </td>
                                        </tr>";
                                $row_num++;
                            }
                        } else {
                        ?>
                            <tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                                <td style="max-width:100rem;">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="stocks">Stock</label>
                                            <select required name="pr_stocks_id[0]" class="stocks form-control" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="unit_of_measure">Unit of Measure</label>
                                            <select required name="unit_of_measure_id[0]" class="unit_of_measure form-control" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </div>




                                        <div class="col-sm-3">
                                            <label for="amount">Unit Cost</label>
                                            <input type="text" class="amount form-control">
                                            <input type="hidden" name="unit_cost[0]" class="unit_cost">
                                        </div>

                                        <div class="col-sm-2">

                                            <label for="quantity">Quantity</label>
                                            <input type="number" name='quantity[0]' class="form-control quantity">

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="specs_view">Specification</label>
                                            <textarea rows="2" class="specs_view form-control" id="q"></textarea>
                                            <input name="specification[0]" rows="2" class="specs" type='hidden'>
                                            <textarea name='specification[0]' class='specs' type='hidden' style='display:none'></textarea>
                                        </div>
                                    </div>
                                </td>
                                <td style='  text-align: center;'>
                                    <div class='pull-right'>
                                        <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                        <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                    </div>
                                </td>


                            </tr>
                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>


                        <?php } ?>

                    </tbody>
                </table>



                <div class="form-group" style="text-align: center;">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:15em;font-size:15px']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>


</div>
<style>
    textarea {
        width: 100%;
        max-width: 100%;
    }

    .panel {
        padding: 3rem;
    }
</style>
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

    function unitOfMeasureSelect() {
        $('.unit_of_measure').select2({
            data: unit_of_measure,
            placeholder: "Select Unit of Measure",

        })

    }

    function maskAmount() {

        $('.amount').maskMoney({
            allowNegative: true
        });


    }

    var unit_of_measure = []
    $(document).ready(function() {
        var x = <?= $row_num ?>;
        maskAmount()
        $.getJSON(window.location.pathname + '/frontend/web/index.php?r=unit-of-measure/get-all-measure')
            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.unit_of_measure
                    })
                })
                unit_of_measure = array
                unitOfMeasureSelect()

            });


        // function addNewLine() {
        //     var text = document.getElementById('q').value;
        //     text = text.replace(/\n/g, "[n]");
        //     console.log(text)
        // }
        $('.specs_view').on('change', function(e) {
            e.preventDefault()
            var specs = $(this).val()
            var main_specs = $(this).closest('tr');
            specs = specs.replace(/\n/g, "[n]");
            specs = specs.replace(/"/g, '\'');
            main_specs.children('td').eq(0).find('.specs').val(specs)
        })

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
                    source.children('td').eq(0).find('.unit_of_measure').val(res.unit_of_measure_id).trigger('change')
                }
            })


        })
        stockSelect()
        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').next().remove();
            $(this).closest('tr').remove();
        });
        $('.add_new_row').on('click', function(event) {
            event.preventDefault();
            $('.stocks').select2('destroy');
            $('.unit_of_measure').select2('destroy');
            $('.unit_cost').maskMoney('destroy');
            var source = $(this).closest('tr');
            var clone = source.clone(true);
            clone.children('td').eq(0).find('.desc').text('')
            clone.children('td').eq(0).find('.quantity').val(0)
            clone.children('td').eq(0).find('.quantity').attr('name', 'quantity[' + x + ']')
            clone.children('td').eq(0).find('.unit_of_measure').val('')
            clone.children('td').eq(0).find('.unit_of_measure').attr('name', 'unit_of_measure_id[' + x + ']')
            clone.children('td').eq(0).find('.pr_item_id').val('')
            clone.children('td').eq(0).find('.pr_item_id').attr('name', 'pr_item_id[' + x + ']')
            clone.children('td').eq(0).find('.stocks').val('')
            clone.children('td').eq(0).find('.stocks').attr('name', 'pr_stocks_id[' + x + ']')
            clone.children('td').eq(0).find('.unit_cost').val(0)
            clone.children('td').eq(0).find('.unit_cost').attr('name', 'unit_cost[' + x + ']')
            clone.children('td').eq(0).find('.amount').val(0)
            clone.children('td').eq(0).find('.specs').val(null)
            clone.children('td').eq(0).find('.specs_view').val(null)
            clone.children('td').eq(0).find('.specs').attr('name', 'specification[' + x + ']');

            // clone.children('td').eq(0).find('.specification').val('')
            $('#form_fields_data').append(clone);
            var spacer = `<tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>`
            $('#form_fields_data').append(spacer);
            clone.find('.remove_this_row').removeClass('disabled');
            stockSelect()
            maskAmount()
            unitOfMeasureSelect()
            x++


        });
        $('.specs_remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('.specs_add_new_row').on('click', function(event) {
            event.preventDefault();
            var source = $(this).closest('tr');
            var clone = source.clone(true);

            $(this).closest('.specs-table').append(clone);
            clone.find('.specs_remove_this_row').removeClass('disabled');
        });

        $('.specs').on('change', function(event) {
            event.preventDefault();
            var source = $(this).closest('.form_fields_data > tr ');
            var new_val = source.children('td').eq(0).find('.unit_cost').val() + '|' + $(this).val()
            console.log(new_val)
        });

        // $('.stocks').val(1).trigger('change')




    });
</script>