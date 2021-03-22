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
                <label for="book"> Books</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map($books, 'id', 'name'),
                    'id' => 'book',
                    'name' => 'book',
                    'options' => ['placeholder' => 'Select a Fund Cluster Code'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-2">
                <button class="generate btn btn-success" style="margin-top: 25px;">Generate</button>
            </div>
-
        </div>
        <?php Pjax::begin(['id' => 'journal', 'clientOptions' => ['method' => 'POST']]) ?>
        <div class="col-sm-2">
            <div id="bars1">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
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
                                <h6>TRIAL BALANCE
                                    <?php if (!empty($fund_cluster_code)) {
                                        echo strtoupper($fund_cluster_code);
                                    } ?>
                                </h6>
                                <h6>As of
                                    <?php if (!empty($reporting_period)) {
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
                            <?php if (!empty($fund_cluster_code)) {
                                echo $fund_cluster_code;
                            }
                            ?>
                        </span>
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

            <tbody id="ledgerTable">

                <?php
                $ttl = 0;
                $d = 0;
                $f = 0;
                $qqq = 0;
                if (!empty($t_balance)) {
                    foreach ($t_balance as $val) {
                        $debit_balance = '';
                        $credit_balance = '';
                        // $debit = $val['total_debit'] ? number_format($val['total_debit']) : '';
                        // $credit = $val['total_credit'] ? number_format($val['total_credit']) : '';

                        // if ($val['total_debit'] > $val['total_credit']) {

                        //     $x = $val['total_debit'] - $val['total_credit'];

                        //     if ($x < 0.00) {
                        //         $debit_balance = number_format($x, 2);
                        //         $qqq=$x;
                        //     }
                        //     $d += $x;
                        // } else if ($val['total_credit'] > $val['total_debit']) {
                        //     $y = $val['total_credit'] - $val['total_debit'];
                        //     $credit_balance = number_format($y, 2);
                        //     $f += $y;
                        // }
                        if (!empty($val['debit']) || !empty($val['credit'])) {

                            echo
                            "<tr>
              
                            <td>
                            {$val['general_ledger']}
                            </td>
                            <td>
                            {$val['uacs']}
                            </td>
                            <td style='text-align:right'>
                            {$val['debit']}
                            </td>
                            <td  style='text-align:right'>
                            {$val['credit']}
                            </td>

                        </tr>";
                        }
                    }
                }
                ?>

                <tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td class="total_amount">
                        <?php
                        if (!empty($debit_total)) {
                            echo number_format($debit_total, 2);
                        }
                        // echo $d;
                        ?>
                    </td>
                    <td class="total_amount">
                        <?php
                        if (!empty($credit_total)) {
                            echo number_format($credit_total, 2);
                        }
                        // echo $f;
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php Pjax::end() ?>

    </div>
    <style>
        #bars1 {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            height: 50px;
            width: 50px;
            margin: -25px 0 0 -25px;
        }

        #bars1 span {
            position: absolute;
            display: block;
            bottom: 10px;
            width: 9px;
            height: 5px;
            background: rgba(0, 0, 0, 0.25);
            -webkit-animation: bars1 1.5s infinite ease-in-out;
            animation: bars1 1.5s infinite ease-in-out;
        }

        #bars1 span:nth-child(2) {
            left: 11px;
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s;
        }

        #bars1 span:nth-child(3) {
            left: 22px;
            -webkit-animation-delay: 0.4s;
            animation-delay: 0.4s;
        }

        #bars1 span:nth-child(4) {
            left: 33px;
            -webkit-animation-delay: 0.6s;
            animation-delay: 0.6s;
        }

        #bars1 span:nth-child(5) {
            left: 44px;
            -webkit-animation-delay: 0.8s;
            animation-delay: 0.8s;
        }

        @keyframes bars1 {
            0% {
                height: 5px;
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                background: rgba(0, 0, 0, 0.25);
            }

            25% {
                height: 30px;
                -webkit-transform: translateY(15px);
                transform: translateY(15px);
                -webkit-transform: translateY(15px);
                transform: translateY(15px);
                background: #000000;
            }

            50% {
                height: 5px;
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                background: rgba(0, 0, 0, 0.25);
            }

            100% {
                height: 5px;
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                background: rgba(0, 0, 0, 0.25);
            }
        }

        @-webkit-keyframes bars1 {
            0% {
                height: 5px;
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                background: rgba(0, 0, 0, 0.25);
            }

            25% {
                height: 30px;
                -webkit-transform: translateY(15px);
                transform: translateY(15px);
                background: #000000;
            }

            50% {
                height: 5px;
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                background: rgba(0, 0, 0, 0.25);
            }

            100% {
                height: 5px;
                -webkit-transform: translateY(0px);
                transform: translateY(0px);
                background: rgba(0, 0, 0, 0.25);
            }
        }

        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .headerItems>h6 {
            font-weight: bold;
        }

        .total_amount {
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

        #bars1 {
            display: none;
        }

        .table {
            display: none;
        }
    </style>

</div>


<?php
SweetAlertAsset::register($this);
$script = <<< JS
$(document).on("pjax:beforeSend",function(){
    $('#bars1').show();
    
//    setTimeout(  $('#bars1').show(),10000)
});
$(document).on('ready pjax:success', function(){
    $('.table').show();
});
$(document).ready(function(){
    let gen = undefined
    let book_id = undefined
    let reporting_period=undefined
    var title=""

    $( "#book" ).on('change keyup', function(){
        book_id = $(this).val()
        // console.log(book_id)
    })
    $("#reporting_period").change(function(){
        reporting_period=$(this).val()
    })
    $(".generate").click(function(){
        if (reporting_period!=null && book_id!=null){
            query()

        }
        else{
            swal( {
                title: " Reporting Period and book_id Cluster Code are Required",
                type: "error",
                timer:3000,
                closeOnConfirm: false,
                closeOnCancel: false
            })
        }
    })

    function query(){
        // console.log(book_id+gen)
        // console.log(book_id)
        $.pjax({
        container: "#journal", 
        url: window.location.pathname + '?r=jev-preparation/trial-balance',
        type:'POST',
        data:{
            reporting_period:reporting_period?reporting_period:'',
            book_id:book_id?book_id:'',
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