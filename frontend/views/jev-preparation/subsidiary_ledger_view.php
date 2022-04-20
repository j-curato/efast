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

$this->title = 'Subsidiary Ledger';
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
    $book = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();
    $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/sample';
    $sub1 = (new \yii\db\Query())->select('*')->from('sub_accounts_view')->all();

    ?>


    <div class="container panel panel-default" style="margin-top:6em;padding:2em;padding-top:4em;">



        <form id="print_filter">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-2">

                    <h4>Print Filter</h4>


                </div>
                <div class="col-sm-5"></div>
            </div>
            <div class="row " style="bottom: 20px;">

                <div class="col-sm-3">
                    <label for="uacs">General Ledger</label>
                    <?php
                    $coa = Yii::$app->db->createCommand("SELECT CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger)as account_title,uacs FROM chart_of_accounts WHERE is_active=1")->queryAll();
                    echo Select2::widget([
                        'id' => 'uacs',
                        'data' => ArrayHelper::map($coa, 'uacs', 'account_title'),
                        'name' => 'print_uacs',
                        'options' => ['placeholder' => 'Select General Ledger'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-sm-3">
                    <label for="book"> Fund Cluster Code</label>
                    <?php
                    echo Select2::widget([
                        'data' => ArrayHelper::map($book, 'id', 'name'),
                        'name' => 'print_book_id',
                        'options' => ['placeholder' => 'Select a Book '],
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
                        'id' => 'print_reporting_period',
                        'name' => 'print_reporting_period',
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'minViewMode' => "months",

                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-3" style="margin-top:25px;">
                    <button id="print_all" type='button' class="btn btn-warning">Print</button>
                </div>

            </div>
        </form>

        <form id="generate_filter">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-2">

                    <h4>Generate Filter</h4>


                </div>
                <div class="col-sm-5"></div>
            </div>
            <div class="row " style="bottom: 20px;">


                <div class="col-sm-3">
                    <label for="sub_account">Sub Account</label>
                    <?php
                    echo Select2::widget([
                        'id' => 'sub_account',
                        'data' => ArrayHelper::map($sub1, 'object_code', 'account_title'),
                        'name' => 'object_code',
                        'options' => ['placeholder' => 'Select Sub Account'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>


                <div class="col-sm-3">
                    <label for="fund"> Book </label>
                    <?php
                    echo Select2::widget([
                        'data' => ArrayHelper::map($book, 'id', 'name'),
                        'id' => 'book',
                        'name' => 'book_id',
                        'options' => ['placeholder' => 'Select a Book '],
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
                        'name' => 'reporting_period',
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'minViewMode' => "months",

                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-3" style="margin-top:25px;">
                    <button id="generate" type="button" class="btn btn-success">Generate</button>

                </div>

            </div>
        </form>
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

        <br>
        <table class="table" id="ledger-table" style="margin-top:30px">
            <thead>


                <tr class="document_header1">
                    <th>
                        Entity Name:
                    </th>
                    <th>
                        DEPARTMENT OF TRADE AND INDUSTRY
                    </th>
                    <!-- <th>
                    </th> -->
                    <th colspan="2">
                        Fund Cluster Code:
                    </th>
                    <th colspan="2">
                        <?php echo  !empty($fund_cluster) ? $fund_cluster : '' ?>
                    </th>
                </tr>


                <tr>
                    <th colspan="3" class="head">

                        <div>
                            <span> Account Of:</span>
                            <span><?php echo  !empty($sl_name) ? $sl_name : '' ?></span>
                        </div>
                        <div>
                            <span> Office/Address:</span>
                            <span> PD , DTI</span>
                        </div>
                        <div>
                            <span> Contact Number:</span>
                            <span> PD , DTI</span>
                        </div>
                    </th>

                    <th colspan="3">
                        <?php
                        if (!empty($object_code)) {
                            echo $object_code;
                        }
                        ?>
                        <div>
                            <span>Account Code </span>
                        </div>
                        <div>
                            <span> GL :</span>
                            <span> <?php echo !empty($general_ledger) ? $general_ledger : '' ?></span>
                        </div>
                        <div>
                            <span>SL :</span>
                            <span>
                                <span> <?php echo  !empty($sl_name) ? $sl_name : '' ?></span>
                            </span>
                        </div>

                    </th>
                </tr>



                <tr class="head">

                    <th rowspan="3">
                        Date
                    </th>
                    <th rowspan="3">
                        Particulars
                    </th>
                    <th rowspan="3">
                        Reference No.
                    </th>
                    <th rowspan="1" colspan="3">
                        Amount
                    </th>

                </tr>
                <tr>
                    <th rowspan="1">
                        Debit
                    </th>
                    <th rowspan="1">
                        Credit
                    </th>
                    <th rowspan="1">
                        Balance
                    </th>

                </tr>

            </thead>
            <tbody>
                <?php
                $balance = 0;
                $balance_per_uacs = [];
                if (!empty($data)) {
                    foreach ($data as $key => $val) {


                        $credit = $val['credit'] ? number_format($val['credit'], 2) : '';
                        $debit = $val['debit'] ? number_format($val['debit'], 2) : '';
                        $balance = $val['balance'] ? number_format($val['balance'], 2) : '';
                        echo "<tr>
                            <td>{$val['date']}</td>
                            <td>{$val['explaination']}</td>
                            <td>{$val['jev_number']}</td>
                            <td>$debit </td>
                            <td>$credit</td>
                            <td>$balance</td>

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


    </div>


</div>
<style>
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

        #print {
            display: none;
        }

        #submit {
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

<script>
    $(document).ready(function() {
        let q = 0;
        let x = -0.2;

        let y = 0.2;
        console.log(q = x + y)
        $("#print_all").on('click', function(e) {
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=jev-preparation/get-subsidiary-ledger',
                data: $("#print_filter").serialize(),
                success: function(data) {
                    console.log(JSON.parse(data))
                    let res = JSON.parse(data)
                    printData(res.for_print, res.year, res.book_name)
                }
            })
        })
        $("#generate").on('click', function(e) {
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=jev-preparation/generate-subsidiary-ledger',
                data: $("#generate_filter").serialize(),
                success: function(data) {
                    console.log(JSON.parse(data))
                    let res = JSON.parse(data)
                    diisplayData(res)

                }
            })
        })
    })

    function diisplayData(data) {
        let running_balance = 0
        let total_debit = 0;
        let total_credit = 0;
        $("#ledger-table tbody").html('')
        $.each(data, function(key, val) {
            let debit = Number(val.debit);
            let credit = Number(val.credit);
            let particular = val.particular == 'beginning_balance' ? 'Beginning Balance' : val.particular;
            total_debit += debit;
            total_credit += credit;
            if (val.normal_balance.toLowerCase() == 'debit') {
                running_balance += debit - credit;
            } else {
                running_balance += credit - debit
            }

            let row = `<tr>
            <td>${val.date}</td>
            <td>${particular}</td>
            <td>${val.jev_number}</td>
            <td>${thousands_separators(debit.toFixed(2))}</td>
            <td>${thousands_separators(credit.toFixed(2))}</td>
            <td>${thousands_separators(running_balance.toFixed(2))}</td>
            </tr>`

            $("#ledger-table tbody").append(row)
        })
        let total_row = `<tr>
            <td colspan='3'>TOTAL</td>
            <td>${thousands_separators(total_debit.toFixed(2))}</td>
            <td>${thousands_separators(total_credit.toFixed(2))}</td>
            <td>${thousands_separators(running_balance.toFixed(2))}</td>
            </tr>`

        $("#ledger-table tbody").append(total_row)
    }

    function thousands_separators(num) {
        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

    function printData(data, year, book) {


        let fund_cluster_code = ''
        let object = ''
        // console.log(data)
        var mywindow = window.open('?r=jev-preparation/ledger', 'new div', 'height=700,width=1300');
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<link rel="stylesheet" href="/afms/frontend/web/print.css" type="text/css" media="all" />');

        // mywindow.document.write('<style>');
        // mywindow.document.write('.style1 {font-size:11px; font-weight:bold; color:red; border:1px solid black}');
        // mywindow.document.write('@media print{ .table{page-break-after:auto;} @page{margin:0.3cm;} td{padding:4px;font-size:12px}th{padding:1;font-size:12px}} ');
        // mywindow.document.write('th,td {border: 1px solid black;padding: 10px;background-color: white;margin:0;gap:0;}');
        // mywindow.document.write('table {border-spacing:0;border-collapse: collapse;}');
        // mywindow.document.write('.document_header1 >th {border:0;}');
        // mywindow.document.write('h4 {padding:0;margin:0;}');
        // mywindow.document.write('</style>');
        mywindow.document.write('</head><body >');
        // mywindow.document.write('<img src="../web/dti.jpg" style="width:100px;height:100px;">');
        mywindow.document.write("<table class='table' cellspacing='0'><tbody>");
        mywindow.document.write("<thead>");

        mywindow.document.write("</thead>");
        $.each(data, function(key, val) {
            // console.log(val[1]['object_code'])
            let head_object_code = val[0]['object_code'] == null ? val[1]['object_code'] : val[0]['object_code'];
            let head_account_title = val[0]['account_title'] == null ? val[1]['account_title'] : val[0]['account_title'];
            mywindow.document.write("<tr class='header_logo' style='margin-bottom:5px;'>");
            mywindow.document.write(" <th></th>");
            mywindow.document.write(` <th colspan='2'> <div style='display:flex'><img src='/afms/frontend/web/dti.jpg' style='width:80px;height:80px;margin-left:auto;margin-right:10px''><div style='margin-top:10px; text-align:center;'><h4 style='margin-top:13px'>Department of Trade and Industry</h4><h4>Subsidiary Ledger</h4><h4>${year}</h4></div></div></th>`);
            // mywindow.document.write(" <th colspan='2'><h4>Department of Trade and Industry</h4><h4>General Ledger</h4><h4>2020</h4></th>");

            mywindow.document.write(" <th colspan='2' style='text-align:center;'>" + '' + "</th>");
            mywindow.document.write("</tr>");

            mywindow.document.write("<tr class='document_header1'>");
            mywindow.document.write(" <th colspan='1' style='text-align:center;white-space:nowrap'>Entity Name:</th>");
            mywindow.document.write(" <th colspan='1' style='text-align:center;'>DEPARTMENT OF TRADE AND INDUSTRY</th>");
            mywindow.document.write(" <th colspan='2' style='text-align:center;'>Fund Cluster:</th>");
            mywindow.document.write(" <th colspan='2' style='text-align:center;'>" + book + "</th>");
            mywindow.document.write("</tr>");


            mywindow.document.write("<tr class='document_header1'>");
            mywindow.document.write(" <th colspan='1' style='text-align:center; white-space:nowrap'>Account Title:</th>");
            mywindow.document.write(" <th style='text-align:center;'>" + head_account_title + "</th>");
            mywindow.document.write(" <th colspan='2' style='text-align:center;'>UACS Object Code</th>");
            mywindow.document.write(" <th colspan='2' style='text-align:center;'>" + head_object_code + "</th>");
            mywindow.document.write("</tr>");
            mywindow.document.write("<tr>");
            // mywindow.document.write("<th>Reporting Period</th>");
            mywindow.document.write("<th>Date</th>");
            mywindow.document.write("<th>Particular:</th>");
            mywindow.document.write("<th>Reference No</th>");
            // mywindow.document.write("<th>Amount</th>");
            mywindow.document.write("<th>Debit</th>");
            mywindow.document.write("<th>Credit</th>");
            mywindow.document.write("<th>Balance</th>");
            mywindow.document.write("</tr>");

            mywindow.document.write("<tr> ");
            mywindow.document.write("<td style='text-align:center;background-color:yellow;' colspan='6'>" + key + "</td>");
            mywindow.document.write("</tr>");
            let total_balance = 0;
            let total_debit = 0;
            let total_credit = 0;
            let q = 0;
            $.each(val, function(key, val2) {
                let balance = 0;
                let particular = val2.particular == 'beginning_balance' ? 'Beginning Balance' : val2.particular;
                let credit = parseFloat(val2.credit)
                let debit = parseFloat(val2.debit)
                total_debit += parseFloat(val2.debit);
                total_credit += parseFloat(val2.credit);

                if (val2.normal_balance.toLowerCase() == 'debit') {
                    q += debit - credit;
                } else {
                    q += credit - debit;
                }

                let total_balance = q.toFixed(2)
                mywindow.document.write("<tr> ");
                mywindow.document.write("<td style='text-align:center;'>" + val2.date + "</td>");
                mywindow.document.write("<td style='text-align:center;'>" + particular + "</td>");
                mywindow.document.write("<td style='text-align:center;'>" + val2.jev_number + "</td>");
                mywindow.document.write("<td style='text-align:right;'>" + thousands_separators(val2.debit) + "</td>");
                mywindow.document.write("<td style='text-align:right;'>" + thousands_separators(val2.credit) + "</td>");
                mywindow.document.write("<td style='text-align:right;'>" + thousands_separators(total_balance) + "</td>");
                mywindow.document.write("</tr>");
            })
            mywindow.document.write("<tr> ");
            mywindow.document.write("<td style='text-align:center;' colspan='3'>Total</td>");
            mywindow.document.write("<td style='text-align:right;'>" + thousands_separators(total_debit) + "</td>");
            mywindow.document.write("<td style='text-align:right;'>" + thousands_separators(total_credit) + "</td>");
            mywindow.document.write("<td style='text-align:right;'>" + thousands_separators(total_balance) + "</td>");
            mywindow.document.write("</tr>");

            // mywindow.document.write("<p style='page-break-after:always;'></p>");
        })
        mywindow.document.write("</tbody></table>");
        // for (var i = 0; i < object.length; i++) {

        //     mywindow.document.write("<table class='table' cellspacing='0'><tbody>");
        //     mywindow.document.write("<thead>");


        //     mywindow.document.write("<tr class='header_logo' style='margin-bottom:5px;'>");
        //     mywindow.document.write(" <th></th>");
        //     mywindow.document.write(" <th colspan='2'> <div style='display:flex'><img src='/afms/frontend/web/dti.jpg' style='width:80px;height:80px;margin-left:auto;margin-right:10px''><div style='margin-top:10px; text-align:center;'><h4 style='margin-top:13px'>Department of Trade and Industry</h4><h4>General Ledger</h4><h4>2020</h4></div></div></th>");
        //     // mywindow.document.write(" <th colspan='2'><h4>Department of Trade and Industry</h4><h4>General Ledger</h4><h4>2020</h4></th>");

        //     mywindow.document.write(" <th colspan='2' style='text-align:center;'>" + '' + "</th>");
        //     mywindow.document.write("</tr>");

        //     mywindow.document.write("<tr class='document_header1'>");
        //     mywindow.document.write(" <th colspan='1' style='text-align:center;white-space:nowrap'>Entity Name:</th>");
        //     mywindow.document.write(" <th colspan='1' style='text-align:center;'>DEPARTMENT OF TRADE AND INDUSTRY</th>");
        //     mywindow.document.write(" <th colspan='2' style='text-align:center;'>Fund Cluster:</th>");
        //     mywindow.document.write(" <th colspan='2' style='text-align:center;'>" + fund_cluster_code + "</th>");
        //     mywindow.document.write("</tr>");


        //     mywindow.document.write("<tr class='document_header1'>");
        //     mywindow.document.write(" <th colspan='1' style='text-align:center; white-space:nowrap'>Account Title:</th>");
        //     mywindow.document.write(" <th style='text-align:center;'>" + data[object[i]][0]['general_ledger'] + "</th>");
        //     mywindow.document.write(" <th colspan='2' style='text-align:center;'>UACS Object Code</th>");
        //     mywindow.document.write(" <th colspan='2' style='text-align:center;'>" + object[i] + "</th>");
        //     mywindow.document.write("</tr>");


        //     mywindow.document.write("<tr>");
        //     // mywindow.document.write("<th>Reporting Period</th>");
        //     mywindow.document.write("<th>Date</th>");
        //     mywindow.document.write("<th>Particular:</th>");
        //     mywindow.document.write("<th>Reference No</th>");
        //     // mywindow.document.write("<th>Amount</th>");
        //     mywindow.document.write("<th>Debit</th>");
        //     mywindow.document.write("<th>Credit</th>");
        //     mywindow.document.write("<th>Balance</th>");
        //     mywindow.document.write("</tr>");


        //     mywindow.document.write("</thead>");
        //     // mywindow.document.write(object[i]);
        //     // mywindow.document.write('<br>');
        //     for (var x = 0; x < data[object[i]].length; x++) {
        //         // mywindow.document.write(data[object[i]][x]['reporting_period']);
        //         // mywindow.document.write('<br>');
        //         // mywindow.document.write(object[i]);
        //         // var bal= roundOff(data[object[i]][x]['balance'],2)

        //         // var y= Number(Math.round(data[object[i]][x]['balance']+'e2')+'e-2')
        //         var bal = thousands_separators(data[object[i]][x]['balance'])
        //         var y
        //         if (bal == 'NaN') {
        //             bal = 0;
        //             console.log(bal)
        //         }
        //         var debit = data[object[i]][x]['debit'] > 0 ? thousands_separators(data[object[i]][x]['debit']) : ''
        //         var credit = data[object[i]][x]['credit'] > 0 ? thousands_separators(data[object[i]][x]['credit']) : ''

        //         // console.log(data[object[i]][x]['balance'])
        //         mywindow.document.write("<tr> ");
        //         // mywindow.document.write("<td>"+data[object[i]][x]['reporting_period']+"</td>");
        //         // mywindow.document.write("<td>"+data[object[i]][x]['reporting_period']+"</td>");
        //         mywindow.document.write("<td>" + data[object[i]][x]['date'] + "</td>");
        //         mywindow.document.write("<td>" + data[object[i]][x]['explaination'] + "</td>");
        //         mywindow.document.write("<td>" + data[object[i]][x]['ref_number'] + "</td>");
        //         mywindow.document.write("<td style='text-align:right;' >" + debit + "</td>");
        //         mywindow.document.write("<td style='text-align:right;'>" + credit + "</td>");
        //         mywindow.document.write("<td style='text-align:right;'>" + bal + "</td>");
        //         mywindow.document.write("</tr>");


        //     }
        //     mywindow.document.write("</tbody></table>");
        //     mywindow.document.write("<p style='page-break-after:always;'></p>");

        // }
        mywindow.document.write('</body></html>');
        mywindow.document.close();
        mywindow.focus();
        setTimeout(function() {
            mywindow.print();
            mywindow.close();
        }, 1000);
        // mywindow.print()
        print = 0

    }
</script>
<?php
$script = <<< JS

// $(document).ready(function(){
//     let gen = undefined
//     let book_id = undefined
//     let reporting_period=undefined
//     let sub_account=undefined
//     let ex=0
//     $( "#general_ledger" ).change(function(){
//         gen = $(this).val() 
//         //  title = document.getElementById('title')
//         // query()
//     })
//     $( "#book" ).on('change keyup', function(){
//         book_id = $(this).val()
//         // console.log(book_id)
//         // query()
//     })
//     $("#reporting_period").change(function(){
//         reporting_period=$(this).val()
//         // query()
//     })
//     $("#sub_account").change(function(){
//         sub_account=$(this).val()
//         // query()
//     })


//     $("#export").click(function(){
//         ex=1
//         query()
//     })
//     $('.printData').click(function(){
//         var y = $(this).val()
//         console.log(y)
//     })
//     let print=0
//     // $("#print").click(function(){
//     //     var x = $(this).val()

//     //     //  var y= JSON.parse(x)
//     //     print=1
//     //         printData()

//     // })
//     $('#submit').click(function(){
        
//         query()
//     })

//     function query(){
//         // console.log(fund+gen)
//         // console.log(fund)
//         $.pjax({container: "#employee", 
//         url: window.location.pathname + '?r=jev-preparation/get-subsidiary-ledger',
//         type:'POST',
//         data:{
//             reporting_period:reporting_period?''+reporting_period.toString():'',
//             book_id:book_id?book_id:0,
//             export:ex,
//             sub_account:sub_account?sub_account:0,
//             print:print
//         },
  
//     });


//     }
//     function thousands_separators(num)
//     {
//         var number= Number(Math.round(num+'e2')+'e-2')
//         var num_parts = number.toString().split(".");
//         num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
//         return num_parts.join(".");
//     }

//     // function printData(){
//     //     $.ajax({
//     //     url: window.location.pathname + '?r=jev-preparation/ledger',
//     //     type:'POST',
//     //     data:{
//     //         reporting_period:reporting_period?''+reporting_period.toString():'',
//     //         fund:fund?fund:0,
//     //         export:ex,
//     //         gen:gen?gen:0,
//     //         print:print
//     //     },
//     //     success:function(result){
//     //         data = JSON.parse(result).results
//     //         fund_cluster_code = JSON.parse(result).fund_cluster_code
//     //         var object= Object.keys(data)
//     //         console.log(data[1010101000])
//     //         var mywindow = window.open('?r=jev-preparation/ledger', 'new div', 'height=700,width=1300');
//     //         mywindow.document.write('<html><head><title></title>');
//     //         mywindow.document.write('<link rel="stylesheet" href="../web/print.css" type="text/css" media="all" />');

//     //         // mywindow.document.write('<style>');
//     //         // mywindow.document.write('.style1 {font-size:11px; font-weight:bold; color:red; border:1px solid black}');
//     //         // mywindow.document.write('@media print{ .table{page-break-after:auto;} @page{margin:0.3cm;} td{padding:4px;font-size:12px}th{padding:1;font-size:12px}} ');
//     //         // mywindow.document.write('th,td {border: 1px solid black;padding: 10px;background-color: white;margin:0;gap:0;}');
//     //         // mywindow.document.write('table {border-spacing:0;border-collapse: collapse;}');
//     //         // mywindow.document.write('.document_header1 >th {border:0;}');
//     //         // mywindow.document.write('h4 {padding:0;margin:0;}');
//     //         // mywindow.document.write('</style>');
//     //         mywindow.document.write('</head><body >');
//     //         // mywindow.document.write('<img src="../web/dti.jpg" style="width:100px;height:100px;">');

            
//     //         for (var i=0;i<object.length;i++){

//     //             mywindow.document.write("<table class='table' cellspacing='0'><tbody>");
//     //             mywindow.document.write("<thead>");


//     //             mywindow.document.write("<tr class='header_logo' style='margin-bottom:5px;'>");
//     //             mywindow.document.write(" <th></th>");
//     //             mywindow.document.write(" <th colspan='2'> <div style='display:flex'><img src='../web/dti.jpg' style='width:80px;height:80px;margin-left:auto;margin-right:10px''><div style='margin-top:10px;'><h4 style='margin-top:13px'>Department of Trade and Industry</h4><h4>General Ledger</h4><h4>2020</h4></div></div></th>");
//     //             // mywindow.document.write(" <th colspan='2'><h4>Department of Trade and Industry</h4><h4>General Ledger</h4><h4>2020</h4></th>");
               
//     //             mywindow.document.write(" <th colspan='2' style='text-align:center;'>"+''+"</th>");
//     //             mywindow.document.write("</tr>");
                
//     //             mywindow.document.write("<tr class='document_header1'>");
//     //             mywindow.document.write(" <th>Entity Name:</th>");
//     //             mywindow.document.write(" <th colspan='1'>DEPARTMENT OF TRADE AND INDUSTRY</th>");
//     //             mywindow.document.write(" <th colspan='2'>Fund Cluster:</th>");
//     //             mywindow.document.write(" <th colspan='2' style='text-align:center;'>"+fund_cluster_code+"</th>");
//     //             mywindow.document.write("</tr>");


//     //             mywindow.document.write("<tr class='document_header1'>");
//     //             mywindow.document.write(" <th>Account Title:</th>");
//     //             mywindow.document.write(" <th>"+data[object[i]][0]['general_ledger']+"</th>");
//     //             mywindow.document.write(" <th colspan='2'>UACS Object Code</th>");
//     //             mywindow.document.write(" <th colspan='2'>"+object[i]+"</th>");
//     //             mywindow.document.write("</tr>");


//     //             mywindow.document.write("<tr>");
//     //             mywindow.document.write("<th>Date</th>");
//     //             mywindow.document.write("<th>Particular:</th>");
//     //             mywindow.document.write("<th>Reference No</th>");
//     //             // mywindow.document.write("<th>Amount</th>");
//     //             mywindow.document.write("<th>Debit</th>");
//     //             mywindow.document.write("<th>Credit</th>");
//     //             mywindow.document.write("<th>Balance</th>");
//     //             mywindow.document.write("</tr>");

              
//     //             mywindow.document.write("</thead>");
//     //             // mywindow.document.write(object[i]);
//     //             // mywindow.document.write('<br>');
//     //             for(var x=0;x<data[object[i]].length;x++){
//     //                 // mywindow.document.write(data[object[i]][x]['reporting_period']);
//     //                 // mywindow.document.write('<br>');
//     //                 // mywindow.document.write(object[i]);
//     //                 // var bal= roundOff(data[object[i]][x]['balance'],2)

//     //                 // var y= Number(Math.round(data[object[i]][x]['balance']+'e2')+'e-2')
//     //                 var bal = thousands_separators(data[object[i]][x]['balance'])
//     //                 var debit =data[object[i]][x]['debit']>0? thousands_separators(data[object[i]][x]['debit']):''
//     //                 var credit = data[object[i]][x]['credit']>0?thousands_separators(data[object[i]][x]['credit']):''
                 
                    
//     //                 mywindow.document.write("<tr> ");
//     //                 // mywindow.document.write("<td>"+data[object[i]][x]['reporting_period']+"</td>");
//     //                 mywindow.document.write("<td></td>");
//     //                 mywindow.document.write("<td>"+data[object[i]][x]['explaination']+"</td>");
//     //                 mywindow.document.write("<td>"+data[object[i]][x]['uacs']+"</td>");
//     //                 mywindow.document.write("<td style='text-align:right;' >"+debit+"</td>");
//     //                 mywindow.document.write("<td style='text-align:right;'>"+credit+"</td>");
//     //                 mywindow.document.write("<td style='text-align:right;'>"+bal +"</td>");
//     //                 mywindow.document.write("</tr>");


//     //             }
//     //             mywindow.document.write("</tbody></table>");
//     //             mywindow.document.write("<p style='page-break-after:always;'></p>");

//     //         }
//     //         mywindow.document.write('</body></html>');
//     //         mywindow.document.close();
//     //         mywindow.focus();
//     //         setTimeout(function(){ mywindow.print(); mywindow.close(); },1000);
//     //         // mywindow.print()
//     //         print=0
//     //     }
//     // });
//     // }



// });
// // function PrintElem(elem) {
//     Popup($('#'+elem).html());
// }
// function Popup(data) {
//     var mywindow = window.open('', 'new div', 'height=700,width=1300');
//     mywindow.document.write('<html><head><title></title>');
//     mywindow.document.write('<link rel="stylesheet" href="/css/budgetprint.css" type="text/css" media="all" />');
//     mywindow.document.write('</head><body >');
//     mywindow.document.write(data);
//     mywindow.document.write('</body></html>');
//     mywindow.document.close();
//     mywindow.focus();
//     setTimeout(function(){ mywindow.print(); mywindow.close(); },1000);
//     return true;
// }




JS;
$this->registerJs($script);
?>