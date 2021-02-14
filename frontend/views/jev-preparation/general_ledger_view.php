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

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Ledger';
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
        <table class="table" style="margin-top:30px">
            <thead>
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

                <tr class="header" style="border:none;">
                    <td colspan="3" style="border: none;">
                        <span>
                            Account Title:

                        </span>
                        <span id="ledger">

                        </span>
                    </td>
                    <!-- <td id="ledger" colspan="3">

                    </td> -->

                    <td colspan="3" style="border: none;">
                        <span>
                            UACS Object Code:

                        </span>
                        <span id="uacs">
                        </span>
                    </td>
                    <!-- <td colspan="2" >
                    </td> -->

                </tr>
                <tr style="border-top:1px solid black">
                    <td style="border-top:1px solid black">
                        Reporting Period
                    </td>
                    <td style="border-top:1px solid black">
                        Particulars
                    </td>
                    <td style="border-top:1px solid black">
                        Reference
                    </td>
                    <td style="border-top:1px solid black">
                        Debit
                    </td>
                    <td style="border-top:1px solid black">
                        Credit
                    </td>
                    <td style="border-top:1px solid black">
                        Balance
                    </td>
                </tr>
            </thead>
            <tbody id="ledgerTable">
                    
         


            </tbody>
        </table>
    </div>
    <style>
        #reporting_period {
            background-color: white;
            border-radius: 3px;
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

            @page {
                size: auto;
                margin: 0;
                margin-top: 6cm;
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
    var title=""
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

    function query(){
        // console.log(fund+gen)
        // console.log(fund)
        $.ajax({
            type: "POST",
            url:   window.location.pathname + '?r=jev-preparation/ledger',
            data: {
                fund:fund?fund:0,
                gen:gen?gen:'',
                reporting_period:reporting_period?''+reporting_period.toString():'',
            },
            success: function(msg){
                var data= JSON.parse(msg)
                // console.log(data)

                var result = data.results
                var balance = data.balance

               
                let table = document.getElementById('ledgerTable');
                table.innerHTML =  displayData(balance) +displayData(result)
               
                },
            error: function(xhr){
            alert("failure"+xhr.readyState+this.url)
            }
    });
    }
    function thousands_separators(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
    function displayData(result){
        console.log(result)

      

        var x='';
        if (result.length>0 &&gen!=null){
            document.getElementById('uacs').innerHTML=result[0].uacs
            document.getElementById('ledger').innerHTML=result[0].general_ledger


        }
        if (result.length>0 &&fund!=null){
            document.getElementById('fund_cluster').innerHTML=result[0].fund_cluster_code
                
        }
        for( var i=0;i<result.length;i++){
            
        var row="<tr>"  
        if (i>0){
            if (result[i-1].reporting_period !=result[i].reporting_period ){
                row+="<td>"+result[i].reporting_period+ "</td>"
            
            }
            else{
                row+="<td>"+''+ "</td>"
            }
        

        }else if (i==0){
            row+="<td>"+result[i].reporting_period+ "</td>"

        }
                row+="<td>"+result[i].explaination+ "</td>"
                if (result[i].ref_number ==null){
                    row+="<td>"+''+"</td>"
                }
                else{
                    row+="<td>"+result[i].ref_number+"</td>"
                }
                row+="<td>"+ thousands_separators(result[i].debit)+"</td>"
                row+="<td>"+thousands_separators(result[i].credit)+ "</td>"
                
                if (result[i].credit!=0){
                    row+="<td>"+result[i].credit+"</td>"
                }
                else if (result[i].debit!=0){
                    row+="<td>"+result[i].debit+"</td>"
                }
                else{
                    row+="<td>"+''+"</td>"
                }

                
                row+="</tr>"
                x+=row
        }
        return x
        
    }

})

JS;
$this->registerJs($script);
?>