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

                    <td colspan="5" style="text-align: center;">
                        <h4 class="head">
                            OBLIGATION REQUEST AND STATUS
                        </h4>
                        <div>
                            _____________________________
                        </div>
                        <h5 class="head">
                            entity name
                        </h5>

                    </td>
                    <td colspan="3">
                        <div>
                            <span>Serial Number:</span>
                            <span>124123</span>
                        </div>
                        <div>
                            <span>Date:</span>
                            <span>15-12-12</span>
                        </div>
                        <div>
                            <span>Fund Cluster:</span>
                            <span>Fund 01</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        payee
                    </td>
                    <td colspan="6">
                        rqwrqweqwe qwe qwe qwe qwe qwe
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Office
                    </td>
                    <td colspan="6">
                        qweqw
                    </td>
                </tr>
                <tr class="header">
                    <td colspan="2">
                        Address
                    </td>
                    <td colspan="6">
                        qwe
                    </td>
                </tr>
                <tr class="header">
                    <td colspan="2">
                        Responsibility Center
                    </td>
                    <td colspan="3">
                        Particulars
                    </td>
                    <td colspan="1">
                        MFO/PAP
                    </td>
                    <td colspan="1">
                        UACS Object Code
                    </td>
                    <td colspan="1">
                        Amount
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    </td>
                    <td colspan="3">
                    </td>
                    <td colspan="1">
                    </td>
                    <td colspan="1">
                    </td>
                    <td colspan="1">
                    </td>
                </tr>


                <tr style="border-top:1px solid black">
                    <td colspan="4">
                        <div>
                            <span class="head">A. Certified: </span>
                            Charges to appropriation/alloment arenecessary,
                            lawful and under my direct supervision;and supporting documents
                            valid, proper and legal

                        </div>
                        <div>
                            <span>Signature:</span>
                            <span>______________</span>
                        </div>
                        <div>
                            <span>Printed Name:</span>
                            <span>______________</span>
                        </div>
                        <div>
                            <span>Position:</span>
                            <span>______________</span>
                            <h6>Head, Budget Division/Unit/Authorized Representative</h6>
                        </div>
                        <div>
                            <span>Date:</span>
                            <span>______________</span>
                        </div>

                    </td>
                    <td colspan="4">

                        <div>
                            <span class="head"> B. Certified:</span>
                            Allotment available and obligated
                            for the purpose/adjustment necessary as
                            indicated above
                        </div>
                        <div>
                            <span>Signature:</span>
                            <span>______________</span>
                        </div>
                        <div>
                            <span>Printed Name:</span>
                            <span>______________</span>
                        </div>
                        <div>
                            <span>Position:</span>
                            <span>______________</span>
                            <h6>Head, Budget Division/Unit/Authorized Representative</h6>
                        </div>
                        <div>
                            <span>Date:</span>
                            <span>______________</span>
                        </div>

                    </td>


                </tr>
                <tr>
                    <td colspan="8">
                        <h6 class="head">
                            STATUS OF OBLIGATION
                        </h6>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="head">
                        REFERENCE

                    </td>
                    <td colspan="4" class="head">
                        AMOUNT
                    </td>
                </tr>
                <tr>
                    <td rowspan="2">date</td>
                    <td rowspan="2">particular</td>
                    <td rowspan="2">ORS/JEV/Check/ADA/TRA No.</td>
                    <td rowspan="2">Obligation</td>
                    <td rowspan="2">Payable</td>
                    <td rowspan="2">Payment</td>
                    <td colspan="2">balance</td>
                </tr>
                <tr>
                    <td>
                        Not Yet Due
                    </td>
                    <td>
                        Due and Demandable
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>



            </tbody>
        </table>
        <?php Pjax::end() ?>

    </div>
    <style>
        .head {
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid black;
            padding: 1rem;
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