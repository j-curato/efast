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
$this->params['breadcrumbs'][] = ['label' => 'Furs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fur-view " style="background-color: white;padding:20px ">
    <?php
    echo "<input type='hidden' id='model_id' value='$model->id'/>"
    ?>

    <!-- <div id="con"> -->

    <div id='con'>
        <div class="head" style="margin-left: auto;margin-right:auto;text-align:center;">
            <h5 style="font-weight: bold;">Fund Utilization Report</h5>
            <h6 id="period">For the period of <?= DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F, Y') ?></h6>
            <h6 id="prov"></h6>
        </div>
        <table id="conso_fur_table" style="margin-top:20px;">
            <thead>
                <th>Report Type</th>
                <th>Beginning Balance</th>
                <th>Fund Recieved for the Month</th>
                <th>Total Disbursement for the Month</th>
                <th>Ending Balance</th>
            </thead>
            <tbody>
                <?php
                $cns_bgn_bal_tt = 0;
                $cns_adv_bal_tt = 0;
                $cns_wdt_bal_tt = 0;
                foreach ($items['conso_fur'] as $itm) {
                    $cns_bgn_bal_tt += floatval($itm['begining_balance']);
                    $cns_adv_bal_tt += floatval($itm['total_advances']);
                    $cns_wdt_bal_tt += floatval($itm['total_withdrawals']);
                    echo "<tr>
                    
                            <td>{$itm['report_type']}</td>
                            <td>" . number_format($itm['begining_balance'], 2) . "</td>
                            <td>" . number_format($itm['total_advances'], 2) . "</td>
                            <td>" . number_format($itm['total_withdrawals'], 2) . "</td><td>";
                    echo   number_format((floatval($itm['begining_balance']) + floatval($itm['total_advances'])) - floatval($itm['total_withdrawals']), 2);
                    echo "</td></tr>";
                }
                ?>

            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <td><?= number_format($cns_bgn_bal_tt, 2) ?></td>
                    <td><?= number_format($cns_adv_bal_tt, 2) ?></td>
                    <td><?= number_format($cns_wdt_bal_tt, 2) ?></td>
                    <td><?= number_format(($cns_bgn_bal_tt + $cns_adv_bal_tt) - $cns_wdt_bal_tt, 2) ?></td>

                </tr>
            </tfoot>
        </table>
        <table class="" id="fur_table" style="margin-top: 30px;">

            <thead>
                <th>Fund Source</th>
                <th>Beginning Balance</th>
                <th class="amount">Cash Advance for the month</th>
                <th class="amount">Total Liquidation For the Month</th>
                <th class="amount">Ending Balance</th>
                <th>Particulars</th>
                <th>Report Type</th>
                <th>Object Code</th>
                <th>Account Title</th>
            </thead>
            <tbody>
                <?php

                $bgn_bal_ttl = 0;
                $adv_ttl = 0;
                $wtl_ttl = 0;
                foreach ($items['fur'] as $itm) {
                    $bgn_bal_ttl += floatval($itm['begining_balance']);
                    $adv_ttl += floatval($itm['total_advances']);
                    $wtl_ttl += floatval($itm['total_withdrawals']);
                    $fur_ending_balance = floatval($itm['begining_balance']) + floatval($itm['total_advances']) - floatval($itm['total_withdrawals']);

                    echo   "<tr>

                        <td>{$itm['fund_source']}</td>
                        <td class='amount'>" . number_format($itm['begining_balance'], 2) . "</td>
                        <td class='amount'>" . number_format($itm['total_advances'], 2) . "</td>
                        <td class='amount'>" . number_format($itm['total_withdrawals'], 2) . "</td>
                        <td class='amount'>" . number_format($fur_ending_balance, 2) . "</td>
                        <td>{$itm['particular']}</td>
                        <td>{$itm['report_type']}</td>
                        <td>{$itm['object_code']}</td>
                        <td>{$itm['account_title']}</td>
                        </tr>";
                }
                ?>
            </tbody>
            <tr>
                <td>Total</td>
                <td class='amount'><?= number_format($bgn_bal_ttl, 2) ?></td>
                <td class='amount'><?= number_format($adv_ttl, 2) ?></td>
                <td class='amount'><?= number_format($wtl_ttl, 2) ?></td>
                <td class='amount'> <?= number_format(($bgn_bal_ttl + $adv_ttl) - $wtl_ttl, 2) ?></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    <!-- </div> -->

</div>
<!-- <div id="dots5">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div> -->
<style>
    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
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

        var endDate   = new Date(2021,07,12, 18,39,09);
        var seconds = (endDate.getTime() - startDate.getTime())
        var diff =seconds/ 60;
        console.log(seconds)
        
        // $.ajax({
        //     type:'POST',
        //     url:window.location.pathname +'?r=fur/find-fur',
        //     data:{id:$('#model_id').val()},
        //     success:function(data){
        //         var res = JSON.parse(data)
        //         console.log(res)
        //         var reporting_period = new Date(res.reporting_period)
        //         month = reporting_period.toLocaleString('default',{month:'long'})
        //         year = reporting_period.getFullYear()
        //         $("#period").text('For the Period of '+ month+','+year)
        //                 $('#prov').text('Province of '+province[res.province])
        //         $.ajax({
        //             type:'POST',
        //             url:window.location.pathname +'?r=fur/generate-fur',
        //             data:{
        //                 reporting_period:res.reporting_period,
        //                 province:res.province,
        //                 bank_account_id:res.bank_account_id
        //             },
        //             success:function(data){
        //                 var res = JSON.parse(data)
        //                 var conso_fur = res.conso_fur
        //                 var fur = res.fur
        //                 console.log(res)
       
        //                 addData(fur,conso_fur)
        //                 $('#dots5').hide()
        //                 $('.fur-view').show()
        //             }
      
        //         })
        //     }
        // })
    })

    function addData(fur, conso_fur) {
        $("#conso_fur_table > tbody").html("");
        $("#fur_table > tbody").html("");
        var advances_type = ''
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
            advances_type = conso_fur[i]['report_type']
            b_balance = conso_fur[i]['begining_balance']!=null?conso_fur[i]['begining_balance']:0
            f_total_recieve = conso_fur[i]['total_advances']
            f_total_disbursements = conso_fur[i]['total_withdrawals']
            ending_balance =  (b_balance+f_total_recieve)-f_total_disbursements
            total_conso_fur_b_balance += parseFloat(b_balance,2) 
            total_conso_fur_f_total_recieve += parseFloat(f_total_recieve,2)
            total_conso_fur_f_total_disbursements += parseFloat(f_total_disbursements,2)
            total_conso_fur_ending_balance += parseFloat(ending_balance,2)
            if (b_balance ==0 && f_total_recieve==0 && f_total_disbursements==0){
 
            }
            else{
            row = `<tr>
                    <td>`+advances_type+`</td>
                    <td class='amount'>`+thousands_separators(b_balance)+`</td>
                    <td class='amount'>`+thousands_separators(f_total_recieve)+`</td>
                    <td class='amount'>`+thousands_separators(f_total_disbursements)+`</td>
                    <td class='amount'>`+thousands_separators(ending_balance)+`</td>
                    </tr>
                    `
            $('#conso_fur_table tbody').append(row)
            }
        }
        row = `<tr>
                    <td colspan=''>Total</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_b_balance)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_f_total_recieve)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_f_total_disbursements)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_ending_balance)+`</td>
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
         
                 fur_b_balance = fur[x]['begining_balance']
                 fur_total_advances = fur[x]['total_advances']==null?0:fur[x]['total_advances']
                 fur_total_withdrawals = fur[x]['total_withdrawals']==null?0:fur[x]['total_withdrawals']
                 fur_ending_balance=(parseFloat(fur_b_balance,2)+parseFloat(fur_total_advances,2))-parseFloat(fur_total_withdrawals,2)

                 total_fur_b_balance+=parseFloat(fur_b_balance,2)
                 total_fur_total_advances+=parseFloat(fur_total_advances,2)
                 total_fur_total_withdrawals+=parseFloat(fur_total_withdrawals,2)
                 total_fur_ending_balance+=parseFloat(fur_ending_balance,2)
            if (fur_b_balance==0 && fur_total_advances==0 && fur_total_withdrawals==0){

            }
            else{
                var final_fur_b_balance = fur_b_balance !=0 ?thousands_separators(fur_b_balance):''
                var final_fur_total_advances  = fur_total_advances !=0 ?thousands_separators(fur_total_advances):''
                var final_fur_total_withdrawals = fur_total_withdrawals !=0 ?thousands_separators(fur_total_withdrawals):''
                var final_fur_ending_balance = fur_ending_balance !=0 ?thousands_separators(fur_ending_balance):''
                row =  `<tr>

                        <td>`+fur[x]['fund_source']+`</td>
                        <td class='amount'>`+final_fur_b_balance+`</td>
                        <td class='amount'>`+final_fur_total_advances+`</td>
                        <td class='amount'>`+final_fur_total_withdrawals+`</td>
                        <td class='amount'> `+final_fur_ending_balance+`</td>
                        <td>`+fur[x]['particular']+`</td>
                        <td>`+fur[x]['report_type']+`</td>
                        <td>`+fur[x]['object_code']+`</td>
                        <td>`+fur[x]['account_title']+`</td>
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
                </tr>`

            $('#fur_table tbody').append(row)
    }






JS;
$this->registerJs($script);
?>