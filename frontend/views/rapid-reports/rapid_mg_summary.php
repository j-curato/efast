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
/* @var $model app\models\RapidMgSord */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="rapid-mg-sord-form" id="mainVue">
    <div class="card p-3">
        <form @submit.prevent="apiMgSummary">
            <div class="row">
                <div class="col-3">
                    <?= DatePicker::widget([
                        'name' => 'reportingPeriod',
                        'id' => 'reportingPeriod',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'minViewMode' => 'months',
                            'autoclose' => true
                        ]
                    ]) ?>

                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>

        </form>
    </div>
    <div class="card p-3 mt-3">
        <!-- province_name
        municipality_name
        barangay_name
        organization_name
        purok
        investment_type
        investment_description
        balance_equity
        balance_grant
        balance_other_amount -->
        <table>
            <thead>
                <tr>
                    <th>Investment Type</th>
                    <th>Investment Description</th>
                    <th>Province</th>
                    <th>Municipality/City</th>
                    <th>Barangay</th>
                    <th>Purok/Sitio</th>
                    <th>Balance Grant</th>
                    <th>Balance Equity</th>
                    <th>Balance Other Funds</th>
                    <th>Total Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items">
                    <th>{{item.investment_type}}</th>
                    <th>{{item.investment_description}}</th>
                    <th>{{item.province_name}}</th>
                    <th>{{item.municipality_name}}</th>
                    <th>{{item.barangay_name}}</th>
                    <th>{{item.purok}}</th>
                    <th>{{formatAmount(item.balance_grant)}}</th>
                    <th>{{formatAmount(item.balance_equity)}}</th>
                    <th>{{formatAmount(item.balance_other_amount)}}</th>
                    <th>{{formatAmount(parseFloat(item.balance_grant)
                    + parseFloat(item.balance_equity)
                    + parseFloat(item.balance_other_amount))}}</th>




                </tr>
            </tbody>
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
                items: [],
            },
            mounted() {


            },
            methods: {
                apiMgSummary() {
                    const url = window.location.href
                    const data = {
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
                        reportingPeriod: $("#reportingPeriod").val(),
                    }
                    axios.post(url, data)
                        .then(res => {
                            this.items = res.data
                            console.log(this.items)
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

            },
            computed: {


            }

        });
    })
</script>