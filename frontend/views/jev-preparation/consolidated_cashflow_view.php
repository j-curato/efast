<?php

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\ResponsibilityCenter;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use Mpdf\Tag\Em;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detailed Financial Position';
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
    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/sample';
    $sub1 = (new \yii\db\Query())->select('*')->from('sub_accounts1')->all();

    ?>


    <div class="container panel panel-default">

        <div>
            <?php
            $q = 'qwe';

            if (!empty($print)) {
                $q = $print;
            }



            ?>
            <button id="print" onclick="window.print()"><i class="glyphicon glyphicon-print"></i></button>

        </div>
        <br>


        <div class="actions " style="bottom: 20px;">



            <div class="col-sm-3">
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
            <div class="col-sm-3">
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
            <div class="col-sm-3">
                <button class="btn btn-success" id="submit"> Generate</button>
            </div>

        </div>
        <!-- 
        <div class="document_header ">

            <div style="width: 40%;">

                <?= Html::img('@web/dti.jpg', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;margin-left:40%']); ?>
            </div>
            <div style="text-align: center ;  font-weight: bold;">
                <h5>DEPATMENT OF TRADE AND INDUSTRY</h5>
                <h6>CARAGA REGIONAL OFFICE</h6>
                <h6>TRIAL BALANCE-FUND 01</h6>
                <h6>As Of November 30,2020</h6>
            </div>
        </div> -->
        <?php Pjax::begin(['id' => 'employee', 'clientOptions' => ['method' => 'POST']]) ?>

        <br>
        <table class="table" style="margin-top:30px">
            <thead>
                <tr class="main_header">
                    <th colspan="6">

                        <div style="display:flex;width:100%;align-items:center;">
                            <div style="padding:12px;">
                                <img src='../web/dti.jpg' style='width:80px;height:80px;margin-left:auto;margin-right:10px'>
                            </div>
                            <div style="margin-left:auto;margin-right:auto">
                                <h5>
                                    DEPARTMENT OF TRADE AND INDUSTRY -CARAGA
                                </h5>
                                <h5>
                                    STATEMENT OF CASH FLOWS
                                </h5>
                                <h5>
                                    FUND CLUSTER
                                </h5>
                                <h5>
                                    AS OF <?php
                                            echo !empty($reporting_period) ? $reporting_period : '';
                                            ?>
                                </h5>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="4">

                    </th>

                    <th>
                        <?php
                        echo !empty($reporting_period) ? date('Y', strtotime($reporting_period)) : '';
                        ?>
                    </th>
                    <th>
                        <?php
                        echo !empty($prev_year) ? $prev_year : '';
                        ?>
                    </th>

                </tr>

            </thead>
            <tbody id="ledgerTable">

                <?php
                if (!empty($data)) {

                    foreach ($data as $key => $val1) {
                        $total_current = 0;
                        $total_last_year = 0;
                        echo "<tr>
                                <td  class='right-border'>{$key}</td>
                                <td colspan='5'></td>
                            </tr>";

                        foreach ($val1 as $key2 => $val2) {
                            echo "<tr>
                                    <td colspan='2' class='right-border' >{$key2}</td>
                                    <td colspan='4'></td>
                                </tr>";
                            foreach ($val2 as $key4 => $val4) {
                                $total_current += $val4['current_bal'];
                                $total_last_year += $val4['last_year_bal'];
                                echo "<tr >
                                        <td colspan='2' class='right-border'> </td>
                                        <td  style='text-align:left' colspan='2'>{$val4['specific_cashflow']}</td>
                                        <td>" . number_format($val4['current_bal'], 2) . "</td>
                                        <td> " . number_format($val4['last_year_bal'], 2) . "</td>
                                    </tr>";
                            }
                        }
                        echo "<tr>
                                <td colspan='1' class='right-border'></td>
                                <td colspan='3'  >Total </td>
                                <td colspan='1'>" . number_format($total_current, 2) . "</td>
                                <td colspan='1'>" . number_format($total_last_year, 2) . "</td>
                            </tr>";
                    }
                }

                ?>



            </tbody>
        </table>


        <?php Pjax::end() ?>
    </div>
    <style>
        .right-border {
            border-right: 1px solid transparent;
        }

        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .head {
            text-align: left;
        }

        .table>thead>tr>th {
            border-bottom: 1px solid black;
            border-top: 1px solid black;
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

        .head>th {
            border-top: 1px solid black
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 12px;
            background-color: white;
            text-align: center;
        }



        table {
            border: 1px solid black;
            width: 100%;
        }

        .container {
            margin-top: 5px;
            position: relative;
            padding: 10px;
            /* overflow: scroll; */

            overflow-y: hidden;
            overflow-x: auto;


        }

        .head>th {
            display: table-cell;
            vertical-align: text-top;
            text-align: center;
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

        /* .document_header>th {}

        .document_header {} */

        .document_header1>th {

            padding: 10px;
        }

        .document_header1>th {
            border: 0;
        }

        @media print {
            #print {
                display: none;
            }

            .actions {
                display: none;
            }

            table,
            th,
            td {
                padding: 5px;
                font-size: 10px;
            }

            thead>tr>td {
                border: 1px solid black;
                padding: 5px;
                font-weight: bold;
            }

            @page {
                size: auto;
                margin: 0cm;
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
    let sub_account=undefined
    let ex=0
    $( "#general_ledger" ).change(function(){
        gen = $(this).val() 
        //  title = document.getElementById('title')
        // query()
    })
    $( "#fund" ).on('change keyup', function(){
        fund = $(this).val()
        // console.log(fund)
        // query()
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
        // query()
    })
    $("#sub_account").change(function(){
        sub_account=$(this).val()
        // query()
    })


    $("#export").click(function(){
        ex=1
        query()
    })
    $('.printData').click(function(){
        var y = $(this).val()
        console.log(y)
    })
    let print=0
    $("#print").click(function(){
        var x = $(this).val()

        //  var y= JSON.parse(x)
        print=1
            printData()

    })
    $('#submit').click(function(){
        
        query()
    })

    function query(){
        // console.log(fund+gen)
        // console.log(fund)
        $.pjax({container: "#employee", 
        url: window.location.pathname + '?r=jev-preparation/consolidated-cashflow',
        type:'POST',
        data:{
            reporting_period:reporting_period?''+reporting_period.toString():'',
            fund:fund?fund:0,
        
        },
  
    });


    }
    function thousands_separators(num)
    {
        var number= Number(Math.round(num+'e2')+'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }


});

JS;
$this->registerJs($script);
?>