<?php

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\Payee;
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
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();
    $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/sample';
    ?>
    <button id="export">export</button>


    <div class="container panel panel-default">
        <div class="actions " style="bottom: 20px;">


            <!-- <div class="col-sm-4">
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
            </div> -->
            <div class="col-sm-5">
                <label for="fund"> Fund Cluster Code</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map($books, 'id', 'name'),
                    'id' => 'fund',
                    'name' => 'fund',
                    'options' => ['placeholder' => 'Select a Fund Cluster Code'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-5">
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
            <div class="col-sm-2">
                    <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>
        <?php Pjax::begin(['id' => 'employee', 'clientOptions' => ['method' => 'POST']]) ?>
        <table class="table" style="margin-top:30px">
            <thead>
                <th>
                    DATE
                </th>
                <th>
                    JEV No.
                </th>
                <th>
                    DV No.
                </th>
                <th>
                    LDDAP/Check No.
                </th>
                <th>
                    Name of Disbursing Officer
                </th>
                <th>
                    Payee
                </th>
                        <!-- HEADER -->
                <?php
                if (!empty($credit) || !empty($debit)) {
                    foreach ($credit as $key => $c) {


                        echo "<th >" . $c["general_ledger"] . '-' . $c["uacs"] . "</th>";
                        // // echo '<pre>';
                        // // var_dump($c["uacs"]);
                        // // echo '</pre>';
                    }
                    echo "<th>" . 'TOTAL' . "</th>";
                    foreach ($debit as $key => $c) {


                        echo "<th>" . $c["general_ledger"] . '-' . $c["uacs"] . "</th>";
                        // // echo '<pre>';
                        // // var_dump($c["uacs"]);
                        // // echo '</pre>';
                    }
                    echo "<th>" . 'TOTAL' . "</th>";
                }


                ?>



            </thead>
            <tbody id="ledgerTable">
                <?php
                if (!empty($data)) {
                    $credit_count = count($credit);
                    $debit_count = count($debit);
                    function addRow($initial, $count)
                    {
                        for ($initial; $initial < $count; $initial++) {

                            echo "<td>" . '' . "</td>";
                        }
                    }
                    foreach ($data as $key => $d) {
                        $payee_name='';
                        $check_no ='';
                        if (!empty($d->payee_id) ){
                        $payee_name  = Payee::findOne($d->payee_id)->account_name;
                        }
                        // if (!empty($d->check_ada_number)){
                            
                        // }
                        echo "<tr>"

                            . "<td>{$d->reporting_period}</td>" .
                            "<td>$d->jev_number </td>" .
                            "<td>$d->dv_number</td>" .
                            "<td>". $d->check_ada_number . "</td>" .
                            "<td>ALLEEN P. PAHAMTANG </td>" .
                            "<td>" . 
            
                            $payee_name
                            . "</td>";

                        $i = 0;
                        $y = 0;
                        $total = 0;
                        
                        foreach ($d->jevAccountingEntries as  $acc) {

                            if (!empty($acc->credit)) {
                                $x = array_search($acc->chartOfAccount->uacs, array_column($credit, 'uacs'));
                                for ($i; $i < $credit_count; $i++) {
                                    if ($i == $x) {
                                        echo "<td> ". number_format($acc->credit)." </td>";
                                        // echo "<td>" . number_format($acc->credit) . " </td>";
                                        $i++;
                                        break;
                                    } else {
                                        echo "<td>" . '' . "</td>";
                                    }
                                }
                                // if ($i != $credit_count) {
                                //     for ($i; $i <= $credit_count; $i++) {
                                //         echo "<td>" . '' . "</td>";
                                //     }
                                // }
                                $y++;
                               
                                $total += $acc->credit;
                            }
                        }
                        if ($i < $credit_count && $y > 0) {

                            // for ($i; $i <= $credit_count; $i++) {
                            //     echo "<td>" . '' . "</td>";
                            // }
                            addRow($i, $credit_count);
                        }
                        if ($y == 0) {

                            addRow($i, $credit_count);
                        }
                        echo "<td>" . number_format($total) . "</td>";

                        $z = 0;
                        $f = 0;

                        foreach ($d->jevAccountingEntries as  $acc) {
                            if (!empty($acc->debit)) {
                                $x = array_search($acc->chartOfAccount->uacs, array_column($debit, 'uacs'));
                                for ($z; $z < $debit_count; $z++) {
                                    if ($z == $x) {
                                        echo "<td>". number_format($acc->debit)."</td>";
                                        // echo "<td>" . number_format($acc->debit) . "</td>";
                                        $z++;
                                        break;
                                    } else {
                                        echo "<td></td>";
                                    }
                                }


                                $f++;
                                $total += $acc->debit;
                            }
                        }
                        if ($z < $debit_count && $f > 0) {


                            addRow($z, $debit_count);
                        }
                        if ($f == 0) {

                            addRow($z, $debit_count);
                        }
                        echo "<td>".number_format($total)."</td>";

                        echo "</tr>";
                    }
                }

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
    let book_id = undefined
    let reporting_period=undefined
    let ex=0
    $( "#general_ledger" ).change(function(){
        gen = $(this).val() 
        //  title = document.getElementById('title')
        // query()
    })
    $( "#fund" ).on('change keyup', function(){
        book_id = $(this).val()
        // console.log(fund)
        // query()
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
        // query()
    })
    $("#generate").click(function(){
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
        url: window.location.pathname + '?r=jev-preparation/adadj',
        type:'POST',
        data:{
            reporting_period:reporting_period?''+reporting_period.toString():'',
            book_id:book_id?book_id:0,
            export:ex,
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