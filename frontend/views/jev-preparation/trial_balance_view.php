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

$this->title = 'General Journal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">






    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();


    ?>

    <div class="container panel panel-default">
        <div class="actions " style="bottom: 20px;">



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
            <div class="col-sm-5">
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
            <div class="col-sm-2">

                <button class="generate">generate</button>
            </div>

        </div>
        <?php Pjax::begin(['id' => 'journal', 'clientOptions' => ['method' => 'POST']]) ?>
        <table class="table" style="margin-top:30px">
            <thead>
                <tr class="header" style="border: none;">

                    <td colspan="5">


                        <div style="width: 100%; display:flex;align-items:center; justify-content: center;">
                            <div style="margin-right: 20px;left:-10px">
                                <?= Html::img('@web/dti.jpg', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;']); ?>
                            </div>
                            <div style="text-align:center;" class="headerItems">
                                <h6>DEPARTMENT OF TRADE AND INDUSTRY</h6>
                                <h6>CARAGA REGIONAL OFFICE</h6>
                                <h6>TRIAL BALANCE FUND </h6>
                                <h6>As of <?php if (!empty($reporting_period)) {
                                                echo $reporting_period;
                                            } ?> </h6>
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
                        <span id="fund_cluster">

                        </span>
                    </td>


                </tr>


                <tr style="border-top:1px solid black">

                    <td style="border-top:1px solid black">
                        Reporting Period
                    </td>
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

            <tbody id="ledgerTable">
                <?php
                $ttl = 0;
                if (!empty($t_balance)) {
                    foreach ($t_balance as $val) {

                        $ttl += $val['total_debit'];
                        echo "<tr>
                        <td>
                        {$val['reporting_period']}
                        </td>
                            <td>
                            {$val['general_ledger']}
                            </td>
                            <td>
                            {$val['uacs']}

                            </td>
 
                            <td style='text-align:right'>"
                            . number_format($val['total_debit']) .

                            " </td>
                            <td  style='text-align:right'>"
                            . number_format($val['total_credit']) .

                            "</td>

                        </tr>";
                        // foreach ($val->jevAccountingEntries as $entry) {
                        //     echo "<tr>" .
                        //         "<td></td>" .
                        //         "<td></td>" .
                        //         "<td>" . $entry->chartOfAccount->general_ledger . "</td>" .
                        //         "<td>" . $entry->chartOfAccount->uacs . "</td>" .
                        //         "<td>" . number_format($entry->debit, 2)  . "</td>" .
                        //         "<td>" . number_format($entry->credit, 2) . "</td>"

                        //         . "</tr>";
                        // }
                    }
                }
                ?>

                <tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td class="total_amount">
                        <?php if (!empty($debit_total)) {
                            echo $debit_total;
                        }  ?>
                    </td>
                    <td class="total_amount"> 
                        <?php if (!empty($credit_total)) {
                            echo $credit_total;
                        }  ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php Pjax::end() ?>

    </div>
    <style>
        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .headerItems>h6 {
            font-weight: bold;
        }
        .total_amount{
            text-align: right;
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
    </style>

</div>


<?php
SweetAlertAsset::register($this);
$script = <<< JS

$(document).ready(function(){
    let gen = undefined
    let fund = undefined
    let reporting_period=undefined
    var title=""

    $( "#fund" ).on('change keyup', function(){
        fund = $(this).val()
        // console.log(fund)
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
    })
    $(".generate").click(function(){
        if (reporting_period!=null && fund!=null){
            query()

        }
        else{
            swal( {
                title: " Reporting Period and Fund Cluster Code are Required",
                type: "error",
                timer:3000,
                closeOnConfirm: false,
                closeOnCancel: false
            })
        }
    })

    function query(){
        // console.log(fund+gen)
        // console.log(fund)
        $.pjax({
        container: "#journal", 
        url: window.location.pathname + '?r=jev-preparation/trial-balance',
        type:'POST',
        data:{
            reporting_period:reporting_period?reporting_period:'',
            fund:fund?fund:'',
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