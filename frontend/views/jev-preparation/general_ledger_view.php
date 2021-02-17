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

$this->title = 'ADADJ';
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
    <button id="export">export</button>


    <div class="container panel panel-default">
        <div class="actions " style="bottom: 20px;">


            <div class="col-sm-4">
                <label for="general_ledger">General Ledger</label>
                <?php
                echo Select2::widget([
                    'id' => 'general_ledger',
                    'data' => ArrayHelper::map($ledger, 'id', 'name'),
                    'name' => 'general_ledger',
                    'options' => ['placeholder' => 'General Ledger Account'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-4">
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
            <div class="col-sm-4">
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
        <?php Pjax::begin(['id' => 'employee', 'clientOptions' => ['method' => 'POST']]) ?>
        <table class="table" style="margin-top:30px">
            <thead>
                <th>
                    DATE
                </th>
                <th>
                    Particulars
                </th>
                <th>
                    Reference No.
                </th>
                <th>
                    ledger
                </th>
                <th>
                    Uacs
                </th>
                <th>
                    Credit
                </th>
                <th>
                    Debit
                </th>
                <th>
                    balance
                </th>

                <?php



                ?>



            </thead>
            <tbody id="ledgerTable">
                <?php
                $balance = 0;
                if (!empty($data)) {
                    foreach ($data as $key => $val) {

                        if ($key > 0) {
                            if ($val['normal_balance'] == 'credit') {
                                $balance = $balance + $val['credit'] - $val['debit'];
                            } else {
                                $balance = $balance + $val['debit'] - $val['credit'];
                            }
                        } else {
                            $balance = $val['credit'] ? $val['credit'] : $val['debit'];
                        }

                        // $x = array_key_exists($val['uacs'], $balance_per_uacs);

                        // if ($x === false) {
                        //     if ($val['credit'] > 0) {
                        //         // $balance_per_uacs[] =["$val['uacs]"=>{$val['credit']}];
                        //         $balance_per_uacs[$val['uacs']] = $val['credit'];
                        //         $balance = $val['credit'];
                        //     } else {
                        //         $balance_per_uacs[$val['uacs']] = $val['debit'];
                        //         $balance = $val['debit'];

                        //     }
                        // } else {
                        //     if ($val['normal_balance'] == 'credit') {
                        //         $balance = $balance_per_uacs[$val['uacs']] + $val['credit'] - $val['debit'];
                        //     } else {
                        //         $balance = $balance_per_uacs[$val['uacs']] + $val['debit'] - $val['credit'];
                        //     }
                        // }
      
                        $credit = $val['credit']?number_format($val['credit'], 2):'';
                        $debit = $val['debit']?number_format($val['debit'], 2):'';
                        echo "<tr>
                            <td>{$val['reporting_period']}</td>
                            <td>{$val['explaination']}</td>
                            <td>{$val['uacs']}</td>
                            <td>{$val['general_ledger']}</td>
                            <td>{$val['ref_number']}</td>
                            <td>" . $debit . "</td>
                            <td>" . $credit . "</td>
                            <td>" . number_format($balance, 2) . "</td>

                        </tr>";
                    };
                    // echo '<pre>';
                    // var_dump($data);
                    // echo '</pre>';
                }

                // echo '<pre>';
                // var_dump($data);
                // echo '</pre>';

                ?>


            </tbody>
        </table>


        <?php Pjax::end() ?>
    </div>
    <style>
        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .table>thead>tr>th {
            border-bottom: 1px solid black;
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
            /* overflow: scroll; */

            overflow-y: hidden;
            overflow-x: auto;


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

        @media print {
            .actions {
                display: none;
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
    let ex=0
    $( "#general_ledger" ).change(function(){
        gen = $(this).val() 
        //  title = document.getElementById('title')
        query()
    })
    $( "#fund" ).on('change keyup', function(){
        fund = $(this).val()
        // console.log(fund)
        query()
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
        query()
    })
    $("#export").click(function(){
        ex=1
        query()
    })

    function query(){
        // console.log(fund+gen)
        // console.log(fund)
        $.pjax({container: "#employee", 
        url: window.location.pathname + '?r=jev-preparation/ledger',
        type:'POST',
        data:{
            reporting_period:reporting_period?''+reporting_period.toString():'',
            fund:fund?fund:0,
            export:ex,
            gen:gen?gen:0,
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