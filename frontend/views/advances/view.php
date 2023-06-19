<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */

$this->title = $model->nft_number;
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="advances-view">

    <?php if (Yii::$app->user->can('create_advances')) { ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </p>
    <?php } ?>
    <div class="con">

        <table class="table table-striped">
            <thead>

                <th>Fund Source</th>
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
                foreach ($model->advancesEntries as $i => $val) {
                    $payee = !empty($val->cashDisbursement->dvAucs->payee_id) ? $val->cashDisbursement->dvAucs->payee->account_name : '';
                    if ($val->is_deleted !== 1) {
                        $dv_number = !empty($val->cashDisbursement->dvAucs) ? $val->cashDisbursement->dvAucs->dv_number : '';
                        $check_or_ada_no = !empty($val->cashDisbursement->check_or_ada_no) ? $val->cashDisbursement->check_or_ada_no : '';
                        $ada_number = !empty($val->cashDisbursement->ada_number) ? $val->cashDisbursement->ada_number : '';
                        $issuance_date = !empty($val->cashDisbursement->issuance_date) ? $val->cashDisbursement->issuance_date : '';
                        echo "
                        <tr>

                        <td>
                        {$val->id}
                        {$val->fund_source}
                        </td>
                        <td>
                        {$dv_number}
                        </td>
                        <td>
                        {$check_or_ada_no}
                        </td>
                        <td>
                        {$ada_number}
                        </td>
                        <td>
                        {$issuance_date}
                        </td>
                        <td>
                        {$payee}
                        </td>
                        <td>
                        {$val->accountingCode->object_code}
                        </td>
                        <td>
                        {$val->accountingCode->account_title}
                        </td>
                        <td style='text-align:right'>
                    
                        " . number_format($val->amount, 2) . "
                        </td>

                        </tr>
                    ";
                        $total += $val->amount;
                    }
                }

                ?>

                <tr>

                    <td colspan="8" style="text-align: center;font-weight:bold">Total</td>
                    <td style="text-align: right;font-weigth:bold"> <?php echo number_format($total, 2); ?></td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
<style>
    .con {
        background-color: white;
        padding: 15px;
    }
</style>