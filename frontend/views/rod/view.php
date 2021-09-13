<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "ROD";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rod-view" style="background-color: white;padding:20px;display:none">




    <?php
    $provinces  = [
        'adn' => 'Agusan Del Norte',
        'ads' => 'Agusan Del Sur',
        'sdn' => 'Surigao Del Norte',
        'sds' => 'Surigao Del Sur',
        'pdi' => 'Province of Dinagat Islands',
    ];
    echo "<input type='hidden' value = '$model->rod_number' id='rod_number'/>";
    echo "<input type='hidden' value='view' name = 'action_type'>";

    ?>

    <!-- <div id="con"> -->

    <div id='con'>

        <table class="" id="rod_table" style="margin-top: 30px;">

            <thead>
                <tr>

                    <th colspan="6">
                        <div class="head" style="margin-left: auto;margin-right:auto;text-align:center;">
                            <h5 style="font-weight: bold;">REPORT OF DISBURSEMENTS</h5>
                            <h6> Department of Trade and Industry</h6>
                            <h6 id="prov"> Provincial Office of <?php echo  $provinces[$model->province] ?> </h6>
                        </div>
                    </th>
                </tr>
                <tr>

                    <th colspan="4">Period Covered:</th>
                    <th colspan="2">Report No.:</th>
                </tr>
                <tr>

                    <th colspan="4"></th>
                    <th colspan="2">Sheet No.:</th>
                </tr>

                <th>Date</th>
                <th>DV/Payroll No.</th>
                <th>Responsibility Center Code</th>
                <th>Payee</th>
                <th>Nature of Payment</th>
                <th class="amount">Amount</th>

            </thead>
            <tbody>
                <tr id="start"></tr>
                <tr>
                    <td colspan="5">
                        Total
                    </td>
                    <td class='amount' id="total_amount">

                    </td>

                </tr>
                <tr>
                    <td colspan="6">
                        CERTIFICATION
                    </td>

                </tr>
                <tr>


                    <td colspan="6">
                        <h1 id="pageCounter">
                        </h1>
                        <span>
                            I herby certify that this Report of Disbursemets in <span class="total"></span> sheet is a full, true and correct statement of the disbursements made by
                            me and that this is in liquidation of the following cash advances granted to the Provincial Office, to with:
                        </span>
                        <table id="fund_source_table" style="margin-left:auto;margin-right:auto;margin-top :2rem">

                            <thead>
                                <th>Fund Source</th>
                                <th>Check Number</th>
                                <th>Check Date</th>
                                <th>Fund Source Amount</th>
                                <th>Total Disbursed</th>
                                <th>Balance</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-right:none">
                        <span>______________</span>
                        <br>
                        <span>Disbursing Officer</span>
                    </td>
                    <td colspan="3" style="text-align: center; border-left:none">
                        <span>______________</span>
                        <br>
                        <span>Date</span>
                    </td>
                </tr>

            </tbody>



        </table>


    </div>
    <!-- </div> -->



</div>
<div id="dots5" style="display: none;">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
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
        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }

        .rod-view {
            padding: 0;
        }

 


    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile(yii::$app->request->baseUrl . "/frontend/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<?php
SweetAlertAsset::register($this);
$script = <<< JS
  const size = 1122;
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
        // console.log(seconds)
    })

    if ($('#rod_number').val() !=''){ 
        // $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=rod/get-rod',
            data:{
                rod_number:$("#rod_number").val(),
                action_type:'view'
            },
            success:function(data){
                var res = JSON.parse(data)
                var liquidation = res.liquidations
                var conso_fund_source = res.conso_fund_source
                addData(liquidation)
                fundSource(conso_fund_source)
                var _docHeight = (document.height !== undefined) ? document.height : document.body.offsetHeight;
                var table = $("#rod_table");
                // alert(table.offsetHeight);
                var thead = $('#rod_table thead')
                var qwe = 0;
                var pages = Math.ceil(table.innerHeight() / size)
                var table_size = parseFloat(table.innerHeight(), 2)
                var thead_size = parseFloat(thead.innerHeight(), 2)
                if (pages > 1) {
                    qwe = table_size + (thead_size * pages);
                }
                console.log(table.innerHeight())
                $('.total').text(Math.ceil(parseFloat(table_size, 2) / size))
                setTimeout(() => {
                    $('#dots5').hide()
                    $('.rod-view').show()
                }, 1000);
             
            }
      
        })
    }

    function fundSource(conso_fund_source){
        console.log(conso_fund_source)
        $("fund_source_table tbody").html('');
        for (var i = 0; i<conso_fund_source.length;i++){
            row =  `<tr class='data_row'>
                        <td>`+conso_fund_source[i]['fund_source']+`</td>
                        <td>`+conso_fund_source[i]['check_or_ada_no']+`</td>
                        <td>`+conso_fund_source[i]['issuance_date']+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(conso_fund_source[i]['amount']))+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(conso_fund_source[i]['total_withdrawals']))+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(conso_fund_source[i]['balance']))+`</td>
                        </tr>`
                $('#fund_source_table').append(row)
        }        
    }
    function addData(rod) {
        $(".data_row").remove();
        var total = 0
        for (var x = 0;x<rod.length;x++){
                row =  `<tr class='data_row'>
                        <td>`+rod[x]['check_date']+`</td>
                        <td >`+rod[x]['dv_number']+`</td>
                        <td >`+rod[x]['reponsibility_center_name']+`</td>
                        <td >`+rod[x]['payee']+`</td>
                        <td></td>
                        <td class='amount'>`+thousands_separators(parseFloat(rod[x]['withdrawals']))+`</td>
                        </tr>`
                $('#rod_table').find('#start').after(row)
            total +=parseFloat(rod[x]['withdrawals'])
        }
      
        $('#total_amount').text(  thousands_separators(total))


    }






JS;
$this->registerJs($script);
?>