<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\FundSourceType;
use app\models\MajorAccounts;
use app\models\MfoPapCode;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "CADADR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">

            <div class="col-sm-3">
                <label for="from_reporting_period"> From Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'from_reporting_period',
                    'id' => 'from_reporting_period',
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
            <div class="col-sm-3">
                <label for="to_reporting_period"> To Reporting Peiod</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'to_reporting_period',
                    'id' => 'to_reporting_period',
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
            <div class="col-sm-3">
                <label for="book"> Books</label>
                <?php
                echo Select2::widget([
                    'name' => 'book',
                    'id' => 'book',
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'name', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div>


            <div class="col-sm-2" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>

    </form>

    <!-- <div id="con"> -->

    <div id='con'>
        <table id="cadadr">
            <thead>

                <tr>
                    <th class='head' colspan="14">CHECKS AND ADVICES TO DEBIT ACCOUNT DISBURSEMENT RECORD</th>
                </tr>
                <tr>
                    <th class='head' colspan="">Entity Name:</th>
                    <th class='head' colspan="7"> DEPARTMENT OF TRADE AND INDUSTRY, REGION 13-CARAGA</th>
                    <th class='head' colspan="2">Month Covered</th>
                    <th class='head' colspan="2">Fund Cluster:</th>
                    <th class='head' colspan="1"> MDS 101</th>
                </tr>
                <tr>
                    <th class='head' colspan="">Bank Name:</th>
                    <th class='head' colspan="7">LAND BANK OF THE PHILIPPINES (MDS ACCT# 101-2036-90014-1)</th>
                    <th class='head' colspan="2">'APRIL 2021</th>
                    <th class='head' colspan="2">Sheet Number:</th>
                    <th class='head' colspan="1"> 15/</th>
                </tr>
                <tr>
                    <th colspan="7">ARLEEN P. PAHAMTANG</th>
                    <th colspan="3">Administrative Officer III</th>
                    <th colspan="3">DTI 13 Caraga-RO</th>
                </tr>
                <tr>
                    <th colspan="7">Accountable Officer</th>
                    <th colspan="3">Official Designation</th>
                    <th colspan="3">Station</th>
                </tr>
                <tr>
                    <th rowspan="" colspan="2 ">NCA/DS/DV/Payroll</th>
                    <th rowspan="" colspan="4 "> Check/ADA</th>
                    <th rowspan="4">Payee</th>
                    <th rowspan="4">UACS</th>
                    <th rowspan="4">Nature of Payment</th>
                    <th rowspan="3" colspan="4">Amount</th>

                </tr>
                <tr>
                    <th rowspan="3"> No. </th>
                    <th rowspan="3"> Date </th>

                </tr>
                <tr>
                    <th colspan="2">Serial Number</th>
                    <th rowspan="2">Date</th>
                    <th rowspan="2">Date Released / Credited</th>


                </tr>
                <tr>
                    <th>Check</th>
                    <th>ADA</th>
                    <th>NCA Received</th>
                    <th>Check Issued</th>
                    <th>ADA Issued</th>
                    <th>NCA/BANK Balance</th>
                </tr>



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
    table{
        margin-top:20px
    }

    #summary_table {
        margin-top: 30px;
    }

    .head {
        border: none;
        padding: 3px;
    }

    /* #con {
        display: none;
    } */

    .amount {
        text-align: right;
    }

    @media print {
        #summary_table {
            margin-top: 0;
        }

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
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    var mfo = []
    var allotment_balances = []
    $('#generate').click((e) => {
        e.preventDefault();
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=report/cadadr',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                // var detailed = res.detailed
                // var conso = res.conso
                // mfo = res.mfo_pap
                // allotment_balances = res.allotments
                displayData(res.results, res.begin_balance, res.adjustment)
                // addToSummaryTable(res.conso_saob)

                setTimeout(() => {
                    $('#con').show()
                    $('#dots5').hide()
                }, 2000);

            }

        })
    })


    function displayData(res, begin_balance, adjustment) {
        $("#cadadr tbody").html('');
        var arr = []
        var balance = parseFloat(begin_balance)
        row = `<tr class='data_row'>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td></td>
                <td colspan='' ></td>
                <td></td>
                <td colspan='' >` + 'Begining Balance' + `</td>
                <td  class='amount'></td>
                <td  class='amount'></td>
                <td class='amount' ></td>
                <td  class='amount'>` + thousands_separators(balance) + `</td>
                  
                </tr>`
        var total_nca_recieve = 0
        var total_check_issued = 0
        var total_ada_issued = 0
        $('#cadadr tbody').append(row)
        for (var i = 0; i < res.length; i++) {
            var data = res[i]
            var dv_number = data['dv_number']
            var dv_date = data['dv_date']
            var payee = data['account_name']
            var ada_issued = parseFloat(data['ada_issued'])
            var ada_number = data['ada_number']
            var book_name = data['book_name']
            var check_issued = parseFloat(data['check_issued'])
            var check_or_ada_no = data['check_or_ada_no']
            var issuance_date = data['issuance_date']
            var particular = data['particular']
            var reporting_period = data['reporting_period']
            var nca_recieve = parseFloat(data['nca_recieve'])
            balance += nca_recieve - (ada_issued + check_issued)
            // if (jQuery.inArray(dv_number, arr) == -1) {
            //     arr.push(dv_number)
            // } else {
            //     dv_number = ''
            //     dv_date = ''
            //     check_or_ada_no = ''
            //     ada_number = ''
            //     particular = ''
            // }
            // console.log(jQuery.inArray(dv_number, arr))
            row = `<tr class='data_row'>
                <td colspan='' >` + dv_number + `</td>
                <td colspan='' >` + dv_date + `</td>
                <td colspan='' >` + check_or_ada_no + `</td>
                <td colspan='' >` + ada_number + `</td>
                <td colspan='' >` + issuance_date + `</td>
                <td></td>
                <td colspan='' >` + payee + `</td>
                <td></td>
                <td colspan='' >` + particular + `</td>
                <td  class='amount'>` + thousands_separators(nca_recieve) + `</td>
                <td  class='amount'>` + thousands_separators(check_issued) + `</td>
                <td  class='amount'>` + thousands_separators(ada_issued) + `</td>
                <td class='amount' >` + thousands_separators(balance) + `</td>
                  
                </tr>`
            $('#cadadr tbody').append(row)
            total_nca_recieve += nca_recieve
            total_check_issued += check_issued
            total_ada_issued += ada_issued

        }
        row = `<tr class='data_row'>
                <td  ></td>
                <td  ></td>
                <td  ></td>
                <td  ></td>
                <td  ></td>
                <td></td>
                <td  ></td>
                <td></td>
                <td style='font-weight:bold' >` + 'Total' + `</td>
                <td  class='amount'>` + thousands_separators(total_nca_recieve.toFixed(2)) + `</td>
                <td  class='amount'>` + thousands_separators(total_check_issued) + `</td>
                <td class='amount' >` + thousands_separators(total_ada_issued) + `</td>
                <td  class='amount'>` + thousands_separators(balance) + `</td>
                  
                </tr>`
        $('#cadadr tbody').append(row)

        if (adjustment.length >= 1) {
            row = `<tr class='data_row'>
        
                <td colspan='13' style='font-weight:bold;background-color:#cccccc'>` + 'Adjustment' + `</td>
  
                  
                </tr>`
            $('#cadadr tbody').append(row)

        }
        for (var adjustment_loop = 0; adjustment_loop < adjustment.length; adjustment_loop++) {

            var adjust_amount = parseFloat(adjustment[adjustment_loop]['amount'])
            var adjust_particular = adjustment[adjustment_loop]['particular']

            var b_balance = balance
            balance = parseFloat(balance.toFixed(2)) + parseFloat(adjust_amount)
            console.log(balance, adjust_amount)
            row = `<tr class='data_row'>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td></td>
                <td colspan='' ></td>
                <td></td>
                <td colspan='' >` + adjust_particular + `</td>
                <td  class='amount'>` + thousands_separators(b_balance.toFixed(2)) + `</td>
                <td  class='amount'></td>
                <td class='amount' >` + thousands_separators(adjust_amount) + `</td>
                <td  class='amount'>` + thousands_separators(balance) + `</td>
                  
                </tr>`
            $('#cadadr tbody').append(row)
        }



    }
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
JS;
$this->registerJs($script);
?>