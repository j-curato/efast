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
        <div class="actions " style="bottom: 20px;">


            <form id="filter">
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
                <div class="col-sm-3">
                    <label for="book"> Books</label>
                    <?php
                    echo Select2::widget([
                        'data' => ['mds'=>'MDS','lcca'=>'LCCA','all'=>'All'],
                        'id' => 'book',
                        'name' => 'book_id',
                        'options' => ['placeholder' => 'Select a Fund Cluster Code'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="entry_type"> Entry Type</label>
                    <?php
                    echo Select2::widget([
                        'data' => ['Post-Closing' => 'Post-Closing', 'Pre-Closing' => 'Pre-Closing', 'Closing' => 'Closing'],
                        'id' => 'entry_type',
                        'name' => 'entry_type',
                        'options' => ['placeholder' => 'Select Entry Type'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-sm-2">
                    <button class="generate btn btn-success" style="margin-top: 25px;" type='submit'>Generate</button>
                </div>
            </form>
        </div>



        <table id="data_table">
            <thead>
                <tr class="header" style="border: none;">

                    <td colspan="5">


                        <div style="width: 100%; display:flex;align-items:center; justify-content: center;">
                            <div style="margin-right: 20px;left:-10px">
                                <?= Html::img('frontend/web/dti.jpg', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;']); ?>
                            </div>
                            <div style="text-align:center;" class="headerItems">
                                <h6>DEPARTMENT OF TRADE AND INDUSTRY</h6>
                                <h6>CARAGA REGIONAL OFFICE</h6>
                                <h6><span class="entry_type_heading"></span> <span>Trial Balance</span> <span class="book_name"></span>

                                </h6>
                                <h6>As of <span id="month"></span>

                            </div>

                        </div>

                    </td>


                </tr>
                <tr class="header" style="border: none;">
                    <td colspan="3" style="border: none;">
                        <span>
                            Entity Name:
                        </span>
                        <span>
                            DEPARTMENT OF TRADE AND INDUSTRY - CARAGA
                        </span>
                    </td>

                    <td colspan="3" style="border: none;">
                        <span>
                            Fund Cluster:
                        </span>
                        <span class="book_name"></span>
                    </td>


                </tr>


                <tr style="border-top:1px solid black">


                    <td style="border-top:1px solid black">
                        Account Name
                    </td>
                    <td style="border-top:1px solid black">
                        Code
                    </td>

                    <td style="border-top:1px solid black">
                        Debit
                    </td>
                    <td style="border-top:1px solid black">
                        Credit
                    </td>

                </tr>
            </thead>
            <tbody></tbody>
        </table>


    </div>
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <style>
        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .headerItems>h6 {
            font-weight: bold;
        }

        .amount {
            text-align: right;
        }


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

        #bars1 {
            display: none;
        }

        .table {
            display: none;
        }
    </style>

</div>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/thousands_separator.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        $('#filter').submit(function(e) {
            $("#data_table tbody").html('')
            $('.entry_type_heading').text($('#entry_type').val())
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=report/trial-balance',
                data: $('#filter').serialize(),
                success: function(data) {
                    var res = JSON.parse(data)
                    // console.log(res)
               
                    displayResultData(res.result)
                    $('#month').text(res.month)
                    $('.book_name').text(res.book_name)
                }
            })
        })
    })

    function displayResultData(data) {
        console.log(data)

        let total_debit = 0;
        let total_credit = 0;
        $.each(data, function(index, val) {

            let debit = '';
            let credit = '';
            let beginning_balance = val.begin_balance != null ? parseFloat(val.begin_balance) : 0;
            // let total_debit_credit = val.total_debit_credit != null ? parseFloat(val.total_debit_credit) : 0;
            let total = val.total_debit_credit != null ? parseFloat(val.total_debit_credit) : 0;
            // let total = beginning_balance + total_debit_credit;
            if (val.normal_balance == null) {
                debit = 'No Normal Balance'
                credit = 'No Normal Balance'
            } else if (val.normal_balance.toLowerCase() == 'debit') {

                if (total < 0) {
                    var total_value = Math.abs(total)
                    credit = thousands_separators(total_value.toFixed(2))
                    total_credit += total_value
                } else {
                    debit = thousands_separators(total.toFixed(2))
                    total_debit += total
                }
            } else if (val.normal_balance.toLowerCase() == 'credit') {

                if (total < 0) {
                    var total_value = Math.abs(total)
                    debit = thousands_separators(total_value.toFixed(2))
                    total_debit += total_value
                } else {
                    credit = thousands_separators(total.toFixed(2))
                    total_credit += total
                }
            }

            var row = `<tr>
                <td>${val.account_title}</td>
                <td>${val.object_code}</td>
                <td class='amount'>${debit}</td>
                <td class='amount'>${credit}</td>
            </tr>`
            $("#data_table tbody").append(row)
        })
        console.log(total_debit,
            total_credit)
        var q = `<tr>
                <td>Total</td>
                <td></td>
                <td class='amount'>${thousands_separators(total_debit.toFixed(2))}</td>
                <td class='amount'>${thousands_separators(total_credit.toFixed(2))}</td>
            </tr>`;
        $("#data_table tbody").append(q)
    }
</script>