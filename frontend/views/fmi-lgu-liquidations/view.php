<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiLguLiquidations */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Lgu Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-lgu-liquidations-view" id="mainVue">




    <div class="container">
        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </span>
        </div>
        <div class="card p-3">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'serial_number',
                    'office.office_name',
                    'reporting_period',
                ],
            ]) ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Reporting Period</th>
                        <th>Date</th>
                        <th>Check No.</th>
                        <th>Payee</th>
                        <th>Particular</th>
                        <th>Grant Amount</th>
                        <th>Equity Amount</th>
                        <th>Other Funds</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="item in items">
                        <td>{{item.formatted_period}}</td>
                        <td>{{item.date}}</td>
                        <td>{{item.check_number}}</td>
                        <td>{{item.payee}}</td>
                        <td>{{item.particular}}</td>
                        <td>{{item.grant_amount}}</td>
                        <td>{{item.equity_amount}}</td>
                        <td>{{item.other_fund_amount}}</td>
                        <td>{{parseFloat(parseFloat(item.grant_amount) + parseFloat(item.equity_amount)+ parseFloat(item.other_fund_amount).toFixed(2))}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        new Vue({
            el: "#mainVue",
            data: {
                items: <?= json_encode($items) ?>
            },
        })
    });
</script>