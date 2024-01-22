<?php

use app\models\BacComposition;
use app\models\Office;
use app\models\PrContractType;
use app\models\PrModeOfProcurement;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use kartik\widgets\DatePicker as WidgetsDatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseOrder */
/* @var $form yii\widgets\ActiveForm */






$aoq = [];
if (!empty($model->fk_pr_aoq_id)) {
    $aoq[] = [
        'id' => $model->fk_pr_aoq_id,
        'aoq_number' => $model->aoq->aoq_number
    ];
}
$authOfficial[] = !empty($model->fk_auth_official) ? $model->authorizedOfficial->employeeDetails : [];
$accountingUnit[] = !empty($model->fk_accounting_unit) ? $model->accountingUnit->employeeDetails : [];
$modeOfProcurementOptions = PrModeOfProcurement::find()->asArray()->all();
$contractTypeOptions = PrContractType::find()->asArray()->all();
$isBidding = false;
if (!empty($model->aoq->rfq->modeOfProcurement->is_bidding)) {
    $isBidding =  $model->aoq->rfq->modeOfProcurement->is_bidding == 1;
}

?>

<div class="pr-purchase-order-form d-none" id="mainVue">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
    ]); ?>
    <div class="card p-2">
        <div class="row">
            <?php if (Yii::$app->user->can('ro_procurement_admin')) : ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Office'
                        ]
                    ]) ?>
                </div>
            <?php endif; ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'po_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true
                    ]
                ]) ?>
            </div>
            <?php if ($model->isNewRecord) : ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_pr_aoq_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($aoq, 'id', 'aoq_number'),
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
            <?php endif; ?>

            <div class="col-sm-2">
                <?= $form->field($model, 'fk_contract_type_id')->dropDownList(
                    ArrayHelper::map($contractTypeOptions, 'id', 'contract_name'),
                    [
                        'prompt' => 'Select Contract',
                        '@change' => 'checkContractType',
                        'v-model' => 'contractType'
                    ]
                ) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_mode_of_procurement_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($modeOfProcurementOptions, 'id', 'mode_name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Mode'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'philgeps_reference_num')->textInput() ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'pre_proc_conference', ['enableAjaxValidation' => true])->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]) ?>
            </div>

            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'pre_bid_conf')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'eligibility_check')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'opening_of_bids')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'bid_evaluation')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'post_qual')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'bac_resolution_award')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'notice_of_award')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'contract_signing')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-2" :class="{ 'd-none': !isBidding }">
                <?= $form->field($model, 'notice_to_proceed')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-2  " :class="{ 'd-none': !isJo }">
                <?= $form->field($model, 'date_work_begun')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-sm-2  " :class="{ 'd-none': !isJo }">
                <?= $form->field($model, 'date_completed')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ]) ?>
            </div>


            <div class="col-sm-2">
                <?= $form->field($model, 'mooe_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>

            <div class="col-sm-2">
                <?= $form->field($model, 'co_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'place_of_delivery')->textInput() ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'delivery_date')->textInput() ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'delivery_term')->textInput() ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'payment_term')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'fk_auth_official')->widget(Select2::class, [
                    'data' => ArrayHelper::map($authOfficial, 'employee_id', 'fullName'),

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
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_accounting_unit')->widget(Select2::class, [
                    'data' => ArrayHelper::map($accountingUnit, 'employee_id', 'fullName'),
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

        <div class="row justify-content-center">
            <div class="form-group col-sm-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="card p-2">
        <table class="table">

            <tr>
                <th>Payee</th>
                <th>Purpose</th>
                <th>Description</th>
                <th>Specification</th>
                <th>Quantity</th>
                <th>Gross Amount</th>

            </tr>
            <tr v-for=" item in aoqDetails">
                <td>{{item.payee}}</td>
                <td>{{item.purpose}}</td>
                <td>{{item.description}}</td>
                <td>{{item.specification}}</td>
                <td>{{item.quantity}}</td>
                <td>{{item.gross_amount}}</td>
            </tr>
            <tr>

                <th colspan="5" class="text-center">Grand Total </th>
                <th>{{aoqGrandTotal}}</th>
            </tr>
        </table>
    </div>
</div>

<style>
</style>
<script>
    $(document).ready(function() {

        $("#mainVue").removeClass('d-none')
        new Vue({
            el: "#mainVue",
            data: {
                isBidding: "<?= $isBidding ?>",
                aoqId: "<?= !empty($model->fk_pr_aoq_id) ? $model->fk_pr_aoq_id : '' ?>",
                isJo: false,
                contractTypeOptions: <?= json_encode($contractTypeOptions) ?>,
                contractType: "<?= !empty($model->fk_contract_type_id) ? $model->fk_contract_type_id : '' ?>",
                aoqDetails: <?= !empty($model->fk_pr_aoq_id) ? json_encode($model->aoq->aoqDetailsForPoA) : json_encode([]) ?>
            },
            mounted() {
                $("#prpurchaseorder-fk_pr_aoq_id").on('change', this.getAoqDetails)
                this.checkContractType()
            },
            methods: {
                getAoqDetails() {
                    this.aoqId = $("#prpurchaseorder-fk_pr_aoq_id").val()
                    const url = "?r=pr-purchase-order/rfq-is-bidding"
                    if (!this.aoqId) {
                        return
                    }
                    let data = {
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                        id: this.aoqId
                    }
                    axios.post(url, data)
                        .then(res => {
                            this.isBidding = res.data.isBidding
                            this.aoqDetails = res.data.aoqDetails
                        })
                        .catch(err => {
                            console.log(err)
                        })


                },
                checkContractType() {
                    if (!this.contractType) {
                        return;
                    }
                    let x = this.contractTypeOptions.find(item => {
                        return item.id == this.contractType
                    })
                    x.contract_name.toLowerCase() == 'jo' ? this.isJo = true : this.isJo = false
                },

            },
            computed: {
                aoqGrandTotal() {

                    return this.aoqDetails.reduce((total, item) => total + parseFloat(item.gross_amount), 0)
                }
            }
        })


    })
</script>