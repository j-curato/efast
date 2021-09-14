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
<div class="rod-view" style="background-color: white;padding:10px;display:none">

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
        <button id="print_btn" type='button' class='btn btn-succes  fa fa-print print_btn'></button>
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
                        <span>
                            I hereby certify that this Report of Disbursemets in ___ sheet is a full, true and correct statement of the disbursements made by
                            me and that this is in liquidation of the following cash advances granted to the Provincial Office, to wit:
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

        @page {
            size: A4;
            margin: 10mm;
        }


        .main-footer {
            display: none;
        }

        .rod-view {
            padding: 0;
        }

        .print_btn {
            display: none;
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
    var liquidation_data = []
    var print_conso_fund_source = []
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
                liquidation_data = res.group_liquidation
                var conso_fund_source = res.conso_fund_source
                 print_conso_fund_source = res.conso_fund_source
                addData(liquidation)
                fundSource(conso_fund_source)
                console.log(liquidation_data)
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
    $('#print').click(function(){

        var total = 0
        var css_url = window.location.pathname + '/frontend/web/css/rod_print.css' 
        var mywindow = window.open('?r=jev-preparation/ledger', 'new div', 'height=700,width=1300');
            mywindow.document.write('<html><head><title></title>');
            mywindow.document.write('<link rel="stylesheet" href="/afms/frontend/web/css/rod_print.css" type="text/css" media="all" />');

            mywindow.document.write('<style>');
            mywindow.document.write('@media print {@page{size:A4;margin-top;20px}}');
            mywindow.document.write('th,td {border: 1px solid black;padding: 10px;background-color: white;margin-top:30px;gap:0;}');
            mywindow.document.write('</style>');
            mywindow.document.write('</head><body >');
            // mywindow.document.write('<img src="../web/dti.jpg" style="width:100px;height:100px;">');

            var i = 0
            var sheet_number = 0
            for (var x=0;x<liquidation_data.length;x++){
                sheet_number++
                mywindow.document.write("<table cellspacing='0' >");
                mywindow.document.write("<thead>");
                    mywindow.document.write("<tr> ");
                        mywindow.document.write("  <th colspan='6'>")
                            mywindow.document.write("<div class='head' style='margin-left: auto;margin-right:auto;text-align:center;'>")
                            mywindow.document.write("<h5 style='font-weight: bold;'>REPORT OF DISBURSEMENTS</h5>")
                            mywindow.document.write("<h6> Department of Trade and Industry</h6>")
                            mywindow.document.write("<h6 id='prov'> Provincial Office of  </h6>")
                            mywindow.document.write("</div>")
                        mywindow.document.write(" </th>")
                    mywindow.document.write("</tr>");

                    mywindow.document.write("<tr>")
                        mywindow.document.write(" <th colspan='4'>Period Covered:</th>")
                        mywindow.document.write("<th colspan='2'>Report No.:</th>")
                    mywindow.document.write("</tr>")

                    mywindow.document.write("<tr>")
                        mywindow.document.write("<th colspan='4'></th>")
                        mywindow.document.write("<th colspan='2'>Sheet No.:"+sheet_number+"</th>")
                    mywindow.document.write(" </tr>")

                    mywindow.document.write(" <th>Date</th>")
                    mywindow.document.write("<th>DV/Payroll No.</th>")
                    mywindow.document.write("<th>Responsibility Center Code</th>")
                    mywindow.document.write("<th>Payee</th>")
                    mywindow.document.write("<th>Nature of Payment</th>")
                    mywindow.document.write("<th class='amount'>Amount</th>")
                mywindow.document.write("</thead>");
  
              
                mywindow.document.write("<tbody>");
                    for(var y= 0 ;y<liquidation_data[x].length;y++){

                        mywindow.document.write("<tr >")
                            mywindow.document.write("<td>"+liquidation_data[x][y]['check_date']+"</td>")
                            mywindow.document.write("<td >"+liquidation_data[x][y]['dv_number']+"</td>")
                            mywindow.document.write("<td >"+liquidation_data[x][y]['reponsibility_center_name']+"</td>")
                            mywindow.document.write("<td >"+liquidation_data[x][y]['payee']+"</td>")
                            mywindow.document.write("<td></td>")
                            mywindow.document.write("<td class='amount'>"+thousands_separators(parseFloat(liquidation_data[x][y]['withdrawals']))+"</td>")
                        mywindow.document.write("</tr>");
                        total +=parseFloat(liquidation_data[x][y]['withdrawals'],2)
                    }
              
                    if (x+1 ==liquidation_data.length){
                                 mywindow.document.write("<tr>")
                                    mywindow.document.write("<td colspan='5'>Total</td>")
                                    mywindow.document.write("<td class='amount' >"+thousands_separators(total.toFixed(2))+"</td>")
                                 mywindow.document.write("</tr>")
                        mywindow.document.write("<tr>")
                             mywindow.document.write("<td colspan='6'>")
                                 mywindow.document.write("<span>")
                                mywindow.document.write("I herby certify that this Report of Disbursemets in <span class='total'></span> sheet is a full, true and correct statement of the disbursements made by")
                                 mywindow.document.write("me and that this is in liquidation of the following cash advances granted to the Provincial Office, to with:")
                                 mywindow.document.write("</span>")
                                 mywindow.document.write("<table  cellspacing='0'  style='margin-left:auto;margin-right:auto;margin-top :2rem'>")
                                   mywindow.document.write("  <thead>")
                                       mywindow.document.write("  <th>Fund Source</th>")
                                       mywindow.document.write("  <th>Check Number</th>")
                                        mywindow.document.write(" <th>Check Date</th>")
                                        mywindow.document.write(" <th>Fund Source Amount</th>")
                                        mywindow.document.write(" <th>Total Disbursed</th>")
                                        mywindow.document.write(" <th>Balance</th>")
                                    mywindow.document.write(" </thead>")
                                    mywindow.document.write(" <tbody>")
                                    for (var i = 0; i<print_conso_fund_source.length;i++){
                               
                                        mywindow.document.write("<tr>")
                                            mywindow.document.write("<td>"+print_conso_fund_source[i]['fund_source']+"</td>")
                                            mywindow.document.write("<td>"+print_conso_fund_source[i]['check_or_ada_no']+"</td>")
                                            mywindow.document.write("<td>"+print_conso_fund_source[i]['issuance_date']+"</td>")
                                            mywindow.document.write("<td class='amount'>"+thousands_separators(parseFloat(print_conso_fund_source[i]['amount']))+"</td>")
                                            mywindow.document.write("<td class='amount'>"+thousands_separators(parseFloat(print_conso_fund_source[i]['total_withdrawals']))+"</td>")
                                            mywindow.document.write("<td class='amount'>"+thousands_separators(parseFloat(print_conso_fund_source[i]['balance']))+"</td>")
                                          mywindow.document.write("</tr>")
                                    }  
                                    mywindow.document.write(" </tbody>")
                                 mywindow.document.write("</table>")
                             mywindow.document.write("</td>")
                            mywindow.document.write(" </tr>")
                    }
                    mywindow.document.write("</tbody></table>");
                    mywindow.document.write("<p style='page-break-after:always;'></p>");

           
            }
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            mywindow.focus();
            setTimeout(function(){ mywindow.print(); mywindow.close(); },1000);
            // mywindow.print()
            print=0
    })

    






JS;
$this->registerJs($script);
?>