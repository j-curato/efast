<?php

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\ResponsibilityCenter;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DV Form';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();

    ?>


    <div class="container panel panel-default">
        <div style="float:right">
            <span style="font-size: x-small;">
                <?php
                echo $model->dv_number;
                ?>
            </span>
        </div>
        <table style="margin-top:30px">
            <tbody>

                <tr>

                    <td colspan="4" style="text-align:center">
                        <div>
                            <h5 style="font-weight: bold;">Department of Trade and Industry - Caraga</h5>
                        </div>
                        <h5 class="head">
                            ENTITY NAME
                        </h5>
                        <h5 class="head">
                            DISBURSEMENT VOUCHER
                        </h5>

                    </td>
                    <td colspan="2">
                        <div>
                            <span>Fund Cluster:</span>
                            <span>______________</span>
                        </div>
                        <div>
                            <span>Date:</span>
                            <span>__________________</span>
                        </div>
                        <div>
                            <span>DV No.:</span>
                            <span style="font-size: x-small;">
                                <?php
                                echo $model->dv_number;
                                ?>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Mode of Payment
                    </td>
                    <td colspan="5">
                        <div style="display: flex;width:100%;justify-content:space-evenly">
                            <div style="display:flex">
                                <div class="checkbox"></div>
                                <div>
                                    <div class="row">
                                        <div></div>
                                        <span><i class="fa-square-o square-icon"></i>MDS Check</span>
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex">
                                <div class="checkbox"></div>
                                <span><i class="fa-square-o square-icon"></i>Commercial Check</span>
                            </div>
                            <div style="display:flex">
                                <div class="checkbox"></div>
                                <span><i class="fa-square-o square-icon"></i>ADA</span>
                            </div>
                            <div style="display:flex">
                                <div class="checkbox"></div>
                                <span><i class="fa-square-o square-icon"></i>Others (Please specify)</span>
                            </div>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="1" class="head" rowspan="2">
                        Payee
                    </td>
                    <td colspan="3" rowspan="2">
                        <?php echo $model->payee->account_name; ?>
                    </td>
                    <td rowspan="1">
                        TIN/Employee No.
                    </td>
                    <td rowspan="1">
                        ORS/BURS No.
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;"></td>
                    <td></td>
                </tr>

                <tr class="header">
                    <td colspan="1" class="head">
                        Address
                    </td>
                    <td colspan="5">
                    </td>
                </tr>
                <tr>

                    <td colspan="3">
                        Particulars
                    </td>
                    <td>
                        MFO/PAP
                    </td>
                    <td>
                        Responsibility center
                    </td>
                    <td>
                        Amount
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='padding:10px'>
                        <?php echo $model->particular ?>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <?php
                        //  echo number_format($model->gross_amount, 2) 
                        ?>
                    </td>
                </tr>
                <?php
                $x = 0;
                // while ($x < 7) {
                //     echo "
                //     <tr>
                //         <td colspan='3' style='padding:10px'>
                //         </td>
                //         <td>
                //         </td>
                //         <td>
                //         </td>
                //         <td>
                //         </td>
                //   </tr>
                //     ";
                //     $x++;
                // }
                $ors_serial_number = '';
                $total = 0;
                foreach ($model->dvAucsEntries as $val) {
                    $ors_serial_number = !empty($val->process_ors_id) ? $val->processOrs->serial_number : '';
                    $amount = number_format($val->amount_disbursed, 2);
                    $total += $val->amount_disbursed;
                    echo "
                    <tr>
                        <td colspan='3' style='padding:0px'>
                        $ors_serial_number
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td style='padding-left:auto'>
                        $amount
                        </td>
                  </tr>
                    ";
                }

                ?>
                <tr>
                    <td class="head" style="text-align: center; font-size:12px" colspan="5">
                        Amount Due
                    </td>
                    <td>
                        <?php
                        echo number_format($total, 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="padding: 0;">
                        <h6 style="margin:0">A: Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</h6>
                        <h5 style="text-align: center; margin:2rem">
                            Printed Name, Designation and Signature of Supervisor
                        </h5>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <h6 class="head">
                            B. Accounting Entry
                        </h6>
                    </td>
                </tr>
                <tr>
                    <td style='padding:10px' colspan='3'> Account Title</td>
                    <td>UACS Code</td>
                    <td>Debit</td>
                    <td>Credit</td>
                </tr>
                <?php
                $y = 0;
                while ($y < 4) {

                    echo "
                    <tr>
                        <td style='padding:10px' colspan='3'></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    ";
                    $y++;
                }

                ?>
                <tr>
                    <td colspan="3" style="padding:0;">
                        <h6 class="head">
                            C. Certified
                        </h6>


                        <h6><i class="fa-square-o square-icon"></i>Cash Available</h6>
                        <h6><i class="fa-square-o square-icon"></i> Subject to Authority to Debit Account (when applicable)</h6>
                        <h6><i class="fa-square-o square-icon"></i> Supporting documents complete and amount claimed </h6>

                    </td>
                    <td colspan="3" style="padding:0;">
                        <h6 style="margin:0" style="float:left" class="head">D:Approved for Payment</h6>
                        <!-- <h5 style="text-align: center; margin:4rem">
                        </h5> -->

                    </td>
                </tr>
                <tr>

                    <td>Signature</td>
                    <td colspan="2"></td>
                    <td>Signature</td>
                    <td colspan="2"></td>
                </tr>
                <tr>

                    <td>Printed Name</td>
                    <td colspan="2"></td>
                    <td>Printed Name</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>Postion</td>
                    <td colspan="2"></td>
                    <td>Postion</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td colspan="2"></td>
                    <td>Date</td>
                    <td colspan='2'></td>
                </tr>
                <!-- LETTER E -->
                <tr>
                    <td colspan="5" class="head">
                        E. Reciept Payment
                    </td>
                    <td rowspan="2">JEV No.</td>
                </tr>
                <tr>

                    <td>Check/ADA No. :</td>
                    <td style="width:200px"></td>
                    <td>Date :</td>
                    <td colspan="">Bank Name & Account Number:</td>
                    <td></td>

                </tr>
                <tr>
                    <td>
                        Signature :
                    </td>
                    <td>

                    </td>
                    <td>
                        Date :
                    </td>
                    <td>
                        Printed Name:
                    </td>
                    <td></td>

                    <td rowspan="2">
                        Date:
                    </td>
                </tr>
                <tr>
                    <td colspan="5">Official Receipt No. & Date/Other Documents</td>

                </tr>





            </tbody>
        </table>
    </div>

    <style>
        .square-icon {
            font-size: 20px;
        }

        .serial {
            margin-top: 8px;
        }

        .head {
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid black;
            padding: 3px;
            font-size: 15px;
        }

        table {
            margin: 12px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }


        @media print {
            .actions {
                display: none;
            }

            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                text-indent: 1px;
                text-overflow: '';
                border: none;
            }

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                font-size: 10px;
            }

            @page {
                size: auto;
                margin: 0;
                margin-top: 0.5cm;
            }



            .container {
                margin: 0;
                top: 0;
            }

            .entity_name {
                font-size: 5pt;
            }

            td {
                border: 1px solid black;
                padding: 5px;
                font-size: x-small;
                background-color: white;
            }

            .container {

                border: none;
            }


            table {
                page-break-after: auto
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto
            }

            /* thead {
                display: table-header-group
            } */

            .main-footer {
                display: none;
            }
        }
    </style>

</div>


<?php
SweetAlertAsset::register($this);
$script = <<< JS


JS;
$this->registerJs($script);
?>