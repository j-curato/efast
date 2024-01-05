<?php

use kartik\date\DatePicker;


$this->title = "FMi Summary";
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
        <table>
            <thead>
                <tr>
                    <th>Project Name</th>

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
                    <th>{{item.project_name}}</th>
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