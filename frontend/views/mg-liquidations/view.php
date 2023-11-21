<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MgLiquidations */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Mg Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mg-liquidations-view" id="main">



    <div class="container">
        <div class="card p-2">

            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </span>

        </div>

        <div class=" card p-3">
            <div class="row mb-3">
                <div class=" col-3 p-2">
                    <b>Serial Number :<?= $model->serial_number ?> </b>
                </div>
                <div class=" col-3 p-2 ">
                    <b>Reporting Period :<?= $model->mgrfr->investment_type ?> </b>
                </div>

            </div>
            <div class="row">
                <div class=" col-3 p-2 ">
                    <b>Province :<?= $model->mgrfr->province->province_name ?> </b>
                </div>
                <div class=" col-3 p-2 ">
                    <b>City / Municipality :<?= $model->mgrfr->municipality->municipality_name ?> </b>
                </div>
                <div class=" col-3 p-2 ">
                    <b>Barangay :<?= $model->mgrfr->barangay->barangay_name ?> </b>
                </div>
                <div class=" col-3 p-2 ">
                    <b>Purok/Street :<?= $model->mgrfr->barangay->purok ?? '' ?> </b>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                    <th>Date</th>
                    <th>Payee</th>
                    <th>Comments</th>
                    <th>Grant Amount</th>
                    <th>LGU Equity Amount</th>
                    <th>Other Funds</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in items" :key="index">

                        <td class="">{{item.date}}</td>
                        <td>{{item.payee_name}}</td>
                        <td>{{item.comments}}</td>
                        <td class="">{{formatAmount(item.matching_grant_amount)}}</td>
                        <td class="">{{formatAmount(item.equity_amount)}}</td>
                        <td class="">{{formatAmount(item.other_amount)}}</td>
                        <td class="">
                            {{formatAmount(parseFloat(item.matching_grant_amount) +
                            parseFloat(item.equity_amount) +
                            parseFloat(item.other_amount))}}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-center">Total</th>
                        <th class="">{{formatAmount(totalGrant)}}</th>
                        <th class="">{{formatAmount(totalEquity)}}</th>
                        <th class="">{{formatAmount(totalOtherFunds)}}</th>
                        <th class="">{{formatAmount(totalGrant + totalEquity +totalOtherFunds)}}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    new Vue({
        el: '#main',
        data: {
            items: <?= !empty($model->getItems()) ? json_encode($model->getItems()) : json_encode([]) ?>
        },
        computed: {
            totalGrant() {
                return this.items.reduce((total, item) => total + parseFloat(item.matching_grant_amount), 0);
            },
            totalEquity() {
                return this.items.reduce((total, item) => total + parseFloat(item.equity_amount), 0);
            },
            totalOtherFunds() {
                return this.items.reduce((total, item) => total + parseFloat(item.other_amount), 0);
            },
        },
        methods: {
            formatAmount(amount) {
                amount = parseFloat(amount)
                if (typeof amount === 'number' && !isNaN(amount)) {
                    return amount.toLocaleString(); // Formats with commas based on user's locale
                }
                return 0; // If unitCost is not a number, return it as is
            }
        }

    })
</script>