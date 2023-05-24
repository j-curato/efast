<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LddapAdas */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lddap Adas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lddap-adas-view" style="background-color: white;">
    <div class="container">
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
                    <th class="no-bdr">MDS 101 ACCT#</th>
                    <th class="no-bdr" colspan="2">: 2036-9001-41</th>
                </tr>
                <tr>
                    <th class="no-bdr">OPERATING UNIT</th>
                    <th class="no-bdr" colspan="7">: Regional Office CARAGA/03</th>
                </tr>
                <tr>
                    <th class="no-bdr">FUND CODE</th>
                    <th class="no-bdr" colspan="7">: 101</th>
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

                foreach ($cashDetails as $itm) {
                    echo "<tr>
                        <td>{$itm['payee']}</td>
                        <td></td>
                        <td></td>
                        <td>{$itm['uacs']}</td>
                        <td class='amt'>" . number_format($itm['grossAmt'], 2) . "</td>
                        <td></td>
                        <td class='amt'>" . number_format($itm['grossAmt'], 2) . "</td>
                        <td></td>
                    </tr>";
                }
                ?>
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
                    <td colspan="8">Prior Years A/Ps</td>
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
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td colspan="8">
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
                    <td colspan="8">
                        <div class="signatory">
                            <u><b>CHARLIE C. DECHOS, CPA</b></u><br>
                            <span>Regional Accountant </span>
                        </div>
                        <div class="signatory">

                            <u><b> JOHN VOLTAIRE S. ANCLA, CPA </b></u><br>
                            <span> Chief Administrative Officer </span>
                        </div>

                    </td>
                </tr>
                <tr>
                    <th colspan="8">II. ADVICE TO DEBIT ACCOUNT (ADA) </th>
                </tr>
                <tr>
                    <td colspan="8">
                        <span> To MDS-GSB of the Agencys </span><br>
                        <span>Please debit MDS Sub-Account Number: 2036-90014-1 </span><br>
                        <span>Please credit the account of the above listed creditors to cover payment of accounts payable (A/Ps) </span>

                    </td>
                </tr>
                <tr>
                    <th colspan="8">TOTAL AMOUNT:</th>
                </tr>
                <tr>
                    <th colspan="8">Agency Authorized Signatories</th>
                </tr>
                <tr>
                    <td colspan="8">

                        <div class='signatory'>
                            <u><b>MARRY ANN L. PASCUAL </b></u><br>
                            <span>Administrative Officer V </span>
                        </div>
                        <div class="signatory">
                            <u><b> JOHN VOLTAIRE S. ANCLA, CPA </b></u><br>
                            <span> Chief Administrative Officer </span>
                        </div>
                    </td>

                </tr>
                <tr>
                    <th>FOR MDS-GSB USE ONLY:</th>
                    <th colspan="7">We have debited/ credited above accounts as instructed.</th>
                </tr>
                <tr>

                    <th colspan="8">
                        <div class='blnk-sgnatory'>
                            <span>Signature verified by:</span>
                        </div>
                        <div class='blnk-sgnatory'>
                            <span>Posted by:</span>
                        </div>
                        <div class='blnk-sgnatory'>
                            <span>Checked by:</span>
                        </div>
                        <div class='blnk-sgnatory'>
                            <span>Approved by:</span>
                        </div>
                    </th>

                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2">
                        <span>LDDAP-ADA No.</span><br>
                        <span>Date of Issue</span><br>
                        <span>NCA No. / NCA Date</span><br>
                        <span>CHECK#</span><br>
                        <span>ACIC #</span>

                    </td>
                    <td colspan="2">
                        <span><?= $model->cashDisbursement->check_or_ada_no ?></span><br>
                        <span></span><br>
                        <span></span><br>
                        <span></span><br>
                        <span></span>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<style>
    th,
    td,
    table {
        padding: 1rem;
        border: 1px solid black;
    }

    .signatory {
        width: 50%;
        float: left;
        max-height: 100%;
        text-align: center;
        margin-top: 3rem;
    }

    .blnk-sgnatory {
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
            font-size: 10px;
            padding: 4px;
        }
    }
</style>