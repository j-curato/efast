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

$this->title = 'CKDJ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">



    <?php

    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();
    $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/sample';
    ?>

    <div class="container panel panel-default">

        <div class="row">
            <div class="col-sm-3">

                <button id="print" class="btn btn-primary" style="margin:20px;">Export</button>
            </div>
        </div>

        <input type="file" id="file1" style="display:none">
        <div class="actions " style="bottom: 20px;">



            <div class="col-sm-5">
                <label for="book"> Books</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map($books, 'id', 'name'),
                    'id' => 'book',
                    'name' => 'book',
                    'options' => ['placeholder' => 'Select a Book'],
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
            <div class="col-sm-2" style="margin-top: 25px;">
                <button id="generate" class="btn btn-success">
                    Generate
                </button>
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

                <?php

                if (!empty($credit)) {
                    foreach ($credit as $key => $c) {

                        echo "<th>" . $c["general_ledger"] . '-' . $c["uacs"] . "</th>";
                        echo "</div>";

                        // // echo '<pre>';
                        // // var_dump($c["uacs"]);

                        // // echo '</pre>';
                    }
                    echo "<th>" . 'total' . "</th>";
                }
                if (!empty($debit)) {
                    foreach ($debit as $key => $c) {


                        echo "<th>" . $c["general_ledger"] . '-' . $c["uacs"] . "</th>";
                        // // echo '<pre>';
                        // // var_dump($c["uacs"]);
                        // // echo '</pre>';
                    }
                    echo "<th>" . 'total' . "</th>";
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
                        $payee_name = '';
                        if (!empty($d->payee_id)) {
                            $payee_name = Payee::findOne($d->payee_id)->account_name;
                        }
                        echo "<tr>"

                            . "<td>{$d->reporting_period}</td>" .
                            "<td>$d->jev_number </td>" .
                            "<td>$d->dv_number</td>" .
                            "<td>$d->check_ada_number</td>" .
                            "<td>DISBURSING OFFICER</td>" .
                            "<td>" . $payee_name . "</td>";

                        $i = 0;
                        $y = 0;
                        $total = 0;
                        foreach ($d->jevAccountingEntries as  $acc) {

                            if (!empty($acc->credit)) {
                                $x = array_search($acc->chartOfAccount->uacs, array_column($credit, 'uacs'));
                                for ($i; $i < $credit_count; $i++) {
                                    if ($i == $x) {
                                        echo "<td>" . number_format($acc->credit, 2) . "</td>";
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
                            }
                            $total += $acc->credit + $acc->debit;
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
                        // DEBIT
                        foreach ($d->jevAccountingEntries as  $acc) {
                            if (!empty($acc->debit)) {
                                $x = array_search($acc->chartOfAccount->uacs, array_column($debit, 'uacs'));
                                for ($z; $z < $debit_count; $z++) {
                                    if ($z == $x) {
                                        echo "<td>"  . number_format($acc->debit, 2) . "</td>";
                                        $z++;
                                        break;
                                    } else {
                                        echo "<td></td>";
                                    }
                                }


                                $f++;
                                // $total += $acc->debit;
                            }
                        }
                        if ($z < $debit_count && $f > 0) {


                            addRow($z, $debit_count);
                        }
                        if ($f == 0) {

                            addRow($z, $debit_count);
                        }

                        echo "<td>" . number_format($total, 2) . "</td>";
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

        thead {
            border: 1px solid black;
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
            overflow: scroll;


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
    let book_id = undefined
    let reporting_period=undefined
    let ex=0;

    $( "#general_ledger" ).change(function(){
        gen = $(this).val() 
        //  title = document.getElementById('title')
        query()
    })
    $( "#book" ).on('change keyup', function(){
        book_id = $(this).val()
        // console.log(book_id)
        // query()
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
        // query()
    })
    $("#generate").click(function(){
        query()
    })
    
    $("#print").click(function(){
        ex=1
        query()
    })

    function query(){
        // console.log(book_id+gen)
        // console.log(book_id)
        $.pjax({container: "#employee", 
        url: window.location.pathname + '?r=jev-preparation/ckdj',
        type:'POST',
        data:{
            reporting_period:reporting_period?''+reporting_period.toString():'',
            book_id:book_id?book_id:0,
            export:ex,
            
        },
        success:function(data){
            console.log(data)
        }
        
        })
        ;

    }
    function thousands_separators(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

})
    function openFileOption()
    {
    document.getElementById("file1").click();
    }


JS;
$this->registerJs($script);
?>