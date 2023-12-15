<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

$this->title = "FMI SORD";
$this->params['breadcrumbs'][] = $this->title;
echo "ssssssssssss";
?>
<div class="jev-preparation-index" style="background-color: white;" id="mainVue">

    <form @submit.prevent="generateSord()">
        <div class="row">
            <div class="col-3">
                <?= Select2::widget([
                    'name' => 'id',
                    'id' => 'id',
                    // 'data' => ArrayHelper::map($mgrfr, 'id', 'serial_number'),
                    'options' => ['placeholder' => 'Search for a Subproject Serial No. ...'],
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
        <tr v-for="item in sordData.grantForTheMonth">
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
                            console.log(this.sordData.grantForTheMonth)
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
                    return 0;
                    return this.getDepositTotalForType('matching_grant_amount', 'balance_grant');
                },
                depositTotalEquity() {
                    return 0;
                    return this.getDepositTotalForType('equity_amount', 'balance_equity');
                },
                depositTotalOther() {
                    return 0;
                    return this.getDepositTotalForType('other_amount', 'balance_other_amount');
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