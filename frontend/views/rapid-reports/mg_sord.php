<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

$this->title = "List of Pending DV's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;" id="mainVue">

    <form @submit.prevent="generateSord()">
        <div class="row">
            <div class="col-3">
                <?= Select2::widget([
                    'name' => 'id',
                    'id' => 'id',
                    // 'data' => ArrayHelper::map($mgrfr, 'id', 'serial_number'),
                    'options' => ['placeholder' => 'Search for a MG RFR Serial No. ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['mgrfrs/search-mgrfr']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],
                ]) ?>
            </div>
            <div class="col-3">

                <?= DatePicker::widget([
                    'id' => 'reporting_period',
                    'name' => 'reporting_period',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'autoclose' => true,
                        'minViewMode' => 'months',
                    ]
                ]) ?>
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-success">Submit</button>

            </div>
        </div>

    </form>

    <table class="w-100 mt-5">
        <tr>
            <th class="text-center border-0" colspan="8">
                <span>Municipality of Prosperidad, Agusan del Sur</span> <br>
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

                <span>Bank Name and Branch:</span> <span class="text-uppercase"> {{sordData.mgrfrDetails.branch_name}}</span><br>
                <span>Bank Account Name: </span> <span class="text-uppercase">{{sordData.mgrfrDetails.bank_name}}</span><br>
                <span>Bank Account Number: {{sordData.mgrfrDetails.saving_account_number}}</span><br>
                <span>Project Name: {{sordData.mgrfrDetails.investment_type}} </span><br>
                <span>Project Location:
                    <span v-if="sordData.mgrfrDetails.purok">{{sordData.mgrfrDetails.purok}} ,</span>
                    <span v-if="sordData.mgrfrDetails.barangay_name">{{sordData.mgrfrDetails.barangay_name}} ,</span>
                    <span v-if="sordData.mgrfrDetails.municipality_name">{{sordData.mgrfrDetails.municipality_name}} ,</span>
                    <span v-if="sordData.mgrfrDetails.province_name">{{sordData.mgrfrDetails.province_name}} </span>
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
            <td class="text-right">{{formatAmount(sordData.cashDepositBalance.balance_grant)}}</td>
            <td class="text-right">{{formatAmount(sordData.cashDepositBalance.balance_equity)}}</td>
            <td class="text-right">{{formatAmount(sordData.cashDepositBalance.balance_other_amount)}}</td>
            <td class="text-right">{{formatAmount(parseFloat(sordData.cashDepositBalance.balance_grant) +
            parseFloat(sordData.cashDepositBalance.balance_equity) +
            parseFloat(sordData.cashDepositBalance.balance_other_amount))}}</td>

        </tr>
        <tr v-for="item in sordData.cashDeposits">
            <td class="" colspan="4">{{item.particular}}</td>
            <td class="text-right">{{formatAmount(item.matching_grant_amount)}}</td>
            <td class="text-right">{{formatAmount(item.equity_amount)}}</td>
            <td class="text-right">{{formatAmount(item.other_amount)}}</td>
            <td class="text-right">{{formatAmount(parseFloat(item.equity_amount) +
                parseFloat(item.matching_grant_amount) +
                parseFloat(item.other_amount))}}</td>
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
            <td>{{item.dv_number}}</td>
            <td>{{item.supplier_name}}</td>
            <td>{{item.comments}}</td>
            <td class="text-right">{{formatAmount(item.matching_grant_amount)}}</td>
            <td class="text-right">{{formatAmount(item.equity_amount)}}</td>
            <td class="text-right">{{formatAmount(item.other_amount)}}</td>
            <td class="text-right">{{formatAmount(parseFloat(item.matching_grant_amount) + parseFloat(item.equity_amount) + parseFloat(item.other_amount))}}</td>
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
        <tr>
            <td class="text-center border-0 pt-5" colspan="8">

                <div class="w-25 float-left " style="border: 1px 1px  solid black ">

                    <p class="font-weight-bold">Certified By</p> <br>
                    <span>___________________________</span><br>
                    <span>Municipal Accountant</span>
                </div>
                <div class=" w-25 float-left">
                    <p class="font-weight-bold">Approved By</p><br>
                    <span>___________________________</span><br>
                    <span>Municipal Mayor</span>
                </div>
                <div class="w-25 float-left">
                    <p class="font-weight-bold">Received By</p><br>
                    <span>___________________________</span><br>
                    <span>COA Auditor</span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="8" class="border-0 pt-5">see enclosed bank statement and bank reconciliation</td>
        </tr>
    </table>
</div>



<style>
    th,
    td {
        border: 1px solid black;
        padding: 6px;
    }

    /* 
    .total-border {
        border: none;
        border-bottom: 1px solid black;
    } */
</style>

<script>
    $(document).ready(function() {
        new Vue({
            el: '#mainVue',
            data: {
                sordData: {
                    liquidations: [],
                    cashDeposits: [],
                    cashDepositBalance: [],
                    mgrfrDetails: [],

                },
            },
            mounted() {


            },
            methods: {
                generateSord() {
                    const url = window.location.href
                    const data = {
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
                        id: $("#id").val(),
                        reporting_period: $("#reporting_period").val()
                    }
                    axios.post(url, data)
                        .then(res => {
                            this.sordData = res.data
                            console.log(this.sordData)
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


                getDepositTotalForType(colName, balancePropertyName) {
                    return this.getCashDepositTotalByColumn(colName) +
                        parseFloat(this.sordData.cashDepositBalance[balancePropertyName]);
                },

            },
            computed: {
                depositTotalGrant() {
                    return this.getDepositTotalForType('matching_grant_amount', 'balance_grant');
                },
                depositTotalEquity() {
                    return this.getDepositTotalForType('equity_amount', 'balance_equity');
                },
                depositTotalOther() {
                    return this.getDepositTotalForType('other_amount', 'balance_other_amount');
                },
                liquidationTotalGrant() {
                    return this.getLiquidationTotalByColumn('matching_grant_amount')
                },
                liquidationTotalEquity() {
                    return this.getLiquidationTotalByColumn('equity_amount')
                },
                liquidationTotalOther() {
                    return this.getLiquidationTotalByColumn('other_amount')
                },

            }

        });
    })
</script>