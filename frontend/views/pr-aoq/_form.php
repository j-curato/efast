<?php

use yii\helpers\Html;
use app\models\Office;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2Asset;

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

<div class="pr-aoq-form" id="main">
    <span style="font-size: 2rem;color:red;padding-bottom:5rem;font-variant:small-caps">*select the lowest supplier by checking the checkbox.</span>
    <div class="con">
        <?php $form = ActiveForm::begin([
            'id' => $model->formName()
        ]); ?>
        <div class="row">
            <?php
            if (Yii::$app->user->can('ro_procurement_admin')) {
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
            <?php if ($model->isNewRecord) : ?>
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
            <?php endif; ?>
        </div>




        <table class="table table-stripe">
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
                // foreach ($aoq_entries as $key => $items) {
                //     $r = 0;
                //     echo "<tr class='info'>
                //             <td>
                //             <button type='button' class='btn-xs btn-primary add' value='+'><i class='fa fa-plus'></i></button>
                //             </td>
                //             <td style='display:none;'>
                //                 <input type='hidden' class='form-check-input checkbox rfq_item_id' value='{$key}'
                //                 data-value = '{$key}'>
                //             </td>
                //             <td>{$items[0]['bac_code']}</td>
                //             <td>{$items[0]['stock_title']}</td>
                //             <td>{$items[0]['specification']}</td>
                //             <td>{$items[0]['unit_of_measure']}</td>
                //             <td>{$items[0]['quantity']}</td>
                //         </tr>";
                //     foreach ($items as $itm) {
                //         $rfq_item_id = $itm['rfq_item_id'];
                //         $checked  = $itm['is_lowest'] ? 'checked' : '';
                //         echo "<tr>
                //         <td style='display:none'>
                //             <i class='$rfq_item_id'></i>
                //             <input type='text' class='' value='{$itm['item_id']}' name='items[$rfq_item_id][$r][item_id]' >
                //         </td>
                //         <td> </td>
                //         <td style='width:25em;max-width:25em' >
                //             <select required name='items[$rfq_item_id][$r][payee_id]' class='payee form-control' style='width: 100%'>
                //                 <option value='{$itm['payee_id']}'>{$itm['payee']}</option>
                //             </select>
                //         </td>
                //         <td style='width:15em'>
                //             <input type='text' class='mask-amount form-control' onkeyup='setUnitCostOnfAmountChangeFunction(this)' value='{$itm['amount']}'>
                //             <input type='hidden' name='items[$rfq_item_id][$r][unit_cost]' class='unit_cost' value='{$itm['amount']}'>
                //         </td>
                //         <td style='width:15em'>
                //             <textarea row='2' name='items[$rfq_item_id][$r][remarks]' class='remark'>{$itm['remark']}</textarea>
                //         </td>
                //         <td>
                //             <div class='row'>
                //                 <div class='col-sm-1'>
                //                     <span> <input class='checkbox form-control' type='checkbox'  name='items[$rfq_item_id][$r][lowest]'   $checked> </span>
                //                 </div>
                //                 <div class='col-sm-1'>
                //                     <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' ></i> </a>
                //                 </div>
                //             </div>
                //         </td>
                //     </tr>";
                //         $r++;
                //     }
                // }
                ?>
                <template v-for="rfqItem in rfqItems">
                    <tr :key='rfqItem.rfq_item_id'>
                        <td> <button type='button' class='btn-xs btn-primary' @click='addPayee(rfqItem)'><i class='fa fa-plus'></i></button></td>
                        <td>{{rfqItem.bac_code}}</td>
                        <td>{{rfqItem.stock_title}}</td>
                        <td>{{formatSpecification(rfqItem.specification)}}</td>
                        <td>{{rfqItem.unit_of_measure}}</td>
                        <td>{{rfqItem.quantity}}</td>
                    </tr>
                    <tr v-for="(bidder,bidderIndex) in rfqItem.bidders" :key="rfqItem.rfq_item_id+'-'+bidderIndex">

                        <td>
                            <input type='hidden' class='form-control' :value='rfqItem.rfq_item_id' :name="'items['+rfqItem.rfq_item_id+']['+bidderIndex+'][pr_rfq_item_id]'">
                            <input type='hidden' class='form-control' :value='bidder.id' :name="'items['+rfqItem.rfq_item_id+']['+bidderIndex+'][id]'">
                        </td>
                        <td style='width:25em;max-width:25em'>

                            <label for="payee"> Payee </label>
                            <select required :name="'items['+rfqItem.rfq_item_id+']['+bidderIndex+'][payee_id]'" class='payee form-control' style='width: 100%'>
                                <option v-if="bidder.payeeName" :value='bidder.payeeId'>{{bidder.payeeName}}</option>
                            </select>
                        </td>
                        <td style='width:15em'>
                            <label for="payee"> Bid Amount </label>
                            <input type="text" class="mask-amount form-control" v-model='bidder.maskedAmount' @keyup="changeMainAmount($event,bidder,bidderIndex)" />
                            <input type="hidden" :name="'items['+rfqItem.rfq_item_id+']['+bidderIndex+'][amount]'" class="main-amount" :value="bidder.unitCost">
                        </td>
                        <td style='width:15em'>
                            <label for="payee"> Remarks </label>
                            <textarea row='2' :name="'items['+rfqItem.rfq_item_id+']['+bidderIndex+'][remark]'" class='remark form-control' v-model='bidder.remark'></textarea>
                        </td>
                        <td class="text-center">
                            <label for="checkbox"> Lowest Bidder </label>
                            <br>
                            <input class='checkbox ' type='checkbox' :name="'items['+rfqItem.rfq_item_id+']['+bidderIndex+'][is_lowest]'" v-model='bidder.isLowest'>
                        </td>
                        <td>
                            <a class='remove_this_row btn btn-danger btn-xs ' @click='removeItem(bidderIndex,rfqItem)' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </td>
                    </tr>

                </template>
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
Select2Asset::register($this);
?>
<script type="text/javascript">
    $(document).ready(function() {

        new Vue({
            el: '#main',
            data: {
                csrfToken: '<?= Yii::$app->request->csrfToken ?>',
                rfqItems: <?= !empty($items) ? json_encode($items) : json_encode([]) ?>,
            },
            mounted() {
                $('#praoq-pr_rfq_id').on('change',
                    this.getRfqItems
                )

            },
            methods: {

                changeMainAmount(event, item, index) {
                    // item.unit_cost = $(event.target).maskMoney('unmasked')[0]
                    this.$set(item, 'unitCost', $(event.target).maskMoney('unmasked')[0]);
                    this.$set(item, 'maskedAmount', $(event.target).val());

                },
                getRfqItems() {
                    const url = window.location.pathname + '?r=pr-aoq/get-rfq-info'
                    const data = {
                        id: $('#praoq-pr_rfq_id').val(),
                        _csrf: this.csrfToken
                    }
                    axios.post(url, data)
                        .then(response => {
                            this.rfqItems = response.data
                            console.log(this.rfqItems)
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                },
                addPayee(item) {
                    if (item.bidders) {
                        item.bidders.push({
                            payeeId: '',
                            unitCost: '',
                            remark: ''
                        })
                    } else {
                        this.$set(item, 'bidders', [{
                            payeeId: '',
                            unitCost: '',
                            remark: ''
                        }]);
                    }

                },
                removeItem(index, rfqItem) {
                    rfqItem.bidders.splice(index, 1)
                },
                formatSpecification(specs) {
                    return specs.replace(/<br>/g, ' ')
                }
            },
            updated() {
                payeeSelect()
                maskAmount()
            },
        })
        payeeSelect()
        maskAmount()

        // maskAmount()
        // $('#rfq_items_table').on('click', '.remove_this_row', function() {
        //     $(this).closest('tr').remove()

        // })
        // $('#rfq_items_table').on('click', '.add', function() {

        //     const closest_tr = $(this).closest('tr')
        //     const rfq_item_id = closest_tr.find('.rfq_item_id').val()
        //     let row_num = $(`.${rfq_item_id}`).length
        //     const items = `<tr>
        //                         <td style='display:none'>
        //                             <i class='${rfq_item_id}'></i>
        //                         </td>
        //                         <td></td>
        //                         <td style='width:25em;max-width:25em' >
        //                             <select required name="items[${rfq_item_id}][${row_num}][payee_id]" class="payee form-control" style="width: 100%">
        //                                 <option></option>
        //                             </select>
        //                         </td>
        //                         <td style='width:15em'>
        //                             <input type="text" class="mask-amount form-control" onkeyup='setUnitCostOnfAmountChangeFunction(this)'>
        //                             <input type="hidden" name="items[${rfq_item_id}][${row_num}][unit_cost]" class="unit_cost">
        //                         </td>
        //                         <td style='width:15em'>
        //                             <textarea row='2' name='items[${rfq_item_id}][${row_num}][remarks]' class='remark'></textarea>
        //                         </td>
        //                         <td>

        //                             <div class='row'>
        //                                 <div class='col-sm-1'>
        //                                     <input class='checkbox ' type='checkbox'  name='items[${rfq_item_id}][${row_num}][lowest]'>
        //                                 </div>
        //                                 <div class='col-sm-1'>
        //                                     <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' ></i> </a>
        //                                 </div>
        //                             </div>
        //                         </td>
        //                     </tr>`

        //     closest_tr.after(items);
        //     payeeSelect()
        //     maskAmount()

        // })
        // $('#praoq-pr_rfq_id').on('change', function() {
        //     $("#rfq_items_table tbody").html('')
        //     $.ajax({
        //         type: 'POST',
        //         url: window.location.pathname + '?r=pr-aoq/get-rfq-info',
        //         data: {
        //             id: $(this).val()
        //         },
        //         success: function(data) {
        //             var res = JSON.parse(data)

        //             $.each(res, function(key, val) {
        //                 let r = `<tr class='info'>
        //                     <td>
        //                         <button type='button' class='btn-xs btn-primary add' value='+'><i class='fa fa-plus'></i></button>
        //                         <input type='hidden' class='form-check-input checkbox rfq_item_id' value='${val['rfq_item_id']}'
        //                         data-value = '${val['rfq_item_id']}'>
        //                     </td>

        //                     <td>${val['bac_code']}</td>
        //                     <td>${val['stock_title']}</td>
        //                     <td>${val['specification']}</td>
        //                     <td>${val['unit_of_measure']}</td>
        //                     <td>${val['quantity']}</td>
        //                 </tr>`
        //                 $('#rfq_items_table tbody').append(r)
        //             })

        //         }
        //     })
        // })
    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
    $("#PrAoq").on("beforeSubmit", function (event) {
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