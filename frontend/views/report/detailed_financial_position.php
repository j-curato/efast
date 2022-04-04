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
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();
    $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/sample';
    $sub1 = (new \yii\db\Query())->select('*')->from('sub_accounts1')->all();

    ?>


    <div class="container panel panel-default">

        <div>

            <div class="row " style="bottom: 20px;">
                <div class="col-sm-3">
                    <label for="book"> Books</label>
                    <?php
                    echo Select2::widget([
                        'data' => ArrayHelper::map($books, 'id', 'name'),
                        'id' => 'book_id',
                        'name' => 'book_id',
                        'options' => ['placeholder' => 'Select a Book'],
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
                    <button class="btn btn-success" id="generate" type="button">Generate</button>
                </div>

            </div>


            <br>
            <table class="table" id='data-table' style="margin-top:30px">
                <thead>
                    <tr class="main_header">
                        <th colspan="5">
                            <div style="display:flex;width:100%;align-items:center;" class="main_head">
                                <div style="padding:12px;">

                                </div>
                                <div style="margin-left:auto;margin-right:auto">
                                    <h5>
                                        DEPARTMENT OF TRADE AND INDUSTRY -CARAGA
                                    </h5>
                                    <h5>
                                        DETAILED STATEMENT OF FINANCIAL POSITION
                                    </h5>
                                    <h5>
                                        FUND CLUSTER
                                    </h5>
                                    <h5>
                                        AS OF <span id="r_period"></span>
                                    </h5>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th><span id="current_year"></span></th>
                        <th><span id="last_year"></span></th>

                    </tr>

                </thead>
                <tbody>


                </tbody>
            </table>

        </div>
        <style>
            .right-border {
                border-right: 1px solid transparent;
                font-weight: bold;
            }

            .main_head>div>h5 {
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

                #print {
                    display: none;
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



                .main-footer {
                    display: none;
                }
            }
        </style>

    </div>

    <?php
    $this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/thousands_separator.js", ['depends' => [\yii\web\JqueryAsset::class]]);
    ?>
    <script>
        $(document).ready(function() {

            $('#generate').click(function() {
                $.ajax({
                    type: 'POST',
                    url: window.location.href,
                    data: {
                        reporting_period: $("#reporting_period").val(),
                        book_id: $("#book_id").val()
                    },
                    success: function(data) {
                        const res = JSON.parse(data)
                        displaData(res.result)
                        $("#current_year").text(res.current_year)
                        $("#last_year").text(res.last_year)
                        $("#r_period").text(res.reporting_period)
                    }
                })
            })
        })

        function displaData(data) {
            console.log(data)
            $('#data-table tbody').html('')
            $.each(data, function(key, val) {
                const account_group_row = `<tr>
                <td>${key}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>`
                $('#data-table tbody').append(account_group_row)
                $.each(val, function(key, val2) {
                    const major_row = `<tr>
                                            <td></td>
                                            <td>${key}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>`
                    $('#data-table tbody').append(major_row)
                    $.each(val2, function(key, val3) {
                        const current_balance = val3.current_balance
                        const prev_balance = val3.last_year_balance
                        const data_row = `<tr>
                                            <td></td>
                                            <td></td>
                                            <td style='text-align:left;'>${val3.general_ledger}</td>
                                            <td style='text-align:right;'>${thousands_separators(current_balance)}</td>
                                            <td style='text-align:right;'>${thousands_separators(prev_balance)}</td>
                                            `
                        $('#data-table tbody').append(data_row)
                    })
                })
            })
        }
    </script>