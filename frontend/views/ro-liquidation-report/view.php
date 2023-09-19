<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */

$this->title = $model->liquidation_report_number;
$this->params['breadcrumbs'][] = ['label' => 'Ro Liquidation Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



?>
<div class="ro-liquidation-report-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php

        $j = yii::$app->request->baseUrl . "/index.php?r=jev-preparation/liquidation-report-to-jev&id={$model->id}";
        echo Html::a('JEV', $j, ['class' => 'btn btn-warning']);
        ?>
    </p>


    <div class="con">

        <table id='dv_details_table' class="table table-condensed">

            <thead>
                <tr class="danger">
                    <th colspan="9">DV Details</th>
                </tr>
                <tr>
                    <th>DV Number</th>
                    <th>Payee</th>
                    <th>Check Number</th>
                    <th>ADA Number</th>
                    <th>Particular</th>
                    <th>Issaunce Date</th>
                    <th class="amount">Total Disburse</th>
                    <th class="amount">Liquidated</th>
                    <th class="amount">Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $dv_detail['dv_number'] ?></td>
                    <td><?= $dv_detail['payee'] ?></td>
                    <td><?= $dv_detail['check_number'] ?></td>
                    <td><?= $dv_detail['ada_number'] ?></td>
                    <td><?= $dv_detail['particular'] ?></td>
                    <td>
                        <?php
                        echo DateTime::createFromFormat('Y-m-d',   $dv_detail['issuance_date'])->format('F d, Y')
                        ?>
                    </td>
                    <td class="amount">
                        <?php
                        echo !empty($dv_detail['total_disbursed']) ? number_format($dv_detail['total_disbursed'], 2) : 0;
                        ?>
                    </td>
                    <td class="amount">
                        <?php
                        echo !empty($dv_detail['liquidated_amount']) ? number_format($dv_detail['liquidated_amount'], 2) : 0;
                        ?>
                    </td>
                    <td class="amount">
                        <?php
                        echo !empty($dv_detail['balance']) ? number_format($dv_detail['balance'], 2) : 0;
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table id='dv_details_table' class="table  table-condensed">

            <thead>
                <tr class="success">
                    <th colspan="3">Entry</th>
                </tr>

                <tr>
                    <th>Reporting Period</th>
                    <th>UACS</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($items as $val) {
                    $amount = $val['amount'];
                    $object_code = $val['object_code'];
                    $account_title = $val['account_title'];
                    $reporting_period = $val['reporting_period'];
                    echo "<tr>
                        <td>$reporting_period</td>
                        <td>$object_code - $account_title</td>
                        <td class='amount'>" . number_format($amount, 2) . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <table id='dv_details_table' class="table  table-condensed">

            <thead>
                <tr class="warning">
                    <th colspan="4" style="text-align: center;">REFUND</th>
                </tr>
                <tr>
                    <th>Reporting Period</th>
                    <th>OR Date</th>
                    <th>OR Number</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($refund_items as $val) {
                    $amount = $val['amount'];
                    $reporting_period = $val['reporting_period'];
                    $or_number = $val['or_number'];
                    $or_date = $val['or_date'];
                    echo "<tr>
                    <td>$reporting_period</td>
                    <td>$or_date</td>
                    <td>$or_number</td>
                    <td class='amount'>" . number_format($amount, 2) . "</td>
                </tr>";
                }
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

    th,
    td {
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }
    }
</style>