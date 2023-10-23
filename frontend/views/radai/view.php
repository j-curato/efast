<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Radai */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Radais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$checkNumbers = implode(',', ArrayHelper::getColumn($model->getRadaiItemsCheckNumbers(), 'check_or_ada_no'));
?>
<div class="radai-view ">
    <p>
        <?= Yii::$app->user->can('update_radai') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
    </p>
    <table>
        <tr>
            <th colspan="9" class="ctr no-bdr">
                <span style="font-size:larger">REPORT OF ADVICE TO DEBIT ACCOUNT ISSUED</span><br>
                <span>Period Covered: <u> &emsp;<?= DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y') ?? '' ?> &emsp;</u></span>
                <br>
                <br>
            </th>
        </tr>
        <tr>
            <th colspan="3" class="no-bdr">
                <span>Entiti Name:</span><br>
                <span>Fund Cluster:</span><br>
                <span>Bank Name/Account No.:</span><br>
                <span>Check No/s. :</span><br>
            </th>
            <th colspan="3" class="no-bdr">

                <u>DEPARTMENT OF TRADE AND INDUSTRY</u><br>
                <u> &emsp;<?= $model->book->name ?? '' ?> &emsp;</u><br>
                <u> &emsp;<?= $model->book->account_number ?? ''; ?> &emsp;</u><br>
                <u><?= $checkNumbers ?></u>
            </th>
            <th colspan="3" class="no-bdr">
                <span>Report No.: <u>&emsp; <?= $model->serial_number ?> &emsp;</u></span><br>
                <span>Sheet No.:__________</span><br>
            </th>


        </tr>

        <tr>
            <th class="ctr" colspan="2">ADA</th>
            <th class="ctr" rowspan="2">DV/Payroll No.</th>
            <th class="ctr" rowspan="2">ORS/BURS No.</th>
            <th class="ctr" rowspan="2">Responsibility Center Code</th>
            <th class="ctr" rowspan="2">Payee</th>
            <th class="ctr" rowspan="2">UACS Object Code</th>
            <th class="ctr" rowspan="2">Nature of Payment</th>
            <th class="ctr" rowspan="2">Amount</th>
        </tr>
        <tr>
            <th class="ctr">Date</th>
            <th class="ctr">Serial No.</th>
        </tr>

        <?php
        $total = 0;
        foreach ($model->getItemsPerDv() as $itm) {
            echo "<tr>
                <td>{$itm['check_date']}</td>
                <td>{$itm['lddap_ada_number']}</td>
                <td>{$itm['dv_number']}</td>
                <td>{$itm['orsNums']}</td>
                <td></td>
                <td>{$itm['payee']}</td>
                <td>{$itm['uacs']}</td>
                <td>{$itm['mode_of_payment_name']}</td>
                <td class='amt'>" . number_format($itm['ttlAmtDisbursed'], 2) . "</td>
            </tr>";
            $total += floatval($itm['ttlAmtDisbursed']);
        }
        ?>
        <tr>
            <th colspan="8" class="ctr">Total</th>
            <th class="amt"><?= number_format($total, 2) ?></th>
        </tr>
        <tr>
            <td colspan="9" class="ctr">
                <br>
                <b>C E R T I F I C A T I O N</b><br>
                <span> I hereby certify on my official oath that this Report of Checks Issued in _________ sheet(s) is a full, true and correct </span><br>
                <span> statement of all checks issued by me during the period stated above for which Check Nos. ___________ to _____________ inclusive, </span><br>
                <span> were actually issued by me in payment for obligations shown in the attached disbursement vouchers/payroll. </span><br><br><br>
                <u><b>MARRY ANN L. PASCUAL</b></u><br>

                <br>
                <span>Name and Signature of Disbursing Officer/Cashier</span> <br><br>
                <span>Administrative Officer V</span><br>
                <span>Official Designation</span><br><br>
                <span>_______________________</span><br>
                <span>Date</span><br>

            </td>
        </tr>
    </table>


</div>
<style>
    .radai-view {
        background-color: white;
        padding: 3rem;
    }

    th,
    td {
        border: 1px solid black;
        padding: 2rem;
    }

    table {
        width: 100%;
    }

    .ctr {
        text-align: center;
    }

    .amt {
        text-align: right;
    }

    .no-bdr {
        border: 0;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }

        th,
        td {
            padding: 5px;
        }
    }
</style>