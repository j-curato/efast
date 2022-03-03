<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */
/* @var $form yii\widgets\ActiveForm */


$pr_rfq = '';

if (!empty($model->id)) {
    $pr_rfq_query   = Yii::$app->db->createCommand("SELECT id,rfq_number   FROM pr_rfq WHERE id = :id")
        ->bindValue(':id', $model->pr_rfq_id)
        ->queryAll();
    $pr_rfq = ArrayHelper::map($pr_rfq_query, 'id', 'rfq_number');
}
$row = 1;
?>

<div class="pr-aoq-form">
    <div class="con">

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-sm-2">
                <?= $form->field($model, 'pr_rfq_id')->widget(
                    Select2::class,

                    [
                        'data' => $pr_rfq,
                        'options' => ['placeholder' => 'Search for a Purchase Request'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=pr-rfq/search-rfq',
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],
                    ]
                ) ?>
            </div>
            <div class="col-sm-2">

                <?= $form->field($model, 'pr_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]) ?>
            </div>
        </div>



        <table id="transaction_table" class="table table-striped">
            <thead>
                <th>BAC Code</th>
                <th>Stock Title</th>
                <th>Specification</th>
                <th>Unit of Measure</th>
                <th>Quantity</th>
                <th>Payee</th>
                <th>Unit Cost</th>
                <th>Remark</th>
            </thead>
            <tbody>
                <?php

                if (!empty($aoq_entries)) {
                    foreach ($aoq_entries as $i => $val) {
                        echo "
                      <tr>
                            <td style='display:none'>
                                <input type='hidden' class='form-check-input aoq_item_id ' value='{$val['aoq_item_id']}'
                                name='pr_aoq_item[$row]' >
                            </td>
                            <td style='display:none'>
                                <input type='hidden' class='form-check-input pr_rfq_item' value='{$val['rfq_item_id']}'
                                name='pr_rfq_item[$row]' >
                            </td>
                            <td>
                                {$val['bac_code']}
                            </td>

                            <td> {$val['stock_title']}</td>
                            <td> {$val['specification']}</td>
                            <td> {$val['unit_of_measure']}</td>
                            <td> {$val['quantity']}</td>
             
                            <td style='width:25em'>
                                <select required name='payee_id[$row]' class='payee form-control payee' style='width: 100%'>
                                    <option value='{$val['payee_id']}'>{$val['payee']}</option>
                                </select>
                            </td>
                            <td style='width:15em'>
                                <input type='text' class='amount form-control' onkeyup='setUnitCostOnfAmountChangeFunction(this)' value='{$val['amount']}'>
                                <input type='hidden' name='unit_cost[$row]' class='unit_cost' value='{$val['amount']}'>
                            </td>
                            <td style='width:15em'>
                                <textarea row='2' name='remarks[$row]' class='remark'>{$val['remark']}</textarea>
                            </td>
                            <td style='text-align: center;width:80px'>
                                <div class='pull-right'>
                                    <a class='copy_row btn btn-primary btn-xs'><i class='fa fa-copy fa-fw'></i> </a>
                                    <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' onClick='remove(this)'></i> </a>
                                </div>
                            </td>
                        </tr>
                    ";
                        $row++;
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <table id="rfq_items_table" class="table table-striped">
            <thead>
                <th>BAC Code</th>
                <th>Stock Title</th>
                <th>Specification</th>
                <th>Unit of Measure</th>
                <th>Quantity</th>
            </thead>
            <tbody>

            </tbody>

        </table>

        <button class="btn btn-primary" id="add" type="button">add</button>
    </div>


</div>
<style>
    .remark {
        max-width: 15em;
    }

    .con {
        background-color: white;
        padding: 2em;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script type="text/javascript">
    function remove(i) {
        i.closest('tr').remove();
    }

    function payeeSelect() {
        $('.payee').select2({
            ajax: {
                url: window.location.pathname + '?r=payee/search-payee',
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

    function setUnitCostOnfAmountChangeFunction(i) {
        var amount = parseFloat(i.value.replace(/,/g, ''))
        var source = $(i).closest('tr');
        source.find('.unit_cost').val(amount)
    }

    $('.add').click(function() {
        console.log('qwer')
    })
    $(document).ready(function() {
        payeeSelect()
        maskAmount()
        let transaction_row = <?= $row ?>;
        $('.add_new_row').click(function() {
            console.log('q')
        })
        $('.amount').change(function() {
            console.log('qqq')
        })

        $('#add').click(function() {
            $('.checkbox:checked').each(function() {
                checkValue = $(this).closest('tr')
                const clone = checkValue.clone();
                clone.children('td').eq(0).find('.checkbox').prop('type', 'text');
                clone.children('td').eq(0).find('.checkbox').prop('type', 'hidden');
                clone.children('td').eq(0).hide();
                clone.children('td').eq(0).find('.checkbox').prop('name', 'pr_rfq_item[' + transaction_row + ']');
                const buttons = `<td style='  text-align: center;'>
                                    <div class='pull-right'>
                                        <a class='copy_row btn btn-primary btn-xs' type='button'><i class='fa fa-copy fa-fw'></i> </a>
                                        <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' onClick='remove(this)'></i> </a>
                                    </div>
                                </td>`;
                const payee_select = `<td style='width:25em;max-width:25em'>
                        <select required name="payee_id[${transaction_row}]" class="payee form-control" style="width: 100%">
                            <option></option>
                        </select>
                </td>`;
                const amount = `<td style='width:15em'>
                    <input type="text" class="amount form-control" onkeyup='setUnitCostOnfAmountChangeFunction(this)'>
                    <input type="hidden" name="unit_cost[${transaction_row}]" class="unit_cost">
                </td>`;
                const remark = `<td style='width:15em'>
                   <textarea row='2' name='remarks[${transaction_row}]' class='remark'></textarea>
                </td>`;
                clone.append(payee_select)
                clone.append(amount)
                clone.append(remark)
                clone.append(buttons)

                $('#transaction_table tbody').append(clone);
                payeeSelect()
                maskAmount()
                transaction_row++
            })
        })
        $('#praoq-pr_rfq_id').on('change', function() {
            $("#rfq_items_table tbody").html('')
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=pr-aoq/get-rfq-info',
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res)
                    for (var i = 0; i < res.length; i++) {
                        console.log(i)
                        var row = `<tr>
                            <td>
                                <input type='checkbox' class='form-check-input checkbox' value='${res[i]['rfq_item_id']}'
                                name='pr_rfq_item[]' data-value = '${res[i]['rfq_item_id']}'>
                            </td>

                            <td>${res[i]['bac_code']}</td>
                            <td>${res[i]['stock_title']}</td>
                            <td>${res[i]['specification']}</td>
                            <td>${res[i]['unit_of_measure']}</td>
                            <td>${res[i]['quantity']}</td>
                        </tr>`

                        $('#rfq_items_table tbody').append(row)
                    }
                }
            })
        })

        $(document).ready(function(e) {
            if ($("#praoq-pr_rfq_id").val() != '') {

                $("#praoq-pr_rfq_id").trigger('change')

            }
        })
        // $('#praoq-pr_rfq_id').trigger('change')
        $('.copy_row').on('click', function(event) {
            $('.payee').select2('destroy');
            $('.amount').maskMoney('destroy');
            var source = $(this).closest('tr');
            var clone = source.clone(true);
            // clone.children('td').eq(0).find('.desc').text('')
            clone.children('td').find('.aoq_item_id').val('')
            clone.children('td').find('.aoq_item_id').attr('name', 'pr_aoq_item[' + transaction_row + ']')
            clone.children('td').find('.remark').attr('name', 'remarks[' + transaction_row + ']')
            clone.children('td').find('.pr_rfq_item').attr('name', 'pr_rfq_item[' + transaction_row + ']')
            clone.children('td').find('.payee').attr('name', 'payee_id[' + transaction_row + ']')
            clone.children('td').find('.unit_cost').attr('name', 'unit_cost[' + transaction_row + ']')

            $('#transaction_table tbody').append(clone);
            payeeSelect()
            maskAmount()
            transaction_row++

        });
        $('.add').on('click', function(event) {
            console.log(qwer)

        });
    })
</script>