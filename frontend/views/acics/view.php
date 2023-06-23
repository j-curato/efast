<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Acics */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Acics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="accics-view">
    <div class="container">


        <p>
            <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </p>

        <table>
            <tr>
                <td colspan="7" class="ctr no-bdr" style="padding-top:4rem">
                    <b><u>DEPARTMENT OF TRADE AND INDUSTRY - CARAGA</u></b><br>
                    <span>Entity Name</span>
                </td>
            </tr>
            <tr>
                <th colspan="7" class="ctr no-bdr" style="padding-top: 4em;padding-bottom:4em;">ADVICE OF CHECKS ISSUED AND CANCELLED</th>
            </tr>
            <tr>
                <th colspan="7" class="no-bdr">TO: The Bank Manager</th>
            </tr>
            <tr>
                <td colspan="3" class="no-bdr">
                    &emsp; &emsp; <span>________________________________</span><br>
                    &emsp; &emsp; <span>________________________________</span>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
                <td colspan="2" class="no-bdr">
                    <span> Bank Account No.: <b><u>&emsp;<?= $model->book->account_number ?? '' ?> &emsp;</u></b></span> <br>
                    <span>Date: <b> <u>&emsp;&emsp;<?= DateTime::createFromFormat('Y-m-d', $model->date_issued)->format('F d, Y') ?>&emsp;&emsp;</u></b></span>
                    <br>
                    <br>
                </td>
                <td colspan="" class="no-bdr" style="min-width:130px">
                    <span>ACIC No.</span><br>
                    <span>Organization Code:</span><br>
                    <span>Fund Cluster</span><br>
                    <span>Area Code</span><br>
                    <span>NCA No.</span><br>
                </td>
                <td colspan="" class="no-bdr">
                    <span><u> <?= $model->serial_number ?></u></span><br>
                    <span>_________________</span><br>
                    <span>_________________</span><br>
                    <span>_________________</span><br>
                    <span>_________________</span><br>

                </td>
            </tr>
            <tr>
                <th rowspan='2' class="ctr">CHECK NO.</th>
                <th rowspan='2' class="ctr">DATE OF ISSUE</th>
                <th rowspan='2' class="ctr">PAYEE</th>
                <th rowspan='2' class="ctr">AMOUNT</th>
                <th rowspan='2' class="ctr">UACS OBJECT CODE</th>
                <th colspan="2" class="ctr">FOR GSB USE ONLY</th>
            </tr>
            <tr>
                <td class="ctr">DATE NEGTD.</td>
                <td class="ctr">REMARKS</td>
            </tr>
            <?php
            $checkCnt = YIi::$app->db->createCommand("SELECT COUNT(acics_cash_items.fk_acic_id) as cnt FROM acics_cash_items WHERE acics_cash_items.fk_acic_id = :id AND acics_cash_items.is_deleted = 0")
                ->bindValue(':id', $model->id)
                ->queryScalar();
            $cashItemsTtlAmt = 0;
            $checkNumCnt = count(array_unique(array_column($cashItems, 'check_or_ada_no')));
            foreach ($cashItems as $itm) {
                $cashItemsTtlAmt += floatval($itm['ttlAmtDisbursed']);
                echo "<tr>
                <td>{$itm['check_or_ada_no']}</td>
                <td>{$itm['issuance_date']}</td>
                <td>{$itm['payee']}</td>
                <td class='amt'>" . number_format($itm['ttlAmtDisbursed'], 2) . "</td>
                <td>{$itm['uacs']}-{$itm['general_ledger']}</td>
                <td></td>
                <td></td>
            </tr>";
            }
            ?>
            <tr>
                <th></th>
                <th></th>
                <th>Total ACIC Amount</th>
                <th class="amt"><?= number_format($cashItemsTtlAmt, 2) ?></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td colspan="7">
                    Total number of checks: <b><u>&emsp;&emsp;<?= $checkCnt ?>&emsp;&emsp;</u></b> Amount in Words:
                    <b><u>&emsp;
                            <?php
                            echo Yii::$app->memem->convertNumberToWords($cashItemsTtlAmt);
                            echo ' Pesos ';
                            $dcl = round(fmod($cashItemsTtlAmt, 1) * 100);
                            if ($dcl > 0) {
                                echo ' And ';
                                echo Yii::$app->memem->convertNumberToWords($dcl);
                                echo ' Centavos';
                            }
                            ?>&emsp;
                        </u></b>

                </td>
            </tr>
        </table>
        <!-- <p style='page-break-after:always;'></p> -->
        <table style="margin-top: 1.5rem;">


            <tbody>
                <tr>
                    <th colspan="3" class="ctr">CANCELLED CHECK</th>
                    <td rowspan="12" class="asig ctr">
                        <span class="flt-lft">Certified Correct By:</span><br><br><br>
                        <span>___________________________</span><br>
                        <span>Signature over Printed Name of Disbursing <br> Officer/Cashier/Head of Cash /Treasurey <br> Unit</span><br><br>
                        <span class="flt-lft">Approved By:</span><br><br>
                        <span>___________________________</span><br>
                        <span>Signature over Printed Name of Head of Office/Unit or his/her authorized <br> representative</span>
                    </td>
                    <td rowspan="12" style="width:.5%"></td>
                    <td rowspan="12" class="asig ctr">
                        <span class="flt-lft">Recieved by:</span><br><br><br>
                        <span>___________________________</span><br>
                        <span>Signature over Printed Name of GSB <br> personnel who received the ACIC</span><br><br><br>
                        <span class="flt-lft">Delivered By:</span><br><br>
                        <span>___________________________</span><br>
                        <span>Signature over Printed Name of Agency <br> personnel who delivered the ACIC to the <br> GSB</span>
                    </td>
                </tr>
                <tr>
                    <td class="ctr" style="width: 15%;">Check No.</td>
                    <td class="ctr" style="width: 10%;">Date Issued</td>
                    <td class="ctr" style="width: 20%;">Remarks</td>
                </tr>
                <?php
                foreach ($cancelledItems as $cItm) {
                    $dteIsue = DateTime::createFromFormat('Y-m-d', $cItm['issuance_date'])->format('F d, Y');
                    echo "<tr>
                            <td class='ctr'>{$cItm['check_or_ada_no']}</td>
                            <td class='ctr'>{$dteIsue}</td>
                            <td></td>
                        </tr>";
                }
                for ($i = count($cancelledItems); $i < 10; $i++) {
                    echo "<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
                }
                ?>
                <tr>
                    <td colspan="6" style="height: 1.5rem;"></td>
                </tr>
                <tr>
                    <th colspan="6" class="ctr">REPORT SUMMARY</th>
                </tr>
                <tr>
                    <td colspan="6">
                        <span>Number of ACIC(s)</span> <br>
                        <span>Grand Total:</span> <br>
                        <span>Amount in Words</span> <br>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="ctr">
                        <div style='width:50%' class="flt-lft">
                            <span class="flt-lft">Certified Correct by:</span><br><br>
                            <u><b>MARRY ANN L. PASCUAL</b></u><br>
                            <span>Administrative Officer V</span><br>
                            <span>Signature over Printed Name of Disbursing <br> Officer/Cashier/Head of Cash/ Treasury Unit</span>
                        </div>
                        <div style='width:50%' class="flt-lft">
                            <span class="flt-lft">Received by:</span><br><br>
                            <span>__________________________________</span><br>
                            <span>Signature over Printed Name of GSB personnel who <br> received the ACIC</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="ctr">
                        <div style='width:50%' class="flt-lft">
                            <span class="flt-lft">Approved by:</span><br><br>
                            <u><b>GAY A. TIDALGO, CESO IV</b></u><br>
                            <span>Region Director</span><br>
                            <span>Signature over Printed Name of Head of <br> Office/Unit or his/her authorized representative</span>
                        </div>
                        <div style='width:50%' class="flt-lft">
                            <span class="flt-lft">Delivered by:</span><br><br>
                            <span>__________________________________</span><br>
                            <span>Signature over Printed Name of Agency personnel who <br> delivered the ACIC to the GSB</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<style>
    .asig {
        width: 26%;
    }

    .amt {
        text-align: right;
    }

    .flt-lft {
        float: left;
    }

    .container {
        padding: 2rem;
        background-color: white;
    }

    td,
    th,
    table {
        padding: 10px;
        border: 1px solid black;
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

    @media print {

        .main-footer,
        .btn {
            display: none;
        }

        .container {
            padding: 0;
        }

        th,
        td {
            font-size: 10px;
            padding: 4px;
        }

        table {
            width: 100%;
        }
    }
</style>