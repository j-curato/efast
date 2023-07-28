<?php

use app\models\Office;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
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
    <span style="font-size: 2rem;color:red;padding-bottom:5rem;font-variant:small-caps">*select the lowest supplier by checking the checkbox.</span>
    <div class="con">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <?php
            if (Yii::$app->user->can('super-user')) {
            ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Office'
                        ]
                    ]) ?>
                </div>
            <?php } ?>
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
            <!-- <div class="col-sm-2">

                <?= $form->field($model, 'pr_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]) ?>
            </div> -->
        </div>




        <table id="rfq_items_table" class="table table-stripe">
            <thead>
                <th>action</th>
                <th>BAC Code</th>
                <th>Stock Title</th>
                <th>Specification</th>
                <th>Unit of Measure</th>
                <th>Quantity</th>
            </thead>
            <tbody>

                <?php
                foreach ($aoq_entries as $key => $items) {
                    $r = 0;
                    echo "<tr class='info'>
                            <td>
                            <button type='button' class='btn-xs btn-primary add' value='+'><i class='fa fa-plus'></i></button>
                            </td>
                            <td style='display:none;'>
                                <input type='hidden' class='form-check-input checkbox rfq_item_id' value='{$key}'
                                data-value = '{$key}'>
                            </td>
                            <td>{$items[0]['bac_code']}</td>
                            <td>{$items[0]['stock_title']}</td>
                            <td>{$items[0]['specification']}</td>
                            <td>{$items[0]['unit_of_measure']}</td>
                            <td>{$items[0]['quantity']}</td>
                        </tr>";
                    foreach ($items as $itm) {
                        $rfq_item_id = $itm['rfq_item_id'];
                        $checked  = $itm['is_lowest'] ? 'checked' : '';
                        echo "<tr>
                        <td style='display:none'>
                            <i class='$rfq_item_id'></i>
                            <input type='text' class='' value='{$itm['item_id']}' name='items[$rfq_item_id][$r][item_id]' >
                        </td>
                        <td> </td>
                        <td style='width:25em;max-width:25em' >
                            <select required name='items[$rfq_item_id][$r][payee_id]' class='payee form-control' style='width: 100%'>
                                <option value='{$itm['payee_id']}'>{$itm['payee']}</option>
                            </select>
                        </td>
                        <td style='width:15em'>
                            <input type='text' class='mask-amount form-control' onkeyup='setUnitCostOnfAmountChangeFunction(this)' value='{$itm['amount']}'>
                            <input type='hidden' name='items[$rfq_item_id][$r][unit_cost]' class='unit_cost' value='{$itm['amount']}'>
                        </td>
                        <td style='width:15em'>
                            <textarea row='2' name='items[$rfq_item_id][$r][remarks]' class='remark'>{$itm['remark']}</textarea>
                        </td>
                        <td>
                            <span> <input class='checkbox ' type='checkbox'  name='items[$rfq_item_id][$r][lowest]'   $checked> </span>
                            <div class='pull-right' style='float:left'>
                                <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' ></i> </a>
                            </div>
                        </td>
                    </tr>";
                        $r++;
                    }
                }
                ?>
            </tbody>

        </table>
        <div class="row">

            <div class="form-group col-sm-2 col-sm-offset-5">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>


</div>
<style>
    .remark {
        max-width: 15em;
    }

    .con {
        background-color: white;
        padding: 2em;
        border: 1px solid black;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>
<script type="text/javascript">
    function setUnitCostOnfAmountChangeFunction(i) {
        var amount = parseFloat(i.value.replace(/,/g, ''))
        var source = $(i).closest('tr');
        source.find('.unit_cost').val(amount)
    }

    $(document).ready(function() {

        payeeSelect()
        maskAmount()
        $('#rfq_items_table').on('click', '.remove_this_row', function() {
            $(this).closest('tr').remove()

        })
        $('#rfq_items_table').on('click', '.add', function() {

            const closest_tr = $(this).closest('tr')
            const rfq_item_id = closest_tr.find('.rfq_item_id').val()
            let row_num = $(`.${rfq_item_id}`).length
            const items = `<tr>
                                <td style='display:none'>
                                    <i class='${rfq_item_id}'></i>
                                </td>
                                <td></td>
                                <td style='width:25em;max-width:25em' >
                                    <select required name="items[${rfq_item_id}][${row_num}][payee_id]" class="payee form-control" style="width: 100%">
                                        <option></option>
                                    </select>
                                </td>
                                <td style='width:15em'>
                                    <input type="text" class="mask-amount form-control" onkeyup='setUnitCostOnfAmountChangeFunction(this)'>
                                    <input type="hidden" name="items[${rfq_item_id}][${row_num}][unit_cost]" class="unit_cost">
                                </td>
                                <td style='width:15em'>
                                    <textarea row='2' name='items[${rfq_item_id}][${row_num}][remarks]' class='remark'></textarea>
                                </td>
                                <td>
                                    <span> <input class='checkbox ' type='checkbox'  name='items[${rfq_item_id}][${row_num}][lowest]'> </span>
                                    <div class='pull-right' style='float:left'>
                                        <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' ></i> </a>
                                    </div>
                                </td>
                            </tr>`

            closest_tr.after(items);
            payeeSelect()
            maskAmount()

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

                    $.each(res, function(key, val) {
                        let r = `<tr class='info'>
                            <td>
                                <button type='button' class='btn-xs btn-primary add' value='+'><i class='fa fa-plus'></i></button>
                                <input type='hidden' class='form-check-input checkbox rfq_item_id' value='${val['rfq_item_id']}'
                                data-value = '${val['rfq_item_id']}'>
                            </td>
                        
                            <td>${val['bac_code']}</td>
                            <td>${val['stock_title']}</td>
                            <td>${val['specification']}</td>
                            <td>${val['unit_of_measure']}</td>
                            <td>${val['quantity']}</td>
                        </tr>`
                        $('#rfq_items_table tbody').append(r)
                    })

                }
            })
        })
    })
</script>