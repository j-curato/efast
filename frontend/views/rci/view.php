<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Rci */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Rcis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rci-view">




    <div class="container">
        <p>
            <?= Yii::$app->user->can('update_rci') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => ' btn btn-primary']) : '' ?>
        </p>
        <table>
            <thead>

                <tr>
                    <th colspan="9" class="ctr">
                        <span style="font-size:larger">REPORT OF CHECKS ISSUED</span><br>
                        <span>Period Covered: <u>&emsp; <?= DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y') ?> &emsp;</u></span>
                    </th>
                </tr>
                <tr>
                    <th colspan="6">

                        <span> Entity Name:</span> <u>&emsp; DEPARTMENT OF TRADE AND INDUSTRY&emsp;</u><br>
                        <span> Fund Cluster:</span> <u>&emsp; <?= $model->book->name ?? '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;' ?> &emsp;</u><br>
                        <span> Bank Name/ Account No.:</span><u>&emsp; <?= $model->book->account_number ?? '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;' ?> &emsp;<br>


                    </th>
                    <th colspan="3">

                        <span>Report No: <u>&emsp;<?= $model->serial_number ?? '' ?>&emsp;</u> </span><br>
                        <span> Sheet No:______________</span><br>
                    </th>
                </tr>

                <tr>
                    <th class='ctr' colspan="2">Check</th>
                    <th class='ctr' rowspan="2">DV/Payroll No.</th>
                    <th class='ctr' rowspan="2">ORS/BURS No.</th>
                    <th class='ctr' rowspan="2">Responsibility Center Code</th>
                    <th class='ctr' rowspan="2">Payee</th>
                    <th class='ctr' rowspan="2">UACS Object Code</th>
                    <th class='ctr' rowspan="2">Nature of Payment</th>
                    <th class='ctr' rowspan="2">Amount</th>
                </tr>
                <tr>
                    <td class='ctr'>Date</td>
                    <td class='ctr'>Serial No.</td>
                </tr>
            </thead>

            <?php
            $total = 0;
            foreach ($items as $itm) {
                $total  += floatval($itm['ttlAmtDisbursed']);
                echo "<tr>
                        <td>{$itm['issuance_date']}</td>
                        <td>{$itm['check_or_ada_no']}</td>
                        <td>{$itm['dv_number']}</td>
                        <td>{$itm['orsNums']}</td>
                        <td></td>
                        <td>{$itm['payee']}</td>
                        <td>{$itm['uacs']}</td>
                        <td>{$itm['mode_name']}</td>
                        <td class='amt'>" . number_format($itm['ttlAmtDisbursed'], 2) . "</td>
                    </tr>";
            }
            for ($i = count($items); $i < 10; $i++) {
                echo "<tr>
                    <td><br></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>";
            }
            ?>
            <tr>
                <th colspan="8" class="ctr">Total</th>
                <th class="amt"><?= number_format($total, 2) ?></th>
            </tr>
            <tr>
                <td colspan="9" class="ctr">
                    <b>C E R T I F I C A T I O N</b><br>
                    <span> I hereby certify on my official oath that this Report of Checks Issued in _________ sheet(s) is a full, true and correct </span><br>
                    <span> statement of all checks issued by me during the period stated above for which Check Nos. ___________ to _____________ inclusive, </span><br>
                    <span> were actually issued by me in payment for obligations shown in the attached disbursement vouchers/payroll. </span><br><br><br>
                    <span> <u>&emsp;<b>MARRY ANN L. PASCUAL </b>&emsp;</u><br></span><br>
                    <span>Name and Signature of Disbursing Officer/Cashier</span> <br><br>
                    <span><u>&emsp;Administrative Officer V&emsp;</u></span><br>
                    <span>Official Designation</span><br><br>
                    <span>_______________________</span><br>
                    <span>Date</span><br>

                </td>
            </tr>
        </table>
    </div>

</div>
<style>
    .amt {
        text-align: right;
    }

    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    .ctr {
        text-align: center;
    }

    .container {
        background-color: white;
        padding: 2rem;
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