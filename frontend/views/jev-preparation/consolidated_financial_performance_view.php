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

$this->title = 'Consolidated Financial Performance';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">



    <?php

    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();
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
            <div class="action">
                <button id="print" onclick="window.print()"><i class="glyphicon glyphicon-print"></i></button>
            </div>

        </div>
        <br>


        <div class="actions " style="bottom: 20px;">



            <div class="col-sm-3">
                <label for="book"> Books</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map($books, 'id', 'name'),
                    'id' => 'book',
                    'name' => 'book',
                    'options' => ['placeholder' => 'Select a Books'],
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
            <div class="com-sm-3" style="padding-top: 25px;">
                <button class="btn btn-success" id="submit">Generate</button>
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
                                    STATEMENT OF FINANCIAL PERFORMANCE
                                </h5>
                                <h5>
                                    FUND CLUSTER <?php echo !empty($book_name)?strtoupper($book_name):''?>
                                </h5>
                                <h5>
                                    As of <?php echo !empty($reporting_period) ? $reporting_period : '' ?>
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
                        if (!empty($reporting_period)) {
                            echo date('Y',strtotime($reporting_period));
                        } else {
                            echo 'Year';
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        if (!empty($prev_year)) {
                            echo $prev_year;
                        } else {
                            echo 'Previous Year';
                        }
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
                            <td  class='right-border' style='font-weight:bold;text-align:left'>{$key}</td>
                            <td colspan='3'></td>
                            <td ></td>
                            <td ></td>
                         </tr>";

                        foreach ($val1 as $key2 => $val2) {
                            echo "<tr>
                        <td colspan='2' class='right-border' stye='font-weight:bold'>{$key2}</td>
                        <td colspan='2'></td>
                        <td ></td>
                        <td ></td>
                     </tr>";
                            // foreach ($val2 as $key3 => $val3) {

                            //     echo "<tr>
                            //     <td class='right-border'></td>
                            //     <td colspan='2' class='right-border' style='text-align:left' >{$key3}</td>
                            //     <td colspan='3'></td>
                            //  </tr>";

                            foreach ($val2 as $key4 => $val4) {
                                $total_current += $val4['current_bal'];
                                $total_last_year += $val4['last_year_bal'];
                                echo "<tr >
                            <td colspan='2' class='right-border'> </td>
                            <td  style='text-align:left' colspan='2'>{$val4['general_ledger']}</td>
                            <td  style='text-align:right'>" . number_format($val4['current_bal'], 2) . "</td>
                            <td  style='text-align:right'> " . number_format($val4['last_year_bal'], 2) . "</td>
                        </tr>";
                            }
                            // }
                        }
                        echo "<tr>
                    <td colspan='1' class='right-border'></td>
                    <td colspan='3'  >Total {$key}</td>
                    <td colspan='1'  style='text-align:right'>" . number_format($total_current, 2) . "</td>
                    <td colspan='1'  style='text-align:right'>" . number_format($total_last_year, 2) . "</td>
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
        h5{
            font-weight: bold;
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
            .actions {
                display: none;
            }

            table,
            th,
            td {
                padding: 5px;
                font-size: 10px;
            }

            .action {
                display: none;
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
    let book_id = undefined
    let reporting_period=undefined
    let sub_account=undefined
    let ex=0
    $( "#general_ledger" ).change(function(){
        gen = $(this).val() 
        //  title = document.getElementById('title')
        // query()
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
        // console.log(book_id+gen)
        // console.log(book_id)
        $.pjax({container: "#employee", 
        url: window.location.pathname + '?r=jev-preparation/consolidated-financial-performance',
        type:'POST',
        data:{
            reporting_period:reporting_period?''+reporting_period.toString():'',
            book_id:book_id?book_id:0,
        
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