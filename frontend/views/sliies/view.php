<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\i18n\Formatter;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sliies */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Sliies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$ps = !empty($cashDetails['5010000000']['total']) ? $cashDetails['5010000000']['total'] : 0;
$mooe = !empty($cashDetails['5020000000']['total']) ? $cashDetails['5020000000']['total'] : 0;
$co = !empty($cashDetails['5060000000']['total']) ? $cashDetails['5060000000']['total'] : 0;
$ttl  = $ps + $mooe + $co;
$ttlDvs = array_sum(array_column($cashDetails, 'dv_count'));

$chkNo = $model->cashDisbursement->check_or_ada_no ?? '';
$adaNo = $model->cashDisbursement->ada_number ?? '';
$rowDta  = "<tr>
<td>
    Check#: $chkNo<br>
    ADA#: $adaNo
</td>
<td> {$model->cashDisbursement->issuance_date} </td>
<td class='amt'> " . number_format($ttl, 2) . " </td>
<td class='amt'> " . number_format($ps, 2) . " </td>
<td class='amt'> " . number_format($mooe, 2) . " </td>
<td class='amt'> " . number_format($co, 2) . " </td>
<td></td>
<td colspan='2'></td>
</tr>
<tr>
<th class='ctr' colspan='2'>Total</th>
<td class='amt'> " . number_format($ttl, 2) . " </td>
<td class='amt'> " . number_format($ps, 2) . " </td>
<td class='amt'> " . number_format($mooe, 2) . " </td>
<td class='amt'> " . number_format($co, 2) . " </td>
<td></td>
<td colspan='2'></td>
</tr>";
$blnk = "                <tr>
<th class='ctr' colspan='2'>
    <br><br>
</th>
<td class='amt'></td>
<td class='amt'></td>
<td class='amt'></td>
<td class='amt'></td>
<td></td>
<td colspan='2'></td>
</tr>
<tr>
<th class='ctr' colspan='2'>
    <br>
</th>
<td class='amt'></td>
<td class='amt'></td>
<td class='amt'></td>
<td class='amt'></td>
<td></td>
<td colspan='2'></td>
</tr>";
?>
<div class="sliies-view ">




    <div class="container ">
        <?= Html::a('Cash Disbursement', ['cash-disbursement/view', 'id' => $model->fk_cash_disbursement_id], ['class' => 'btn btn-link']) ?>
        <table>

            <thead>
                <tr>
                    <th class="no-bdr">DEPARTMENT</th>
                    <th colspan="5" class="no-bdr">: DEPARTMENT OF TRADE AND INDUSTRY</th>
                    <th class="no-bdr">SLIIE No.:</th>
                    <th colspan="2" class="no-bdr"> <?= $model->serial_number ?></th>
                </tr>
                <tr>
                    <th class="no-bdr">AGENCY</th>
                    <th colspan="5" class="no-bdr">: D5138</th>
                    <th class="no-bdr">MDS Account No.</th>
                    <th colspan="2" class="no-bdr">: <?= $model->cashDisbursement->book->account_number ?? '' ?></th>
                </tr>
                <tr>
                    <th class="no-bdr">OPERATING UNIT</th>
                    <th colspan="8" class="no-bdr">: Regional Office CARAGA/03</th>
                </tr>
                <tr>
                    <th class="no-bdr">FUND CODE </th>
                    <th colspan="8" class="no-bdr">: 101</th>
                </tr>
                <tr>
                    <th colspan="9" class="no-bdr ctr">
                        <br>
                        Summary of LDDPAP-ADAs Issued and Individual ADA Entries (SLIIE)
                        <br>
                        <br>
                    </th>
                </tr>
                <tr>
                    <th colspan="9" class="no-bdr">
                        <span>To: MR. AUGUSTUS MANUEL E. MANTUA</span><br>
                        &emsp;&nbsp; <span>LAND BANK MANAGER,Butuan Branch</span><br>
                        &emsp; &nbsp;<span>Onghoc Bldg. Montilla Blvd, Butuan City</span>

                    </th>
                </tr>
                <tr>
                    <th rowspan="3" class="ctr">LDDAP-ADA NO.</th>
                    <th rowspan="3" class="ctr">Date of Issue</th>
                    <th colspan="5" class="ctr">Amount</th>
                    <th colspan="2" class="ctr">For GSB Use Only</th>
                </tr>
                <tr>
                    <th rowspan="2" class="ctr">Total</th>
                    <th colspan="4" class="ctr">Allotment/Object Class</th>
                    <th rowspan="2" colspan="2" class="ctr">Remarks</th>
                </tr>
                <tr>
                    <th class="ctr">PS</th>
                    <th class="ctr">MOOE</th>
                    <th class="ctr">CO</th>
                    <th class="ctr">FINEX</th>
                </tr>
            </thead>
            <tbody>
                <?= $model->cashDisbursement->is_cancelled == false ?  $rowDta : $blnk ?>
                <tr>
                    <th colspan="2">
                        No. of pcs of LDDAP-ADA: <u>&emsp; <?= $ttlDvs ?> &emsp; </u>
                    </th>
                    <td colspan="7">
                        <span>Total Amount: <b><u>&emsp; <?= number_format($ttl, 2) ?>&emsp; </u></b></span><br>
                        <span>Amount in Words:
                            <u>
                                <b>&emsp;
                                    <?php
                                    echo Yii::$app->memem->convertNumberToWords($ttl);
                                    echo ' Pesos ';
                                    $dcl = round(fmod($ttl, 1) * 100);
                                    if ($dcl > 0) {
                                        echo ' And ';
                                        echo Yii::$app->memem->convertNumberToWords($dcl);
                                        echo ' Centavos';
                                    }
                                    ?>
                                    &emsp;
                                </b>
                            </u>
                        </span>
                    </td>
                </tr>

                <tr>
                    <th rowspan="3" class="ctr">LDDAP-ADA No.</th>
                    <th rowspan="3" class="ctr">Amount</th>
                    <th rowspan="3" class="ctr">Date Issued</th>
                    <th colspan="6" class="ctr">OF WHICH INVALIDATED ENTRIES OF PREVIOUSLY ISSUED LDDAP-ADAs</th>
                </tr>
                <tr>
                    <th colspan="6" class="ctr">

                        Allotment/Object Class
                    </th>
                </tr>
                <tr>
                    <th class="ctr">PS</th>
                    <th class="ctr">MOOE</th>
                    <th class="ctr">CO</th>
                    <th class="ctr">FE</th>
                    <th class="ctr">TOTAL</th>
                    <th class="ctr">Remarks</th>
                </tr>
                <?= $model->cashDisbursement->is_cancelled == true ?  $rowDta : $blnk ?>
                <tr>
                    <td colspan="9">



                        <div style="width:50%;height:100px;float:left">
                            <div class="ctr">
                                <span style="float:left">
                                    Certified Correct
                                </span>
                                <br>
                                <br>
                                <br>
                                <u><b>MARRY ANN L. PASCUAL</b></u><br>
                                <span>Administrative Officer V</span>
                            </div>
                        </div>
                        <div style="width:50%;height:100px;float:left">
                            <div class="ctr">
                                <span style="float:left;">
                                    Approved:
                                </span>
                                <br>
                                <br>
                                <br>

                                <u><b>GAY A. TIDALGO, CESO IV </b></u><br>
                                <span>Regional Director </span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="no-bdr">
                        <span>TRANSMITTAL INFORMATION</span><br>
                        <br>
                        <span> Delivered by:</span><br><br> <br>

                    </td>
                    <td colspan="3" class="no-bdr">
                        <span>
                            FOR MDS-GSB USE ONLY:
                        </span><br><br>
                        <span>Signature Verified by:</span><br><br><br>


                    </td>
                    <td colspan="3" class="no-bdr">
                        <span>
                            Received by:
                        </span> <br>

                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="ctr no-bdr">
                        <u><b>REGINE MAE O. BITCO, CPA </b></u><br>
                        <span> Accountant II </span>
                    </td>
                    <td colspan="3" class="ctr no-bdr">
                        <span>_____________________________</span>
                        <br>
                        <br>
                    </td>
                    <td colspan="3" class="ctr no-bdr">
                        <span>_____________________________</span>
                        <br>
                        <span>Signature Over Printed Name:</span>
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="foot">
            <br>
            <span>Check No.</span>
            <span><?= $model->cashDisbursement->check_or_ada_no ?></span>
            <br>
            <span>ACIC No.</span>
            <span><?= MyHelper::getCashDisbursementAcicNo($model->fk_cash_disbursement_id) ?></span>
        </div>
    </div>


</div>
<style>
    .container {
        background-color: white;
        padding: 2rem;
    }

    th,
    td,
    table {
        border: 1px solid black;
        padding: 1rem;
    }

    table {
        width: 100%;
    }

    .ctr {
        text-align: center;
    }

    .no-bdr {
        border: 0;
    }

    .amt {
        text-align: right;
    }

    .foot {
        width: 25%;
        text-align: left;
        float: right;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

        .foot {
            font-size: 10px;
        }

        th,
        td {
            font-size: 10px;
            padding: 5px;
        }
    }
</style>