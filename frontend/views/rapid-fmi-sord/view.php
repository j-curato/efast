<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RapidFmiSord */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rapid FMI SORDs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
// $period = DateTime::createFromFormat("Y-m-d", $model->reporting_period);
$period = new \DateTime($model->reporting_period);
?>
<div class="rapid-fmi-sord-view">
    <div class="card p-2">
        <span>
            <?= Yii::$app->user->can('super-user') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        </span>
    </div>

    <div class="card p-3" id="mainVue">
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
                    <span>as of <?= $period->format('F, Y') ?></span>
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

</div>

<style>
    th,
    td {
        border: 1px solid black;
        padding: 6px;
    }
</style>

<script>
    $(document).ready(function() {
        new Vue({
            el: '#mainVue',
            data: {
                sordData: <?= $sordData ?>,
            },
            methods: {
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