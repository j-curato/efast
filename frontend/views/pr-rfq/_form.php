<?php

use app\models\Office;
use app\models\PrModeOfProcurement;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */
/* @var $form yii\widgets\ActiveForm */


$pr = [
    [
        'id' => !empty($model->pr_purchase_request_id) ? $model->pr_purchase_request_id : '',
        'pr_number' => !empty($model->purchaseRequest->pr_number) ? $model->purchaseRequest->pr_number : ''
    ]
];
$canvasser  = !empty($model->employee_id) ? $model->canvasser->getEmployeeDetails() : [];
$modeOfProcurements = PrModeOfProcurement::getModeOfProcurementsA();
$observers = $model->getObservers()->asArray()->all();
?>

<div class="pr-rfq-form d-none" id="mainVue">
    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="card" style="padding: 1rem;">

        <ul>
            <li>Note</li>
            <li>The RFQ number is updated every time the date changes.</li>
        </ul>
        <div class="row">
            <?php if (Yii::$app->user->can('ro_procurement_admin')) : ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Office',
                        ],

                    ]) ?>
                </div>
            <?php endif ?>
            <div class="col-sm-2">
                <?= $form->field($model, '_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true
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
                        'format' => 'yyyy-mm-dd hh:ii',
                        'autoclose' => true
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]

                ]) ?>

            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'pr_purchase_request_id')->widget(Select2::class, [
                    'data' =>  ArrayHelper::map($pr, 'id', 'pr_number'),
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

            <div class="col-2">
                <?= $form->field($model, 'is_early_procurement')->widget(Select2::class, [
                    'data' => ['0' => 'No', "1" => 'Yes'],
                ]) ?>
            </div>
            <div class="col-2">
                <?= $form->field($model, 'source_of_fund')->widget(Select2::class, [
                    'data' => ['gop' => 'GOP', "lp" => 'LP'],

                ]) ?>
            </div>
            <div class="col-2">
                <?= $form->field($model, 'fk_mode_of_procurement_id')->dropDownList(
                    ArrayHelper::map($modeOfProcurements, 'id', 'mode_name'),
                    [
                        'prompt' => 'Mode of Procurement?',
                        '@change' => 'checkIfBidding',
                        'v-model' => 'fk_mode_of_procurement_id'

                    ]
                ) ?>
            </div>



            <div class="col-2">
                <?= $form->field($model, 'mooe_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>

            <div class="col-2">
                <?= $form->field($model, 'co_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'employee_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map([$canvasser], 'employee_id', 'fullName'),
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



    </div>
    <div class="card p-2" v-if="isBidding">
        <table class="w-100">
            <tr>
                <th>Observers</th>
                <td><button type="button" class="btn-xs btn-success" @click="addObserver"><i class="fa fa-plus"></i> Add </button></td>
            </tr>
            <tr v-for="(observer,index) in observers">
                <td class="d-none">
                    <input type="hidden" v-if="observer.id" :name="'observers[' + index + '][id]'" v-model="observer.id">
                </td>
                <td>
                    <input type="text" class="form-control" :name="'observers[' + index + '][observer_name]'" v-model="observer.observer_name">
                </td>
                <td>
                    <button class="btn-xs btn-danger" type="button" @click="removeObserver(index)"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </table>
    </div>
    <div class="card p-3">

        <table id="pr_data" class="table mt-3">
            <tbody>
                <tr>
                    <td>
                        <span class='pr_data_header'>
                            Date Propose:
                        </span>
                        <span class="text-uppercase">{{prItemsDetails.prDetails.date_propose}}</span>
                    </td>
                    <td>
                        <span class='pr_data_header'> PR Number: </span>
                        <span class="text-uppercase">{{prItemsDetails.prDetails.pr_number}}</span>
                    </td>
                    <td>
                        <span class='pr_data_header'> Book:</span>
                        <span class="text-uppercase">{{prItemsDetails.prDetails.book_name}}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span class='pr_data_header'>
                            Purpose: {{prItemsDetails.prDetails.purpose}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class='pr_data_header'>Office: {{prItemsDetails.prDetails.office_name}}</span>
                    </td>
                    <td>
                        <span class='pr_data_header'>Division:{{prItemsDetails.prDetails.division}}</span>
                    </td>
                    <td>
                        <span class='pr_data_header'>Unit:{{prItemsDetails.prDetails.unit}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class='pr_data_header'>Prepared By:</span>
                        <span class="text-uppercase">{{prItemsDetails.prDetails.prepared_by}}</span>
                    </td>
                    <td>
                        <span class='pr_data_header'>Requested By:</span>
                        <span class="text-uppercase">{{prItemsDetails.prDetails.requested_by}}</span>
                    </td>
                    <td>
                        <span class='pr_data_header'>Approved By:</span>
                        <span class="text-uppercase">{{prItemsDetails.prDetails.approved_by}}</span>
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
                <tr v-for="(item,index) in prItemsDetails.prItems">
                    <td class="d-none">
                        <input type='hidden' class='form-check-input' v-if="item.id" v-model="item.id" :name="'items[' + index + '][id]'">
                    </td>
                    <td class='d-none'>
                        <input type='hidden' class='form-check-input' v-model="item.pr_item_id" :name="'items[' + index + '][pr_purchase_request_item_id]'">
                    </td>
                    <td style='text-align:center;'>
                        <input type='checkbox' class='form-check-input' :name="'items[' + index + '][is_selected]'" :checked="item.id !== undefined">
                    </td>
                    <td>{{item.bac_code}}</td>
                    <td>{{item.stock_title}}</td>
                    <td>{{item.unit_of_measure}}</td>
                    <td>specs</td>
                    <td>{{item.unit_cost}}</td>
                    <td>{{item.quantity}}</td>
                    <td>GRoss</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class=" row justify-content-center">
        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
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

?>
<script>
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $(document).ready(function(e) {
        $('#mainVue').removeClass('d-none')
        new Vue({
            el: '#mainVue',
            data: {
                'isBidding': <?= !empty($observers) ? 1 : 0 ?>,
                'observers': <?= !empty($observers) ? json_encode($observers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : json_encode([]) ?>,
                'fk_mode_of_procurement_id': '<?= $model->fk_mode_of_procurement_id ?>',
                "prItemsDetails": {
                    "prDetails": '<?= !empty($model->id) ? json_encode($model->purchaseRequest->getPrDetails()) : json_encode([]) ?>',
                    "prItems": <?= !empty($items) ? json_encode($items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : json_encode([]) ?>,
                },
                "modeOfProcurements": <?= !empty($modeOfProcurements) ? json_encode($modeOfProcurements, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : json_encode([]) ?>,
            },
            mounted() {
                $('#prrfq-pr_purchase_request_id').on('change',
                    this.getRfqItems
                )
                console.log(this.observers)
            },
            methods: {

                getRfqItems() {
                    const url = window.location.pathname + '?r=pr-rfq/get-pr-items'
                    const data = {
                        id: $('#prrfq-pr_purchase_request_id').val(),
                        _csrf: csrfToken
                    }
                    axios.post(url, data)
                        .then(response => {
                            this.prItemsDetails = response.data
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                },
                checkIfBidding() {
                    let mode = this.modeOfProcurements.find(item => item.id === this.fk_mode_of_procurement_id)
                    this.observers = []
                    if (parseInt(mode.is_bidding) === 1) {
                        this.addObserver()
                        this.isBidding = true
                    }
                },
                addObserver() {
                    this.observers.push({
                        'observer_name': ''
                    })


                },
                removeObserver(index) {
                    this.observers.splice(index, 1)
                }



            },

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