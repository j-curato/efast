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

$this->title = 'Trial Balance';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();

    ?>

    <div class="container panel panel-default">

        <?php Pjax::begin(['id' => 'journal', 'clientOptions' => ['method' => 'POST']]) ?>

        <table style="margin-top:30px">
            <tbody>

                <tr>

                    <td colspan="5" style="text-align:center">
                        <div>
                            ___________________
                        </div>
                        <h5>
                            entity name
                        </h5>
                        <h4>
                            DISBURSEMENT VOUCHER
                        </h4>

                    </td>
                    <td colspan="1">
                        <div>
                            <span>Fund Cluster:</span>
                            <span>Fund 01</span>
                        </div>
                        <div>
                            <span>Date:</span>
                            <span>15-12-12</span>
                        </div>
                        <div>
                            <span>DV Number:</span>
                            <span>124123</span>
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
                                    <h6>MDS Check</h6>
                                </div>
                            </div>
                            <div style="display:flex">
                                <div class="checkbox"></div>
                                <h6>Commercial Check</h6>
                            </div>
                            <div style="display:flex">
                                <div class="checkbox"></div>
                                <h6>ADA</h6>
                            </div>
                            <div style="display:flex">
                                <div class="checkbox"></div>
                                <h6>Others (Please specify)</h6>
                            </div>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="1" class="head">
                        payee
                    </td>
                    <td colspan="5">
                        rqwrqweqwe qwe qwe qwe qwe qwe
                    </td>
                </tr>

                <tr class="header">
                    <td colspan="1" class="head">
                        Address
                    </td>
                    <td colspan="5">
                        qwe
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
                    <td class="head" style="text-align: center;" colspan="5">
                        Amount Due
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="6">
                        <h6 style="margin:0">A: Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</h6>
                        <h5 style="text-align: center; margin:4rem">
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

                    <td colspan="3">Account Title</td>
                    <td>UACS Code</td>
                    <td>Debit</td>
                    <td>Credit</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="head">
                            C. Certified
                        </h6>
                        <div style="display: flex;">
                            <div class="checkbox"></div>
                            <h6>Cash Available</h6>
                        </div>
                        <div style="display: flex;">
                            <div class="checkbox"></div>
                            <h6> Subject to Authority to Debit Account (when applicable)</h6>
                        </div>
                        <div style="display: flex;">
                            <div class="checkbox"></div>
                            <h6> Supporting documents complete and amount claimed </h6>
                        </div>
                    </td>
                    <td colspan="3">
                        <h6 style="margin:0" class="head">D:Approved for Payment</h6>
                        <h5 style="text-align: center; margin:4rem">
                            qwe
                        </h5>
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
                    <td>123123 123 123</td>
                    <td></td>
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
                    <td>q</td>
                    <td>Date :</td>
                    <td colspan="">Bank Name & Account Number:</td>
                    <td></td>

                </tr>
                <tr>
                    <td>
                        Signature :
                    </td>
                    <td>
                        qq
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
        <?php Pjax::end() ?>

    </div>
    <style>
        .head {
            font-weight: bold;
        }

        .checkbox {

            margin-right: 4px;
            margin-top: 6px;
            height: 20px;
            width: 20px;
            border: 1px solid black;
        }

        td {
            border: 1px solid black;
            padding: 1rem;
            white-space: nowrap;
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

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
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