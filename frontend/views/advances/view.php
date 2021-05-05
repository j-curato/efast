<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="advances-view">

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

    <table class="table table-striped">
        <thead>

            <th>DV Number</th>
            <th>Check Number</th>
            <th>ADA Number</th>
            <th>Check Date</th>
            <th>Payee</th>
            <th>Object Code</th>
            <th> Account Title</th>
            <th style="text-align: right;">Amount</th>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($model->advancesEntries as $val) {
                echo "
                <tr>
                <td>
                {$val->cashDisbursement->dvAucs->dv_number}
                </td>
                <td>
                {$val->cashDisbursement->check_or_ada_no}
                </td>
                <td>
                {$val->cashDisbursement->ada_number}
                </td>
                <td>
                {$val->cashDisbursement->issuance_date}
                </td>
                <td>
                {$val->cashDisbursement->dvAucs->payee->account_name}
                </td>
                <td>
                {$val->subAccount1->object_code}
                </td>
                <td>
                {$val->subAccount1->name}
                </td>
                <td style='text-align:right'>
                {$val->amount}
                </td>

                </tr>
                ";
                $total += $val->amount;
            }

            ?>

            <tr>

                <td colspan="7" style="text-align: center;font-weight:bold">Total</td>
                <td style="text-align: right;"> <?php echo number_format($total,2); ?></td>
            </tr>

        </tbody>
    </table>

</div>