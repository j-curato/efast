<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\Books;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

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
                    'name' => 'book_id',
                    'id' => 'book',
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div>


            <div class="col-sm-2" style="margin-top: 2.05rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>

    </form>

    <!-- <div id="con"> -->

    <div id='con'>

        <table id='mode_of_payment_count'>

            <tbody></tbody>
        </table>

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
                    <th class='head' colspan="2"><span id="period"></span></th>
                    <th class='head' colspan="2">Sheet Number:</th>
                    <th class='head' colspan="1"> 15/</th>
                </tr>
                <tr>
                    <th colspan="7">MARRY ANN L. PASCUAL</th>
                    <th colspan="3">Administrative Officer V</th>
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
                    <th rowspan="3" colspan="5">Amount</th>

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



        <table id="cancelled_checks_table">
            <thead>
                <thead>

                    <tr>
                        <th class='head' colspan="14" style="background-color: #ffff80;">CANCELLED CHECKS</th>
                    </tr>

                    <tr>
                        <th rowspan="" colspan="3 ">NCA/DS/DV/Payroll</th>
                        <th rowspan="" colspan="4 "> Check/ADA</th>
                        <th rowspan="4">Payee</th>
                        <th rowspan="4">UACS</th>
                        <th rowspan="4">Nature of Payment</th>
                        <th rowspan="3" colspan="4">Amount</th>

                    </tr>
                    <tr>
                        <th rowspan="3"> No. </th>
                        <th rowspan="3"> Date </th>
                        <th rowspan="3">Reporting Period Cancelled</th>


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
                    </tr>
                </thead>
            <tbody></tbody>
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

    table {
        margin-top: 20px
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

    #cancelled_checks_table>th {
        padding: 0;
        font-size: 10px;
    }

    .amt {
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
            url: window.location.href,
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                console.log(res)
                // display counts per mode of payments
                let t = 0;
                $('#mode_of_payment_count tbody').html('')
                $.each(res.per_mode_of_payment, function(key, val) {
                    if (key) {
                        $('#mode_of_payment_count tbody').append(`<tr>
                        <th>${key}</th>
                        <th>${val.length}</th>
                    </tr>`)
                        t += val.length
                    }
                })
                console.log(t)

                const frm = new Date($('#from_reporting_period').val())
                const to = new Date($('#to_reporting_period').val())
                const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                const frm_prd = month[frm.getMonth()] + ', ' + frm.getFullYear()
                const to_prd = month[to.getMonth()] + ', ' + to.getFullYear()
                let f_prd = frm_prd == to_prd ? to_prd : frm_prd + ' to ' + to_prd;

                $('#period').text('As of ' + f_prd)
                displayData(res)
                setTimeout(() => {
                    $('#con').show()
                    $('#dots5').hide()
                }, 2000);

            }

        })
    })

    // function displayCancelledChecks(res) {
    //     $('#cancelled_checks_table tbody').html('')
    //     for (var i = 0; i < res.length; i++) {
    //         var data = res[i]
    //         var dv_number = data['dv_number']
    //         var dv_date = data['dv_date']
    //         var payee = data['account_name']
    //         var ada_issued = parseFloat(data['ada_issued'])
    //         var ada_number = data['ada_number']
    //         var book_name = data['book_name']
    //         var check_issued = parseFloat(data['check_issued'])
    //         var check_or_ada_no = data['check_or_ada_no']
    //         var issuance_date = data['issuance_date']
    //         var particular = data['particular']
    //         var reporting_period = data['reporting_period']
    //         var nca_recieve = parseFloat(data['nca_recieve'])

    //         row = `<tr class='data_row'>
    //             <td colspan='' >` + dv_number + `</td>
    //             <td colspan='' >` + dv_date + `</td>
    //             <td colspan='' >` + reporting_period + `</td>
    //             <td colspan='' >` + check_or_ada_no + `</td>
    //             <td colspan='' >` + ada_number + `</td>
    //             <td colspan='' >` + issuance_date + `</td>
    //             <td></td>
    //             <td colspan='' >` + payee + `</td>
    //             <td></td>
    //             <td colspan='' >` + particular + `</td>
    //             <td  class='amount'>` + thousands_separators(nca_recieve) + `</td>
    //             <td  class='amount'>` + thousands_separators(check_issued) + `</td>
    //             <td  class='amount'>` + thousands_separators(ada_issued) + `</td>
    //             </tr>`
    //         $('#cancelled_checks_table tbody').append(row)


    //     }

    // }

    // function displayData(res, begin_balance, adjustment) {
    //     $("#cadadr tbody").html('');
    //     var arr = []
    //     var balance = parseFloat(begin_balance)
    //     let check_count = 0
    //     let ada_count = 0
    //     row = `<tr class='data_row'>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td></td>
    //             <td colspan='' ></td>
    //             <td></td>
    //             <td colspan='' >` + 'Begining Balance' + `</td>
    //             <td  class='amount'></td>
    //             <td  class='amount'></td>
    //             <td class='amount' ></td>
    //             <td  class='amount'>` + thousands_separators(balance.toFixed(2)) + `</td>
    //             </tr>`
    //     var total_nca_recieve = 0
    //     var total_check_issued = 0
    //     var total_ada_issued = 0
    //     $('#cadadr tbody').append(row)
    //     for (var i = 0; i < res.length; i++) {
    //         var data = res[i]
    //         var dv_number = data['dv_number']
    //         var dv_date = data['dv_date']
    //         var payee = data['account_name']
    //         var ada_issued = parseFloat(data['ada_issued'])
    //         var ada_number = data['ada_number']
    //         var book_name = data['book_name']
    //         var check_issued = parseFloat(data['check_issued'])
    //         var check_or_ada_no = data['check_or_ada_no']
    //         var issuance_date = data['issuance_date']
    //         var particular = data['particular']
    //         var reporting_period = data['reporting_period']
    //         var nca_recieve = parseFloat(data['nca_recieve'])
    //         const mode_of_payment = data['mode_of_payment']
    //         if (mode_of_payment.toLowerCase() == 'lbp check') {
    //             check_count++
    //         } else if (mode_of_payment.toLowerCase() != '') {
    //             ada_count++
    //         }
    //         balance += nca_recieve - (ada_issued + check_issued)
    //         // if (jQuery.inArray(dv_number, arr) == -1) {
    //         //     arr.push(dv_number)
    //         // } else {
    //         //     dv_number = ''
    //         //     dv_date = ''
    //         //     check_or_ada_no = ''
    //         //     ada_number = ''
    //         //     particular = ''
    //         // }
    //         // console.log(jQuery.inArray(dv_number, arr))
    //         row = `<tr class='data_row'>
    //             <td colspan='' >` + dv_number + `</td>
    //             <td colspan='' >` + dv_date + `</td>
    //             <td colspan='' >` + check_or_ada_no + `</td>
    //             <td colspan='' >` + ada_number + `</td>
    //             <td colspan='' >` + issuance_date + `</td>
    //             <td></td>
    //             <td colspan='' >` + payee + `</td>
    //             <td></td>
    //             <td colspan='' >` + particular + `</td>
    //             <td  class='amount'>` + thousands_separators(nca_recieve) + `</td>
    //             <td  class='amount'>` + thousands_separators(check_issued) + `</td>
    //             <td  class='amount'>` + thousands_separators(ada_issued) + `</td>
    //             <td class='amount' >` + thousands_separators(balance.toFixed(2)) + `</td>

    //             </tr>`
    //         $('#cadadr tbody').append(row)
    //         total_nca_recieve += nca_recieve
    //         total_check_issued += check_issued
    //         total_ada_issued += ada_issued

    //     }


    //     $('#check').text(check_count)
    //     $('#ada').text(ada_count)
    //     row = `<tr class='data_row'>
    //             <td  ></td>
    //             <td  ></td>
    //             <td  ></td>
    //             <td  ></td>
    //             <td  ></td>
    //             <td></td>
    //             <td  ></td>
    //             <td></td>
    //             <td style='font-weight:bold' >` + 'Total' + `</td>
    //             <td  class='amount'>` + thousands_separators(total_nca_recieve.toFixed(2)) + `</td>
    //             <td  class='amount'>` + thousands_separators(total_check_issued) + `</td>
    //             <td class='amount' >` + thousands_separators(total_ada_issued) + `</td>
    //             <td  class='amount'>` + thousands_separators(balance.toFixed(2)) + `</td>

    //             </tr>`
    //     $('#cadadr tbody').append(row)

    //     if (adjustment.length >= 1) {
    //         row = `<tr class='data_row'>

    //             <td colspan='13' style='font-weight:bold;background-color:#cccccc'>` + 'Adjustment' + `</td>


    //             </tr>`
    //         $('#cadadr tbody').append(row)

    //     }
    //     for (var adjustment_loop = 0; adjustment_loop < adjustment.length; adjustment_loop++) {

    //         var adjust_amount = parseFloat(adjustment[adjustment_loop]['amount'])
    //         var adjust_particular = adjustment[adjustment_loop]['particular']

    //         var b_balance = balance
    //         balance = parseFloat(balance.toFixed(2)) - parseFloat(adjust_amount)
    //         console.log(balance, adjust_amount)
    //         row = `<tr class='data_row'>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td colspan='' ></td>
    //             <td></td>
    //             <td colspan='' ></td>
    //             <td></td>
    //             <td colspan='' >` + adjust_particular + `</td>
    //             <td  class='amount'>` + thousands_separators(b_balance.toFixed(2)) + `</td>
    //             <td  class='amount'></td>
    //             <td class='amount' >` + thousands_separators(adjust_amount) + `</td>
    //             <td  class='amount'>` + thousands_separators(balance.toFixed(2)) + `</td>

    //             </tr>`
    //         $('#cadadr tbody').append(row)
    //     }



    // }

    function displayData(data) {
        console.log(data.begin_balance)
        $("#cadadr tbody").html('');
        $("#cancelled_checks_table tbody").html('');

        $('#cadadr tbody').append(`<tr class='data_row'>
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
                <td  class='amount'>` + thousands_separators(data.begin_balance) + `</td>
                </tr>`)
        let ttlAda = 0;
        let ttlCheck = 0
        let ttlNca = 0
        let nca_bal = parseFloat(data.begin_balance)
        $.each(data.results, function(key, val) {
            let ada = val.is_check == 0 ? parseFloat(val.amtDisbursed) : 0;
            let check = val.is_check == 1 ? parseFloat(val.amtDisbursed) : 0;
            let nca = parseFloat(val.nca_receive);

            nca_bal += nca;
            nca_bal -= check;
            nca_bal -= ada;


            ttlAda += parseFloat(ada);
            ttlCheck += parseFloat(check);
            ttlNca += parseFloat(nca);

            let row = `<tr>
                <td>${val.dv_number}</td>
                <td></td>
                <td>${val.check_or_ada_no}</td>
                <td>${val.ada_number}</td>
                <td>${val.check_date}</td>
                <td></td>
                <td>${val.payee}</td>
                <td>${val.uacs}</td>
                <td>${val.particular}</td>
                <td class='amt'>${thousands_separators(nca)}</td>
                <td class='amt'>${thousands_separators(check)}</td>
                <td class='amt'>${thousands_separators(ada)}</td>
                <td class='amt'>${thousands_separators(nca_bal)}</td>
            </tr>`
            $('#cadadr tbody').append(row)
        })
        let ttlRow = `<tr>
                <td colspan='9'>TOTAL</td>
                <td>${thousands_separators(ttlNca)}</td>
                <td>${thousands_separators(ttlCheck)}</td>
                <td>${thousands_separators(ttlAda)}</td>
                <td>${thousands_separators(nca_bal)}</td>

            </tr>`
        $('#cadadr tbody').append(ttlRow)
        // DIsplay Cancelled Cheks
        $.each(data.cancelled_checks, function(key, val) {
            let ada = val.is_check == 0 ? parseFloat(val.amtDisbursed) : 0;
            let check = val.is_check == 1 ? parseFloat(val.amtDisbursed) : 0;
            let nca = parseFloat(val.nca_receive);

            let cnldRow = `<tr class='data_row'>
                <td colspan='' >${val.dv_number}</td>
                <td colspan='' >${val.check_date}</td>
                <td colspan='' >${val.reporting_period}</td>
                <td colspan='' >${val.check_or_ada_no}</td>
                <td colspan='' >${val.ada_number}</td>
                <td colspan='' >${val.check_date}</td>
                <td></td>
                <td colspan='' >${val.payee}</td>
                <td></td>
                <td colspan='' >${val.particular}</td>
                <td  class='amount'>${ thousands_separators(nca) }</td>
                <td  class='amount'>${ thousands_separators(check) }</td>
                <td  class='amount'>${ thousands_separators(ada) }</td>
     
                </tr>`
            $('#cancelled_checks_table tbody').append(cnldRow)
        })
        // Display Adjustments
        if (data.adjustments.length >= 1) {
            $('#cadadr tbody').append(`<tr class='data_row'><td colspan='13' style='font-weight:bold;background-color:#cccccc'>Adjustments </td></tr>`)
            $.each(data.adjustments, function(key, val) {



                let b_balance = ttlNca
                let balance = parseFloat(b_balance.toFixed(2)) - parseFloat(val.amount)
                row = `<tr class='data_row'>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td colspan='' ></td>
                <td></td>
                <td colspan='' ></td>
                <td></td>
                <td colspan='' >` + val.particular + `</td>
                <td  class='amount'>` + thousands_separators(b_balance.toFixed(2)) + `</td>
                <td  class='amount'></td>
                <td class='amount' >` + thousands_separators(val.amount) + `</td>
                <td  class='amount'>` + thousands_separators(balance.toFixed(2)) + `</td>
                  
                </tr>`
                $('#cadadr tbody').append(row)
            })
        }

    }
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
JS;
$this->registerJs($script);
?>