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
                console.log(res.results)
                displayData(res.results)
                // addToSummaryTable(res.conso_saob)

                $('#con').show()
                $('#dots5').hide()
            }

        })
    })

    function displayData(res) {
        $("#cadadr tbody").html('');
        var arr = []

        for (var i = 0; i < res.length; i++) {
            var data = res[i]
            var dv_number = data['dv_number']
            var dv_date = data['dv_date']
            var payee = data['account_name']
            var ada_issued = data['ada_issued']
            var ada_number = data['ada_number']
            var book_name = data['book_name']
            var check_issued = data['check_issued']
            var check_or_ada_no = data['check_or_ada_no']
            var issuance_date = data['issuance_date']
            var particular = data['particular']
            var reporting_period = data['reporting_period']
            if (jQuery.inArray(dv_number, arr) == -1) {
                arr.push(dv_number)
            } else {
                dv_number = ''
                dv_date=''
                check_or_ada_no=''
                ada_number=''
                particular=''
            }
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
                <td></td>
                <td colspan='' >` + check_issued + `</td>
                <td colspan='' >` + ada_issued + `</td>
                <td></td>
                  
                        </tr>`
            $('#cadadr tbody').append(row)

        }
        console.log(arr)


    }

    function addToSummaryTable(conso) {
        $('#summary_table tbody').html('')
        var total_beginning_balance = 0
        var total_prev = 0
        var total_current = 0
        var total_to_date = 0
        var total_utilization = 0
        var total_balance = 0
        for (var i = 0; i < conso.length; i++) {
            var beginning_balance = parseFloat(conso[i]['beginning_balance'])
            var prev = parseFloat(conso[i]['prev'])
            var current = parseFloat(conso[i]['current'])
            var to_date = parseFloat(conso[i]['to_date'])
            var utilization = to_date / beginning_balance
            var balance = beginning_balance - to_date
            var row = `<tr>
                <td>` + conso[i]['mfo_name'] + `</td>
                <td>` + conso[i]['document'] + `</td>
                <td class='amount'>` + thousands_separators(beginning_balance) + `</td>
                <td class='amount'>` + thousands_separators(prev) + `</td>
                <td class='amount'>` + thousands_separators(current) + `</td>
                <td class='amount'>` + thousands_separators(to_date) + `</td>
                <td class='amount'>` + thousands_separators(balance) + `</td>
                <td class='amount'>` + thousands_separators(utilization) + `</td>
            </tr>`
            $('#summary_table tbody').append(row)
            total_beginning_balance += beginning_balance
            total_prev += prev
            total_current += current
            total_to_date += to_date

            total_balance += balance
        }
        total_utilization = total_to_date / total_beginning_balance
        row = `<tr>
                <td style='font-weight:bold' colspan='2'>Total</td>
                <td class='amount'>` + thousands_separators(total_beginning_balance.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_prev.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_current.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_to_date.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_balance.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_utilization.toFixed(2)) + `</td>
            </tr>`
        $('#summary_table tbody').append(row)
    }
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
JS;
$this->registerJs($script);
?>