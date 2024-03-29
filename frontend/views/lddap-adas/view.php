<?php

use yii\helpers\Html;
use app\models\Employee;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LddapAdas */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Lddap Adas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$emp = new Employee();
$cerified_correct_by = !empty($model->fk_certified_correct_by) ?  $emp->getEmployeeById($model->fk_certified_correct_by) : [];
$approved_by = !empty($model->fk_approved_by) ? $emp->getEmployeeById($model->fk_approved_by) : [];
$accounting_head = !empty($model->fk_accounting_head) ?  $accounting_head =  $emp->getEmployeeById($model->fk_accounting_head) : [];

?>
<div class="lddap-adas-view">
    <div class="container">
        <p>
            <?= Html::a('<id class="fa fa-pencil-alt"></id> add Signatories', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) ?>
            <?= Html::a('Cash Disbursement', ['cash-disbursement/view', 'id' => $model->fk_cash_disbursement_id], ['class' => 'btn btn-link']) ?>
        </p>
        <table>

            <tbody>
                <tr>
                    <th class="no-bdr ctr" colspan="8">LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE-ADVICE TO DEBIT ACCOUNTS (LDDAP-ADA)</th>
                </tr>
                <tr>
                    <th class="no-bdr">DEPARTMENT</th>
                    <th class="no-bdr" colspan="4">: DEPARTMENT OF TRADE AND INDUSTRY</th>
                    <th class="no-bdr">LDDAP-ADA#</th>
                    <th class="no-bdr" colspan="2">: <?= $model->serial_number ?></th>
                </tr>
                <tr>
                    <th class="no-bdr">AGENCY</th>
                    <th class="no-bdr" colspan="4">: D5138</th>
                    <th class="no-bdr">MDS Account No.</th>
                    <th class="no-bdr" colspan="2">: <?= $model->cashDisbursement->book->account_number ?? '' ?></th>
                </tr>
                <tr>
                    <th class="no-bdr">OPERATING UNIT</th>
                    <th class="no-bdr" colspan="7">: Regional Office CARAGA/03</th>
                </tr>
                <tr>
                    <th class="no-bdr">FUND CODE</th>
                    <th class="no-bdr" colspan="7">:<?= $model->cashDisbursement->book->name ?? '' ?></th>
                </tr>
                <tr>
                    <th class="no-bdr" colspan="">MDS-GSB BRANCH/MDS SUB ACCOUNT NO.</th>
                    <th class="no-bdr" colspan="7">: LBP-Butuan Branch/ 2036-90014-1</th>
                </tr>
                <tr>
                    <th colspan="8" class="ctr">I. LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE (LDDAP)</th>
                </tr>
                <tr>
                    <th>Creditor</th>
                    <th rowspan="2">PREFERRED SERVICING BANKS/ SAVINGS/ CURRENT ACCT. NO.</th>
                    <th rowspan="2">Obligation Request No.</th>
                    <th rowspan="2">ALLOTMENT CLASS (per UACS)</th>
                    <th colspan="3">In Pesos</th>
                    <th rowspan="2">REMARKS</th>
                </tr>
                <tr>
                    <th>NAME</th>
                    <th> GROSS AMOUNT </th>
                    <th> WITHHOLD-ING TAX </th>
                    <th>NET AMOUNT</th>
                </tr>

                <?php
                $ttlGrs = 0;
                $ttlNet = 0;
                $ttlTax = 0;
                foreach ($cashDetails as $itm) {
                    echo "<tr>
                        <td>{$itm['payee']}</td>
                        <td>{$itm['account_num']}</td>
                        <td>{$itm['orsNums']}</td>
                        <td>{$itm['uacs']}</td>
                        <td class='amt'>" . number_format($itm['grossAmt'], 2) . "</td>
                        <td class='amt'>" . number_format($itm['ttlTax'], 2) . "</td>
                        <td class='amt'>" . number_format($itm['ttlAmtDisbursed'], 2) . "</td>
                        <td></td>
                    </tr>";
                    $ttlGrs += floatval($itm['grossAmt']);
                    $ttlNet += floatval($itm['ttlAmtDisbursed']);
                    $ttlTax += floatval($itm['ttlTax']);
                }
                ?>
                <tr>
                    <th>Sub-total</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="amt"><?= number_format($ttlGrs, 2) ?></th>
                    <th class="amt"><?= number_format($ttlTax, 2) ?></th>
                    <th class="amt"><?= number_format($ttlNet, 2) ?></th>
                    <th></th>
                </tr>
                <tr>
                    <td colspan="8">II: Prior Years A/Ps</td>
                </tr>
                <tr>
                    <th>Sub-total</th>
                    <th></th>
                    <th></th>
                    <th></th>

                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="amt"><?= number_format($ttlGrs, 2) ?></th>
                    <th class="amt"><?= number_format($ttlTax, 2) ?></th>
                    <th class="amt"><?= number_format($ttlNet, 2) ?></th>
                    <th></th>
                </tr>
                <tr>
                    <td colspan="8" class="no-bdr">
                        <div style="width: 50%;float:left;max-height:100%;"> <span>I hereby warrant that the above List of Due and Demandable </span><br>
                            <span>A/Ps was prepared in accordance with existing budgeting,</span><br>
                            <span>accounting and auditing rules and regulations.</span><br>
                            <span>Certified Correct:</span><br>

                        </div>
                        <div style="width: 50%;float:left;max-height:100%;">
                            <span>I hereby assume full responsibility for the veracity and accuracy of the </span><br>
                            <span> listed claims, and the authenticity of the supporting documents as </span><br>
                            <span> submitted by the claimants. </span> <br>
                            <span> Approved: </span> <br>

                        </div>

                    </td>

                </tr>
                <tr>
                    <td colspan="8" class="no-bdr">
                        <div class="signatory">
                            <u><b class="upper-case"><?= !empty($cerified_correct_by['employee_name']) ? $cerified_correct_by['employee_name'] : '' ?></b></u><br>
                            <span><?= !empty($cerified_correct_by['position']) ? $cerified_correct_by['position'] : '' ?></span>
                        </div>
                        <div class="signatory">


                            <u><b class="upper-case"><?= !empty($approved_by['employee_name']) ? $approved_by['employee_name'] : '' ?></b></u><br>
                            <span><?= !empty($approved_by['position']) ? $approved_by['position'] : '' ?></span>
                        </div>

                    </td>
                </tr>
                <tr>
                    <th colspan="8" class="ctr">II. ADVICE TO DEBIT ACCOUNT (ADA) </th>
                </tr>
                <tr>
                    <td colspan="7" class="no-bdr">
                        <span> To MDS-GSB of the Agencys </span><br>
                        <span>Please debit MDS Sub-Account Number: 2036-90014-1 </span><br>
                        <span>Please credit the account of the above listed creditors to cover payment of accounts payable (A/Ps) </span>
                        <br>

                    </td>
                    <th class="amt no-bdr text-center">
                        <br>
                        <br>
                        <br>
                        <?= number_format($ttlNet, 2) ?>
                    </th>
                </tr>
                <tr>
                    <th colspan="8" class="no-bdr">TOTAL AMOUNT:
                        <u>&emsp; <?php echo Yii::$app->memem->convertNumberToWords($ttlNet) . ' Pesos';
                                    $dcl = round(fmod($ttlNet, 1) * 100);
                                    if ($dcl > 0) {
                                        echo ' And ';
                                        echo Yii::$app->memem->convertNumberToWords($dcl);
                                        echo ' Centavos';
                                    }

                                    ?>&emsp;</u>
                    </th>
                </tr>
                <tr>
                    <th colspan="8" class="" style="border-bottom: 0;">Agency Authorized Signatories</th>
                </tr>
                <tr>
                    <td colspan="8" class="no-bdr">

                        <div class='signatory'>
                            <u><b class="upper-case"><?= !empty($accounting_head['employee_name']) ? $accounting_head['employee_name'] : '' ?></b></u><br>
                            <span><?= !empty($accounting_head['position']) ? $accounting_head['position'] : '' ?></span>
                        </div>
                        <div class="signatory">


                            <u><b class="upper-case"><?= !empty($approved_by['employee_name']) ? $approved_by['employee_name'] : '' ?></b></u><br>
                            <span><?= !empty($approved_by['position']) ? $approved_by['position'] : '' ?></span>
                        </div>
                    </td>

                </tr>
                <tr>
                    <th colspan="8" style="border-bottom: 0;"> FOR MDS-GSB USE ONLY: <span style="margin-left: 5rem;">We have debited/ credited above accounts as instructed.</span> </th>
                </tr>
                <tr>

                    <th colspan="8" style="border-top: 0;">
                        <br>
                        <div class='blank-signatory'>
                            <span>Signature verified by</span>
                        </div>
                        <div class='blank-signatory'>
                            <span>Posted by</span>
                        </div>
                        <div class='blank-signatory'>
                            <span>Checked by</span>
                        </div>
                        <div class='blank-signatory'>
                            <span>Approved by</span>
                        </div>
                    </th>

                </tr>
                <tr>
                    <td colspan="4" class="no-bdr" style="padding: 0;"></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"> <span>LDDAP-ADA No.</span></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"> <span>:<?= $model->serial_number ?></span></td>
                </tr>
                <tr>
                    <td colspan="4" class="no-bdr" style="padding: 0;"></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"> <span>Date of Issue</span><br></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"> <span>:<?= DateTime::createFromFormat('Y-m-d', $model->cashDisbursement->issuance_date)->format('F d, Y') ?></span></td>
                </tr>
                <tr>
                    <td colspan="4" class="no-bdr" style="padding: 0;"></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"> <span>NCA No. / NCA Date</span><br></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;">:</td>
                </tr>
                <tr>
                    <td colspan="4" class="no-bdr" style="padding: 0;"></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"><span>CHECK#</span><br></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"><span>:<?= $model->cashDisbursement->check_or_ada_no ?></span></td>
                </tr>
                <tr>
                    <td colspan="4" class="no-bdr" style="padding: 0;"></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;"> <span>ACIC #</span><br></td>
                    <td colspan="2" class="no-bdr" style="padding: 0;">:<?= $model->cashDisbursement->getAcicNum() ?? '' ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<style>
    .container {
        padding: 2rem;
        background-color: white;
    }

    th,
    td,
    table {
        padding: 1rem;
        border: 1px solid black;
    }

    .no-bdr {
        border: 0;
    }

    .upper-case {
        text-transform: uppercase;
    }

    .signatory {
        width: 50%;
        float: left;
        max-height: 100%;
        text-align: center;
        margin-top: 3rem;
    }

    .blank-signatory {
        width: 25%;
        float: left;
        text-align: center;
        margin-top: 4rem;
    }

    .amt {
        text-align: right;
    }

    /* .no-bdr {
        border: 0;
    } */

    .ctr {
        text-align: center;
    }

    @media print {

        th,
        td {
            /* font-size: 13px; */
            padding: 4px;
        }

        .main-footer {
            display: none;
        }
    }
</style>
