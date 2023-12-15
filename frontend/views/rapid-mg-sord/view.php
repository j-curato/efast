<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RapidMgSord */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rapid Mg Sords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rapid-mg-sord-view" id="mainVue">

    <div class="card p-2">
        <span>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </span>
    </div>
    <div class="card p-3">

        <table class=" w-100 mt-5">
            <tr>
                <th class="text-center border-0" colspan="8">
                    <span>Municipality of Prosperidad, Agusan del Sur</span> <br>
                    <span>RAPID GROWTH PROJECT</span>
                </th>
            </tr>
            <tr>
                <th class="text-center border-0" colspan="8">
                    <span>STATEMENT OF RECEIPTS AND DISBURSEMENTS</span><br>
                    <span>as of <?= DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F, Y') ?></span>
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
                sordData: <?= json_encode($sordData) ?>,
            },
            mounted() {


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