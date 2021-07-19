<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\MajorAccounts;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "FUR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">
            <div class="col-sm-3">
                <label for="reporting_period">Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-2">
                <label for="province">Province</label>
                <?php
                echo Select2::widget([
                    'name' => 'province',
                    'id' => 'province',
                    'data' => [
                        'adn' => 'ADN',
                        'ads' => 'ADS',
                        'sdn' => 'SDN',
                        'sds' => 'SDS',
                        'pdi' => 'PDI',
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Province'
                    ]
                ])

                ?>
            </div>
            <div class="col-sm-3" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>

    </form>

    <!-- <div id="con"> -->

    <div id='con'>
        <div class="head" style="margin-left: auto;margin-right:auto;text-align:center;">
            <h5 style="font-weight: bold;">Fund Utilization Report</h5>
            <h6 id="period"></h6>
            <h6 id="prov"></h6>
        </div>
        <table id="conso_fur_table" style="margin-top:20px;">
            <thead>
                <th>Report Type</th>
                <th>Beginning Balance</th>
                <th>Fund Recieved for the Month</th>
                <th>TOtal Disbursement for the Month</th>
                <th>Ending Balance</th>
            </thead>
            <tbody>


            </tbody>
        </table>
        <table class="" id="fur_table" style="margin-top: 30px;">

            <thead>
                <th>Fund Source</th>
                <th>Beginning Balance</th>
                <th class="amount">Cash Advance for the month</th>
                <th class="amount">Total Liquidation For the Month</th>
                <th class="amount">Ending Balance</th>
                <th>Particulars</th>
                <th>Advance Type</th>
                <th>SL Account Code</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- </div> -->
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }
    #con{
        display: none;
    }
    .amount {
        text-align: right;
    }

    @media print {

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>

</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
    function thousands_separators(num) {

    var number = Number(Math.round(num + 'e2') + 'e-2')
    var num_parts = number.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
    }

    var month= ''
    var year=''
    var province={
        'adn' : 'Agusan Del Norte',
        'ads' : 'Agusan Del Sur',
        'sdn' : 'Surigao Del Norte',
        'sds' : 'Surigao Del Sur',
        'pdi' : 'Province of Dinagat Islands',
    }

    $(document).ready(()=>{
        var startDate = new Date(2021,07,13, 09,17,04);
//         date2 = new Date( date1 );
// // Do your operations
        var endDate   = new Date(2021,07,12, 18,39,09);
        var seconds = (endDate.getTime() - startDate.getTime())
        var diff =seconds/ 60;
        console.log(seconds)
    })
    $('#filter').submit((e)=>{
        e.preventDefault();
        var reporting_period = new Date($('#reporting_period').val())
        month = reporting_period.toLocaleString('default',{month:'long'})
        year = reporting_period.getFullYear()
        console.log(province['adn'])
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=report/get-fur',
            data:$("#filter").serialize(),
            success:function(data){
                var res = JSON.parse(data)
                var conso_fur = res.conso_fur
                var fur = res.fur
                $("#period").text('For the Period of '+ month+','+year)
                $('#prov').text('Province of '+province[$('#province').val()])
                addData(fur,conso_fur)
                console.log(res)
                $('#dots5').hide()
                $('#con').show()
            }
      
        })
    })
    function addData(fur, conso_fur) {
        $("#conso_fur_table > tbody").html("");
        $("#fur_table > tbody").html("");
        var report_type = ''
        var b_balance = ''
        var f_total_recieve = ''
        var f_total_disbursements = ''
        var ending_balance =0
        var row
        var total_conso_fur_b_balance=0
        var total_conso_fur_f_total_recieve=0
        var total_conso_fur_f_total_disbursements=0
        var total_conso_fur_ending_balance=0
        for (var i = 0; i < conso_fur.length; i++) {
            report_type = conso_fur[i]['report_type']
            b_balance = conso_fur[i]['b_balance']!=null?conso_fur[i]['b_balance']:0
            f_total_recieve = conso_fur[i]['f_total_recieve']
            f_total_disbursements = conso_fur[i]['f_total_disbursements']
            ending_balance = f_total_recieve-f_total_disbursements
            total_conso_fur_b_balance += parseFloat(b_balance,2) 
            total_conso_fur_f_total_recieve += parseFloat(f_total_recieve,2)
            total_conso_fur_f_total_disbursements += parseFloat(f_total_disbursements,2)
            total_conso_fur_ending_balance += parseFloat(ending_balance,2)
             row = `<tr>
                    <td>`+report_type+`</td>
                    <td class='amount'>`+thousands_separators(b_balance)+`</td>
                    <td class='amount'>`+thousands_separators(f_total_recieve)+`</td>
                    <td class='amount'>`+thousands_separators(f_total_disbursements)+`</td>
                    <td class='amount'>`+thousands_separators(ending_balance)+`</td>
                    </tr>
                    `
            $('#conso_fur_table tbody').append(row)
        }
        row = `<tr>
                    <td>Total</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_b_balance)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_f_total_recieve)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_f_total_disbursements)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_ending_balance)+`</td>
                    </tr>
                    `
        $('#conso_fur_table tbody').append(row)


        var fur_b_balance = 0
        var fur_total_advances = 0
        var fur_total_withdrawals = 0
        var fur_ending_balance=0
        var total_fur_b_balance = 0
        var total_fur_total_advances = 0
        var total_fur_total_withdrawals = 0
        var total_fur_ending_balance=0
   
        for (var x = 0;x<fur.length;x++){
         
                 fur_b_balance = fur[x]['prev_balance']==null?0:fur[x]['prev_balance']
                 fur_total_advances = fur[x]['total_advances']==null?0:fur[x]['total_advances']
                 fur_total_withdrawals = fur[x]['total_withdrawals']==null?0:fur[x]['total_withdrawals']
                 fur_ending_balance=fur_total_advances-fur_total_withdrawals

                 total_fur_b_balance+=parseFloat(fur_b_balance,2)
                 total_fur_total_advances+=parseFloat(fur_total_advances,2)
                 total_fur_total_withdrawals+=parseFloat(fur_total_withdrawals,2)
                 total_fur_ending_balance+=parseFloat(fur_ending_balance,2)
            if (fur_b_balance==0 && fur_total_advances==0 && fur_total_withdrawals==0){

            }
            else{

                row =  `<tr>
                        <td>`+fur[x]['fund_source']+`</td>
                        <td class='amount'>`+thousands_separators(fur_b_balance)+`</td>
                        <td class='amount'>`+thousands_separators(fur_total_advances)+`</td>
                        <td class='amount'>`+thousands_separators(fur_total_withdrawals)+`</td>
                        <td class='amount'> `+thousands_separators(fur_ending_balance)+`</td>
                        <td></td>
                        <td>`+fur[x]['report_type']+`</td>
                        <td></td>
                        </tr>`

                $('#fur_table tbody').append(row)
            }

        }
        row =  `<tr>
                    <td>Total</td>
                    <td class='amount'>`+thousands_separators(total_fur_b_balance)+`</td>
                    <td class='amount'>`+thousands_separators(total_fur_total_advances)+`</td>
                    <td class='amount'>`+thousands_separators(total_fur_total_withdrawals)+`</td>
                    <td class='amount'> `+thousands_separators(total_fur_ending_balance)+`</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>`

            $('#fur_table tbody').append(row)
    }






JS;
$this->registerJs($script);
?>