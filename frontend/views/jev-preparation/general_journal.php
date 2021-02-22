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

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Journal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">



    <?php
    $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
            jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
            chart_of_accounts.general_ledger
            FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
            AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            AND jev_preparation.fund_cluster_code_id =1 AND jev_accounting_entries.chart_of_account_id =1 
            ORDER BY jev_preparation.reporting_period
            ")->queryAll();
    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/sample';
    ?>




    <div class="container panel panel-default">
        <div class="actions " style="bottom: 20px;">



            <div class="col-sm-6">
                <label for="fund"> Fund Cluster Code</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map($fund, 'id', 'name'),
                    'id' => 'fund',
                    'name' => 'fund',
                    'options' => ['placeholder' => 'Select a Fund Cluster Code'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <label for="reporting_period">Reporting Period</label>
                <?php
                echo DatePicker::widget([
                    'id' => 'reporting_period',
                    'name' => 'dp_1',
                    'type' => DatePicker::TYPE_INPUT,
                    'readonly' => true,

                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'minViewMode' => "months",

                    ]
                ]);
                ?>
            </div>

        </div>
        <?php Pjax::begin(['id' => 'journal', 'clientOptions' => ['method' => 'POST']]) ?>
        <table class="table" style="margin-top:30px">
            <thead>
                <tr>
                    <th colspan="6" style="border-bottom: 1px solid black " class="header1">
                        <h5>GENERAL JOURNAL</h5>
                        <h6><?php if (!empty($reporting_period)) {
                                echo date("F Y", strtotime($reporting_period));
                            }
                            ?></h6>
                    </th>
                </tr>
                <tr class="header" style="border: none;">
                    <th colspan="3" style="border: none;">
                        <span>
                            Entity Name:

                        </span>
                        <span>
                            DEPARTMENT OF TRADE AND INDUSTRY - CARAGA

                        </span>
                    </th>

                    <th colspan="3" style="border-bottom:1px solid black">
                        <span>
                            Fund Cluster:

                        </span>
                        <span id="fund_cluster" style="text-align: center;">

                            <?php if (!empty($fund_cluster_code)) {
                                echo $fund_cluster_code;
                            }
                            ?>
                        </span>
                    </th>


                </tr>


                <tr style="border-top:1px solid black;border-bottom:1px solid black " ">
                    <th  rowspan=" 2" class="header2" style="border-top:1px solid black;border-bottom:1px solid black">
                    Date
                    </th>
                    <th rowspan="2" class="header2" style="border-bottom: 1px solid black;">
                        JEV No.
                    </th>
                    <th rowspan="2" class="header2" style="border-bottom: 1px solid black;">
                        Particulars
                    </th>
                    <th rowspan="2" class="header2" style="border-bottom: 1px solid black;">
                        UACS Object Code
                    </th>
                    <th colspan="3" class="header2" style="border-bottom: 1px solid black;">
                        Amount
                    </th>


                </tr>
                <tr>
                    <td style="border-top:1px solid black">
                        Debit
                    </td>
                    <td style="border-top:1px solid black">
                        Credit
                    </td>
                </tr>
            </thead>

            <tbody id="ledgerTable">
                <?php
                if (!empty($journal)) {
                    foreach ($journal as $val) {


                        echo "<tr>
                            <td>
                            $val->reporting_period
                            </td>
                            <td>
                            $val->jev_number
                            </td>
                            <td>
                            $val->explaination
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>";
                        foreach ($val->jevAccountingEntries as $entry) {
                            echo "<tr>" .
                                "<td></td>" .
                                "<td></td>" .
                                "<td>" . $entry->chartOfAccount->general_ledger . "</td>" .
                                "<td>" . $entry->chartOfAccount->uacs . "</td>" .
                                "<td style='text-align:right;'>" . number_format($entry->debit, 2)  . "</td>" .
                                "<td style='text-align:right;'>" . number_format($entry->credit, 2) . "</td>"

                                . "</tr>";
                        }
                    }
                }
                ?>


            </tbody>
            <tfoot>
                <tr class="footer1" style="border:0;">
                    <td colspan="3" class="br"></td>
                    <td colspan='2' class="br" style="padding-top:2em">
                        CERTIFIED CORRECT:
                    </td>
                    <td></td>
                </tr>
                <tr class="footer2">
                    <td colspan="3" class="br"></td>

                    <td colspan='2' class="br">
                        <h5>
                            JOHN VOLTAIRE S. ANCLA,CPA

                        </h5>
                        <h6>
                            Accountant III
                        </h6>
                    </td>
                    <td></td>
                </tr>

            </tfoot>

        </table>
        <?php Pjax::end() ?>

    </div>
    <style>
        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .header1 {
            text-align: center;
            border-bottom: 1px solid black;
        }

        .header1>h5 {
            font-weight: bold;
        }

        tfoot>tr>td {
            border: 1px solid white;
        }



        .header2 {
            border-bottom: 1px solid black;
            text-align: center;
        }

        .footer1>.br {
            border-bottom: 1px solid white;
            border-right: 1px solid white;
        }

        .footer1>td {
            border-bottom: 1px solid white;
            margin: 0;

        }

        .footer2>.br {
            border-right: 1px solid white;
            text-align: center;
        }



        .footer2>td>h5 {
            font-weight: bold;
        }


        /* .header{
            border:none;

        }
        .header>td{
            border: none;
        } */

        .table {
            position: relative;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 12px;
            background-color: white;
        }


        table {
            border: 1px solid black;
            width: 100%;
        }

        .container {
            margin-top: 5px;
            position: relative;
            padding: 10px;

        }

        thead>tr>td {
            border: 1px solid black;
            padding: 10px;
            font-weight: bold;
        }

        #fund {
            display: none;
        }

        .actions {
            padding: 20px;
            position: relative;
        }

        /* tfoot{
            display: none;
        } */

        @media print {
            .actions {
                display: none;
            }

            /* tfoot{
                display: inline;
            } */
            .br>h5 {
                font-size: 10px;
                color: red;
                margin: 0;
            }

            /* tfoot>tr,
            td {
                border: 0;
                color: red;
            }

            tfoot {
                border: 0;
            } */
            table,
            th,
            td {
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
$script = <<< JS

$(document).ready(function(){
    let gen = undefined
    let fund = undefined
    let reporting_period=undefined
    var title=""

    $( "#fund" ).on('change keyup', function(){
        fund = $(this).val()
        // console.log(fund)
        query()
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
        query()
    })

    function query(){
        // console.log(fund+gen)
        // console.log(fund)
        $.pjax({
        container: "#journal", 
        url: window.location.pathname + '?r=jev-preparation/general-journal',
        type:'POST',
        data:{
            fund:fund?fund:0,
            reporting_period:reporting_period?reporting_period:'',
        }});
    }
    function thousands_separators(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

})

JS;
$this->registerJs($script);
?>