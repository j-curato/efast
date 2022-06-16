<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Liquidation Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



?>
<div class="ro-liquidation-report-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <div class="con">

        <table class="table entries_table table-striped">
            <thead>
                <tr class="success">
                    <th colspan="8">
                        Entry
                    </th>
                </tr>
                <tr>
                    <th>Reporting Period</th>
                    <th>Payee</th>
                    <th>Check Number</th>
                    <th>ADA Number</th>
                    <th>Check Issuance Date</th>
                    <th>Particular</th>
                    <th> Account Name</th>
                    <th> Amount</th>

                </tr>

            </thead>

            <tbody>

                <?php
                $entry_total = 0;
                foreach ($items as $val) {
                    $reporting_period = $val['reporting_period'];
                    $payee = $val['payee'];
                    $check_number = $val['check_or_ada_no'];
                    $ada_number = $val['ada_number'];
                    $issuance_date = $val['issuance_date'];
                    $particular = $val['particular'];
                    $object_code = $val['object_code'];
                    $account_title = $val['account_title'];
                    $amount = floatVal($val['amount']);
                    echo "<tr>
                    <td>$reporting_period</td>
                    <td>$payee</td>
                    <td>$check_number</td>
                    <td>$ada_number</td>
                    <td>$issuance_date</td>
                    <td>$particular</td>
                    <td>$object_code - $account_title</td>
                    <td class='amount'>" . number_format($amount, 2) . "</td>
                    
                    
                    </tr>";
                    $entry_total += $amount;
                }

                echo "<tr>
                <td colspan='7'  style='text-align:center;font-weight:bold;'>Total</td>

                <td class='amount'>" . number_format($entry_total, 2) . "</td>
                
                
                </tr>";
                ?>
            </tbody>
        </table>

        <table class="table refunds_table">
            <thead>

                <tr class="danger">
                    <th colspan="9">
                        Refunds
                    </th>
                </tr>
                <tr>

                    <th>Reporting Period</th>
                    <th>Payee</th>
                    <th>Check Number</th>
                    <th>ADA Number</th>
                    <th>Check Issuance Date</th>
                    <th>Particular</th>
                    <th> OR Date</th>
                    <th> OR Number</th>
                    <th> Amount</th>
                </tr>

            </thead>

            <tbody>
                <?php
                $refund_total = 0;
                foreach ($refund_items as $val) {
                    $reporting_period = $val['reporting_period'];
                    $payee = $val['payee'];
                    $check_number = $val['check_or_ada_no'];
                    $ada_number = $val['ada_number'];
                    $issuance_date = $val['issuance_date'];
                    $particular = $val['particular'];
                    $or_date = $val['or_date'];
                    $or_number = $val['or_number'];

                    $amount = $val['amount'];
                    echo "<tr>
                    <td>$reporting_period</td>
                    <td>$payee</td>
                    <td>$check_number</td>
                    <td>$ada_number</td>
                    <td>$issuance_date</td>
                    <td>$particular</td>
                    <td>$or_date</td>
                    <td>$or_number</td>
                    <td class='amount'>" . number_format($amount, 2) . "</td>
                    
                    
                    </tr>";
                    $refund_total += $amount;
                }
                echo "<tr>
                <td colspan='7' style='text-align:center;font-weight:bold;'>Total</td>

                <td class='amount'>" . number_format($refund_total, 2) . "</td>
                
                
                </tr>";
                ?>
            </tbody>
        </table>
    </div>

</div>
<style>
    .con {
        background-color: white;
        padding: 3rem;
    }

    th {
        text-align: center;
    }

    .amount {
        text-align: right;
    }
</style>