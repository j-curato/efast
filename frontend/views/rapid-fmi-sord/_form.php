<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\RapidFmiSord */
/* @var $form yii\widgets\ActiveForm */

$subprojectData = [
    [
        'id' => $model->fk_fmi_subproject_id ?? null,
        'serial_number' => $model->fmiSubproject->serial_number ?? null
    ]
]
?>

<div class="rapid-fmi-sord-form" id="mainVue">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="card p-2">

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
            <div class="col-sm-3">

                <?= $form->field($model, 'reporting_period')->widget(
                    DatePicker::class,
                    [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'minViewMode' => 'months',
                            'autoclose' => true
                        ]
                    ]
                ) ?>
            </div>
            <div class="col-sm-2" style="padding-top: 2em;">
                <div class="form-group">
                    <button type="button" class="btn btn-warning" @click.prevent="generateSord">Generate</button>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3">

        <table class="w-100 mt-5">
            <tr>
                <th class="text-center border-0" colspan="8">
                    <span v-if="sordData.details.municipality_name">{{sordData.details.municipality_name}} ,</span>
                    <span v-if="sordData.details.province_name">{{sordData.details.province_name}} </span>
                    <br>
                    <span>RAPID GROWTH PROJECT</span>
                </th>
            </tr>
            <tr>
                <th class="text-center border-0" colspan="8">
                    <span>STATEMENT OF RECEIPTS AND DISBURSEMENTS</span><br>
                    <span>as of July 31, 2023</span>
                </th>
            </tr>
            <tr>
                <th colspan="8" class="border-0">

                    <span>Bank Name and Branch:</span> <span class="text-uppercase"> {{sordData.details.branch_name}}</span><br>
                    <span>Bank Account Name: </span> <span class="text-uppercase">{{sordData.details.bank_account_name}}</span><br>
                    <span>Bank Account Number: {{sordData.details.bank_account_number}}</span><br>
                    <span>Project Name: {{sordData.details.project_name}} </span><br>
                    <span>Project Location:
                        <span v-if="sordData.details.purok">{{sordData.details.purok}} ,</span>
                        <span v-if="sordData.details.barangay_name">{{sordData.details.barangay_name}} ,</span>
                        <span v-if="sordData.details.municipality_name">{{sordData.details.municipality_name}} ,</span>
                        <span v-if="sordData.details.province_name">{{sordData.details.province_name}} </span>
                    </span>
                </th>
            </tr>
            <tr>

                <th colspan="4"></th>
                <th class="text-center">Grant</th>
                <th class="text-center"> LGU Equity</th>
                <th class="text-center"> Other Funds</th>
                <th class="text-center"> Total</th>
            </tr>
            <tr>
                <td colspan="4">
                    Beginning Balance
                </td>



                <td class="text-right">{{formatAmount(sordData.beginningBalance.grant_beginning_balance)}}</td>
                <td class="text-right">{{formatAmount(sordData.beginningBalance.equity_beginning_balance)}}</td>
                <td class="text-right">{{formatAmount(sordData.beginningBalance.other_beginning_balance)}}</td>
                <td class="text-right">{{formatAmount(parseFloat(sordData.beginningBalance.grant_beginning_balance) +
            parseFloat(sordData.beginningBalance.equity_beginning_balance) +
            parseFloat(sordData.beginningBalance.other_beginning_balance))}}</td>

            </tr>
            <tr v-for="item in sordData.grantDepositsForTheMonth">
                <td class="" colspan="4">{{item.particular}}</td>
                <td class="text-right">{{formatAmount(item.total_grant_deposit)}}</td>
                <td class="text-right">{{formatAmount(item.total_equity_deposit)}}</td>
                <td class="text-right">{{formatAmount(item.total_other_deposit)}}</td>
                <td class="text-right">{{formatAmount(parseFloat(item.total_equity_deposit) +
                parseFloat(item.total_grant_deposit) +
                parseFloat(item.total_other_deposit))}}</td>
            </tr>
            <tr v-for="item in sordData.equityDepositsForTheMonth">
                <td class="" colspan="4">{{item.particular}}</td>
                <td class="text-right">{{formatAmount(item.total_grant_deposit)}}</td>
                <td class="text-right">{{formatAmount(item.total_equity_deposit)}}</td>
                <td class="text-right">{{formatAmount(item.total_other_deposit)}}</td>
                <td class="text-right">{{formatAmount(parseFloat(item.total_equity_deposit) +
                parseFloat(item.total_grant_deposit) +
                parseFloat(item.total_other_deposit))}}</td>
            </tr>
            <tr v-for="item in sordData.otherDepositsForTheMonth">
                <td class="" colspan="4">{{item.particular}}</td>
                <td class="text-right">{{formatAmount(item.total_grant_deposit)}}</td>
                <td class="text-right">{{formatAmount(item.total_equity_deposit)}}</td>
                <td class="text-right">{{formatAmount(item.total_other_deposit)}}</td>
                <td class="text-right">{{formatAmount(parseFloat(item.total_equity_deposit) +
                parseFloat(item.total_grant_deposit) +
                parseFloat(item.total_other_deposit))}}</td>
            </tr>
            <tr>
                <th colspan="4">Total Funds Available</th>
                <th class="text-right">{{formatAmount(depositTotalGrant)}}</th>
                <th class="text-right">{{formatAmount(depositTotalEquity)}}</th>
                <th class="text-right">{{formatAmount(depositTotalOther)}}</th>
                <th class="text-right">{{formatAmount(depositTotalGrant + depositTotalEquity + depositTotalOther)}}</th>
            </tr>
            <tr>
                <th class="border-0" colspan="8">Less Expenses</th>
            </tr>
            <tr>
                <th class="text-center" rowspan="2">Date</th>
                <th class="text-center" rowspan="2">DV No.</th>
                <th class="text-center" rowspan="2">Payee</th>
                <th class="text-center" rowspan="2">Particulars</th>
                <th class="text-center" colspan="4">Amount</th>
            </tr>
            <tr>
                <th class="text-center">Grant</th>
                <th class="text-center">LGU Equity</th>
                <th class="text-center">Other Funds</th>
                <th class="text-center">Total</th>
            </tr>

            <tr v-for="item in sordData.liquidations">
                <td>{{item.date}}</td>
                <td>{{item.check_number}}</td>
                <td>{{item.payee}}</td>
                <td>{{item.particular}}</td>
                <td class="text-right">{{formatAmount(item.grant_amount)}}</td>
                <td class="text-right">{{formatAmount(item.equity_amount)}}</td>
                <td class="text-right">{{formatAmount(item.other_fund_amount)}}</td>
                <td class="text-right">{{formatAmount(parseFloat(item.grant_amount)
                + parseFloat(item.equity_amount) 
                + parseFloat(item.other_fund_amount))}}</td>
            </tr>

            <tr>
                <th colspan="4" class="">Total Expense</th>
                <th class="text-right total-border">{{formatAmount(liquidationTotalGrant)}}</th>
                <th class="text-right total-border">{{formatAmount(liquidationTotalEquity)}}</th>
                <th class="text-right total-border">{{formatAmount(liquidationTotalOther)}}</th>
                <th class="text-right total-border">{{formatAmount(liquidationTotalGrant + liquidationTotalEquity + liquidationTotalOther )}}</th>
            </tr>
            <tr>
                <th colspan="4"> Cash Balance</th>
                <th class="text-right  total-border  "> {{formatAmount(depositTotalGrant - liquidationTotalGrant) }}</th>
                <th class="text-right  total-border "> {{formatAmount(depositTotalEquity - liquidationTotalEquity) }}</th>
                <th class="text-right  total-border "> {{formatAmount(depositTotalOther - liquidationTotalOther) }}</th>
                <th class="text-right  total-border "> {{formatAmount((depositTotalGrant - liquidationTotalGrant)+
                    (depositTotalEquity - liquidationTotalEquity)+
                    (depositTotalOther - liquidationTotalOther))
                 }}
                </th>
            </tr>
        </table>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 6px;
    }
</style>
<?php

?>
<script>
    $(document).ready(function() {
        new Vue({
            el: '#mainVue',
            data: {
                sordData: {
                    beginningBalance: [],
                    liquidations: [],
                    cashDepositBalance: [],
                    details: [],
                    grantDepositsForTheMonth: [],
                    equityDepositsForTheMonth: [],
                    otherDepositsForTheMonth: [],

                },
                sordData: <?= $sordData ?? json_encode(
                                [
                                    "beginningBalance" => [],
                                    "liquidations" => [],
                                    "cashDepositBalance" => [],
                                    "details" => [],
                                    "grantDepositsForTheMonth" => [],
                                    "equityDepositsForTheMonth" => [],
                                    "otherDepositsForTheMonth" => [],

                                ]
                            ) ?>,
            },
            mounted() {


            },
            methods: {
                generateSord() {
                    const url = window.location.pathname + "?r=rapid-fmi-sord/generate-sord"
                    const data = {
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
                        id: $("#rapidfmisord-fk_fmi_subproject_id").val(),
                        reporting_period: $("#rapidfmisord-reporting_period").val()
                    }
                    axios.post(url, data)
                        .then(res => {
                            this.sordData = res.data
                        })
                        .catch(err => {
                            console.log(err)
                        })

                },
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString()
                    }
                    return 0;
                },
                getLiquidationTotalByColumn(colName) {
                    return this.sordData.liquidations.reduce((total, item) => total + parseFloat(item[colName]), 0)
                },
                getCashDepositTotalByColumn(columnName) {
                    return this.sordData.cashDeposits.reduce((total, item) => total + parseFloat(item[columnName]), 0)
                },

                getTotalGrantDeposits() {

                    return this.sordData.grantDepositsForTheMonth.reduce(
                        (total, item) => total + parseFloat(item.total_grant_deposit), 0
                    )
                },
                getTotalEquityDeposits() {
                    return this.sordData.equityDepositsForTheMonth.reduce(
                        (total, item) => total + parseFloat(item.total_equity_deposit), 0
                    )
                },
                getTotalOtherDeposits() {
                    return this.sordData.otherDepositsForTheMonth.reduce(
                        (total, item) => total + parseFloat(item.total_other_deposit), 0
                    )
                },
            },
            computed: {
                depositTotalGrant() {
                    return parseFloat(this.sordData.beginningBalance.grant_beginning_balance) + parseFloat(this.getTotalGrantDeposits())
                },
                depositTotalEquity() {
                    return parseFloat(this.sordData.beginningBalance.equity_beginning_balance) + parseFloat(this.getTotalEquityDeposits());
                },
                depositTotalOther() {
                    return parseFloat(this.sordData.beginningBalance.other_beginning_balance) + parseFloat(this.getTotalOtherDeposits());
                },
                liquidationTotalGrant() {
                    return this.getLiquidationTotalByColumn('grant_amount')
                },
                liquidationTotalEquity() {
                    return this.getLiquidationTotalByColumn('equity_amount')
                },
                liquidationTotalOther() {
                    return this.getLiquidationTotalByColumn('other_fund_amount')
                },

            }

        });
    })
</script>

<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#RapidFmiSord").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
          const res = JSON.parse(data)
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