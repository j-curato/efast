<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="liquidation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Re-Align/Update', ['re-align', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="container panel panel-default">

        <table class="table table-striped">
            <thead>
                <th>Reporting Period</th>
                <th>NFT Number</th>
                <th>Report</th>
                <th>Province</th>
                <th>Fund Source</th>
                <th>UACS Object Code</th>
                <th>General Ledger</th>
                <th class='number'>Withdrawals</th>
                <th class='number'>tax1</th>
                <th class='number'>tax2</th>
            </thead>
            <tbody>

                <?php
                $total_withdrawal = 0;
                $total_vat_nonvat = 0;
                $total_ewt = 0;
                foreach ($model->liquidationEntries as $val) {

                    $total_withdrawal += $val->withdrawals;
                    $total_vat_nonvat += $val->vat_nonvat;
                    $total_ewt += $val->ewt_goods_services;
                    echo "<tr>
                <td>{$val->reporting_period}</td>
                <td>{$val->advances->nft_number}</td>
                <td>{$val->advances->report_type}</td>
                <td>{$val->advances->province}</td>
                <td>{$val->advances->particular}</td>
                <td>{$val->chartOfAccount->uacs}</td>
                <td>{$val->chartOfAccount->general_ledger}</td>
                <td class='number'>" . number_format($val->withdrawals, 2) . "</td>
                <td class='number'>" . number_format($val->vat_nonvat, 2) . "</td>
                <td class='number'>" . number_format($val->ewt_goods_services, 2) . "</td>
                
                </tr>";
                }

                echo "<tr>
                <td colspan='7' style='text-align:center;font-weight:bold;'>Total</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_withdrawal, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_vat_nonvat, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_ewt, 2) . "</td>
                </tr>";
                ?>
            </tbody>
        </table>
    </div>

</div>

<style>
    .number {
        text-align: right;
    }
</style>