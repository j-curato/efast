<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" id="main">
    <div class="container card">
        <!-- <button id="export" type='button' class="btn btn-success" style="margin:1rem;"><i class="glyphicon glyphicon-export"></i>Export</button> -->
        <p>
            <?= Yii::$app->user->can('update_ro_trial_balance') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => ' btn btn-primary']) : '' ?>
            <button @click="exportFile" type='button' class="btn btn btn-success" style="margin:1rem;">Export</button>
        </p>
        <table>
            <thead>
                <tr>
                    <th colspan="4" class="text-center ">
                        <span class="float-left">
                            <?= Html::img('frontend/web/dti.jpg', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;']); ?>
                        </span>
                        <h6 class="font-weight-bold">DEPARTMENT OF TRADE AND INDUSTRY</h6>
                        <h6 class="font-weight-bold">CARAGA REGIONAL OFFICE</h6>
                        <h6 class="font-weight-bold">Trial Balance <?= $model->book->name ?></h6>
                        <h6 class="font-weight-bold">As of <?= DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y') ?></h6>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" class="border-right-0">
                        <span>
                            Entity Name:
                        </span>
                        <span>
                            DEPARTMENT OF TRADE AND INDUSTRY - CARAGA
                        </span>
                    </th>
                    <th colspan="2" class="border-left-0 text-left">
                        <span>
                            Fund Cluster:
                        </span>
                        <span class="">
                            <?= $model->book->name ?>
                        </span>
                    </th>


                </tr>


                <tr>
                    <th class="text-center">Account Name</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Debit</th>
                    <th class="text-center">Credit</th>
                </tr>
            </thead>
            <tbody>

                <tr v-for='item in formattedItems'>

                    <td>{{item.account_title}}</td>
                    <td>{{item.object_code}}</td>
                    <td class="text-right">
                        <p v-if='item.debit !=0'> {{formatAmount(item.debit)}}</p>
                    </td>
                    <td class="text-right">
                        <p v-if='item.credit !=0'> {{formatAmount(item.credit)}}</p>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center">Total</th>
                    <th class="text-right">{{formatAmount(getDebitTotal)}}</th>
                    <th class="text-right">{{formatAmount(getCreditTotal)}}</th>
                </tr>
            </tbody>
        </table>

    </div>
</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }
    }
</style>
<?php
$items = $model->getItems();
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    $(document).ready(function() {
        new Vue({
            el: '#main',
            data: {
                items: <?= !empty($items) ? json_encode($items) : [] ?>,
                formattedItems: []
            },
            computed: {
                getDebitTotal() {
                    return this.formattedItems.reduce((total, item) => total + parseFloat(item.debit), 0)
                },
                getCreditTotal() {
                    return this.formattedItems.reduce((total, item) => total + parseFloat(item.debit), 0)
                }
            },
            mounted() {
                this.formattedItems = this.formatItems()
            },
            methods: {
                formatItems() {
                    return Object.keys(this.items).map((key) => {
                        const obj = this.items[key]
                        let debit = 0;
                        let credit = 0
                        if (obj.normal_balance.toLowerCase() == 'debit') {
                            if (parseFloat(obj.total_debit_credit) < 0) {
                                credit = Math.abs(obj.total_debit_credit)
                            } else {
                                debit = obj.total_debit_credit
                            }
                        }
                        if (obj.normal_balance.toLowerCase() == 'credit') {
                            if (parseFloat(obj.total_debit_credit) < 0) {
                                debit = Math.abs(obj.total_debit_credit)
                            } else {
                                credit = obj.total_debit_credit
                            }
                        }
                        return {
                            account_title: obj.account_title,
                            object_code: obj.object_code,
                            debit: debit,
                            credit: credit,
                        }
                    })

                },
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString()
                    }
                    return 0;
                },
                exportFile() {
                    const apiUrl = window.location.pathname + '?r=trial-balance/export'
                    const data = {
                        _csrf: '<?= $csrfToken ?>',
                        id: <?= $model->id ?>
                    }
                    axios.post(apiUrl, data)
                        .then(response => {
                            window.open(response.data)
                        })
                        .catch(error => {
                            console.log(error)
                        })
                }

            },
        })

    })
</script>