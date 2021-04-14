<?php

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\JevAccountingEntriesSearch;
use app\models\JevPreparationSearch;
use app\models\ResponsibilityCenter;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
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

    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();
    $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/sample';
    ?>




    <div class="container panel panel-default">
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
            <div class="col-sm-2" style="padding:25px">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>
        <?php

        // if (!empty($book_name)) {
        //     $book = (new \yii\db\Query())
        //         ->select("*")
        //         ->from("books")
        //         ->where("name =:name", ["name" => $book_name])
        //         ->one();
            // $q = new JevAccountingEntriesSearch();
            // // $q->book_id = $book['id'];
            // // $q->reporting_period = $reporting_period;
            // $w = $q->search(Yii::$app->request->queryParams);
            // $gridColumn = [
            //     'id',
            // ];
            // echo ExportMenu::widget([
            //     'dataProvider' => $w,
            //     'columns' => $gridColumn,
            //     'filename' => 'Jev',
            //     'exportConfig' => [
            //         ExportMenu::FORMAT_TEXT => false,
            //         ExportMenu::FORMAT_PDF => false,
            //         ExportMenu::FORMAT_EXCEL => false,
            //         ExportMenu::FORMAT_HTML => false,
            //     ]

            // ]);
        // }
        ?>
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
                            Book:

                        </span>
                        <span id="fund_cluster" style="text-align: center;">

                            <?php if (!empty($book_name)) {
                                echo $book_name;
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

                            if ($entry->lvl === 1) {
                                $account_title = $entry->chartOfAccount->general_ledger;
                                $object_code = $entry->object_code;
                            } else if ($entry->lvl === 2) {
                                $q = (new \yii\db\Query())
                                    ->select(["name", "object_code"])
                                    ->from("sub_accounts1")
                                    ->where("object_code =:object_code", ['object_code' => $entry->object_code])
                                    ->one();
                                $account_title = $q['name'];
                                $object_code = $q["object_code"];
                            } else if ($entry->lvl === 3) {
                                $q = (new \yii\db\Query())
                                    ->select(["name", "object_code"])
                                    ->from("sub_accounts2")
                                    ->where("object_code =:object_code", ['object_code' => $entry->object_code])
                                    ->one();
                                $account_title = $q['name'];
                                $object_code = $q["object_code"];
                            }



                            echo "<tr>" .
                                "<td></td>" .
                                "<td></td>" .
                                "<td>" . $account_title . "</td>" .
                                "<td>" . $object_code . "</td>" .
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
    let book_id = undefined
    let reporting_period=undefined
    var title=""

    $( "#book" ).on('change', function(){
        book_id = $(this).val()
        // console.log(book_id)
        // query()
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
        // query()
    })
    $('#generate').click(function(){
        query()
    })

    function query(){
        // console.log(book_id+gen)
        // console.log(book_id)
        $.pjax({
        container: "#journal", 
        url: window.location.pathname + '?r=jev-preparation/general-journal',
        type:'POST',
        data:{
            book_id:book_id?book_id:0,
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