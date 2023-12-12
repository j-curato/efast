<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Office;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\FmiLguLiquidations */
/* @var $form yii\widgets\ActiveForm */

$subprojectData = [
    [
        'id' => $model->fk_fmi_subproject_id ?? null,
        'serial_number' => $model->fmiSubproject->serial_number ?? null,
    ]
]
?>

<div class="fmi-lgu-liquidations-form" id="mainVue">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="card p-3">

        <div class="row">

            <div class="col-3">
                <?= $form->field($model, 'fk_fmi_subproject_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($subprojectData, 'id', 'serial_number'),
                    'options' => ['placeholder' => 'Search for a Bank ...', 'style' => 'height:30em'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['fmi-subprojects/search-subproject']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {text:params.term,page:params.page}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-3">

                <?= $form->field($model, 'fk_office_id')->dropDownList(ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'), ['prompt' => 'Select Office']) ?>
            </div>
            <div class="col-3">

                <?= $form->field($model, 'reporting_period')->widget(
                    DatePicker::class,
                    [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'minViewMode' => 'months'
                        ]
                    ]
                ) ?>
            </div>


        </div>

        <table class="table">
            <thead>
                <tr>

                    <th>Reporting Period</th>
                    <th>Date</th>
                    <th>Check No.</th>
                    <th>Payee</th>
                    <th>Particular</th>
                    <th>Grant Amount</th>
                    <th>Equity Amount</th>
                    <th>Other Funds</th>
                    <th><button type="button" class="btn-xs btn-success" @click="addItem"><i class="fa fa-plus"></i> Add</button></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in items" :key="index">
                    <td>
                        <?php if (!$model->isNewRecord) : ?>
                            <span v-if="item.id">{{item.formatted_period}}</span>
                            <input required type='month' v-else :name="'items['+index+'][reporting_period]'" class="form-control" v-model="item.reporting_period">
                        <?php endif; ?>
                    </td>

                    <td>
                        <span v-if="item.id">{{item.date}}</span>
                        <input v-else required :name="'items['+index+'][date]'" class="form-control" type='date' v-model="item.date">
                    </td>
                    <td>
                        <span v-if="item.id">{{item.check_number}}</span>
                        <input v-else required :name="'items['+index+'][check_number]'" class="form-control" type='text' v-model="item.check_number">
                    </td>
                    <td>
                        <span v-if="item.id">{{item.payee}}</span>
                        <textarea v-else required :name="'items['+index+'][payee]'" class="form-control" type='text' rows="1" v-model="item.payee"></textarea>
                    </td>
                    <td>
                        <span v-if="item.id">{{item.particular}}</span>
                        <textarea v-else required :name="'items['+index+'][particular]'" class="form-control" rows="1" v-model="item.particular"></textarea>
                    </td>
                    <td>
                        <span v-if="item.id">{{formatAmount(item.grant_amount)}}</span>
                        <div v-else>

                            <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value="formatAmount(item.grant_amount)" @keyup="changeMainAmount($event,item,index,'grant_amount')" />
                            <input type="hidden" :name="'items['+index+'][grant_amount]'" class="main-amount" v-model="item.grant_amount">
                        </div>
                    </td>
                    <td>
                        <span v-if="item.id">{{formatAmount(item.equity_amount)}}</span>
                        <div v-else>

                            <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value="formatAmount(item.equity_amount)" @keyup="changeMainAmount($event,item,index,'equity_amount')" />
                            <input type="hidden" :name="'items['+index+'][equity_amount]'" class="main-amount" v-model="item.equity_amount">
                        </div>
                    </td>
                    <td>
                        <span v-if="item.id">{{formatAmount(item.other_fund_amount)}}</span>
                        <div v-else>

                            <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value="formatAmount(item.other_fund_amount)" @keyup="changeMainAmount($event,item,index,'other_fund_amount')" />
                            <input type="hidden" :name="'items['+index+'][other_fund_amount]'" class="main-amount" v-model="item.other_fund_amount">
                        </div>
                    </td>
                    <td>
                        <button v-if="!item.id" type="button" class="btn-xs btn-danger" @click="removeItem(index)"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row justify-content-center">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

?>
<script>
    $(document).ready(function() {
        new Vue({

            el: '#mainVue',
            data: {
                items: <?= !empty($items) ? json_encode($items) : json_encode([]) ?>,
                moneyConfig: {
                    precision: 2, // Number of decimal places
                    prefix: '₱ ', // Currency symbol
                    thousands: ',', // Thousands separator
                    decimal: '.', // Decimal separator,
                },
            },
            methods: {

                addItem() {
                    this.items.push({
                        'reporting_period': '',
                        'date': '',
                        'check_number': '',
                        'payee': '',
                        'particular': '',
                        'grant_amount': '',
                        'equity_amount': '',
                        'other_fund_amount': '',

                    });
                },
                changeMainAmount(event, item, index, itemName) {
                    item[itemName] = $(event.target).maskMoney('unmasked')[0]
                },
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If amount is not a number, return it as is
                },
                removeItem($index) {
                    this.items.splice($index, 1);
                }
            }
        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#FmiLguLiquidations").on("beforeSubmit", function (event) {
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
JS;
$this->registerJs($js);
?>