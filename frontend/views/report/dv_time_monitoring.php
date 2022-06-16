<?php

use app\models\AdvancesEntries;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "DV Time Monitoring";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;padding:2rem;">
    <div class="row">
        <form id="filter">
            <div class="col-sm-3">
                <label for="from_reporting_period">From Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'from_reporting_period',
                    'name' => 'from_reporting_period',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months'
                    ]
                ]);

                ?>
            </div>
            <div class="col-sm-3">
                <label for="to_reporting_period">To Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'to_reporting_period',
                    'name' => 'to_reporting_period',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months'
                    ]
                ]);

                ?>
            </div>
            <div class="col-sm-3">
                <button class="btn btn-success" type="submit">Generate</button>
            </div>
        </form>
    </div>
    <table id="data_table">
        <thead>
            <tr>
                <th colspan="13" style="border:0;padding-bottom:2rem">
                    <span>
                        DEPARTMENT OF TRADE AND INDUSTRY
                    </span>
                    <br>
                    <span>Regional Office No.13</span>
                    <br>
                    <span>Butuan City</span>
                    <br>
                    <span>
                        FINANCE and ADMINISTRATIVE DIVISION
                    </span>
                </th>
            </tr>
            <tr>
                <th colspan="13" style="border:0;">
                    <span>
                        Process Cycle Time Monitoring Report for Processing of Claims

                    </span>
                    <br>
                    <span class='for_the_month'>
                    </span>
                </th>

            </tr>
            <tr>
                <th colspan="13" style="border: 0;text-align:left;font-weight:bold">
                    I. Turn-around Time Tabulation
                </th>
            </tr>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Payee</th>
                <th rowspan="2">DV No.</th>
                <th rowspan="2">DV Amount</th>
                <th colspan="3">Elapsed Time in Accounting Unit</th>
                <th colspan="3">Elapsed Time in Accounting Unit</th>
                <th rowspan="2">Total Turn-around Time(in working day/s)<br><span style="font-size: 11px;font-style:italic;"> (If sum equals 0 value shall be 1)</span></th>
                <th rowspan="2">Within / Beyond the Timeline of 3 Working Days) </th>
                <th rowspan="2">Remarks</th>
            </tr>
            <tr>
                <th>Date In <br> <span style="font-size: 11px;font-style:italic;">(upon receipt of Complete Supporting Documents)</span></th>
                <th>Date Out <br> <span style="font-size: 11px;font-style:italic;">(Upon Accountant's Signature in Box C of DV)</span></th>
                <th>Elapsed Day/s*</th>
                <th>Date In <br> <span style="font-size: 11px;font-style:italic;">(Upon Receipt of Approved DV by the Cashier)</span></th>
                <th>Date Out <br> <span style="font-size: 11px;font-style:italic;">(Upon Issuance of Check/LDDAP)</span></th>
                <th>Elapsed Day/s*</th>
            </tr>


        </thead>
        <tbody>


        </tbody>
    </table>


    <!-- <input type="text" id="d1" value="2022-05-23"><br>
    <input type="text" id="d2" value="2022-05-31">

    <p>Working days count: <span id="dif"></span></p>
    <button id="calc">Calc</button>

    <p>
        Now it shows 5 days, but I need for example add holidays
        3 and 5 May (2016-05-03 and 2016-05-05) so the result will be 3 working days
    </p> -->

</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 5px;
        text-align: center;
    }

    table {
        margin-top: 3rem;
    }

    .btn {
        margin-top: 25px;
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

        th,
        td {
            border: 1px solid black;
            padding: 3px;
            text-align: center;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        .select2-container--default .select2-selection--single {
            border: none !important;
            text-decoration: underline;
        }
    }
</style>
<?php

$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/select2.min.css", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<script>
    let holidays = [];
    let asignatory = []

    function signatory() {
        $('.asignatory').select2({
            data: asignatory,
            placeholder: "Select ",

        })
    }
    $(document).ready(() => {
        $.getJSON('<?php echo Url::base() ?>/frontend/web/index.php?r=assignatory/get-all-assignatory')

            .then(function(data) {

                let array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.name,
                        text: val.name,
                        emp: val.name,

                    })
                    asignatory.push({
                        position: val.position,
                        name: val.name,
                        id: val.position,
                        text: val.name,

                    })
                })


            })
        $('#data_table').on('change', '.asignatory', function() {
            $(this).closest('td').find('.position').text($(this).val())
        })
        $('#calc').click(() => {
            var d1 = $('#d1').val();
            var d2 = $('#d2').val();
            $('#dif').text(workingDaysBetweenDates(d1, d2));
        });
        $("#filter").submit(function(e) {
            e.preventDefault()
            $.ajax({
                type: "POST",
                url: window.location.href,
                data: $('#filter').serialize(),
                success: function(data) {
                    const res = JSON.parse(data)
                    holidays = res.holidays
                    const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                    if (
                        $('#from_reporting_period').val() == $('#to_reporting_period').val()
                    ) {
                        const d = new Date($('#to_reporting_period').val())
                        const selected_month = month[d.getMonth()]
                        const year = d.getFullYear()

                        $('.for_the_month').text('For the Month of ' + selected_month + ', ' + year)
                    } else {
                        const d = new Date($('#to_reporting_period').val())
                        const selected_month = month[d.getMonth()]
                        const year = d.getFullYear()

                        $('.for_the_month').text('As of ' + selected_month + ', ' + year)
                    }
                    displayData(res.data)

                }

            })
        })
    });

    function displayData(data) {
        $('#data_table tbody').html('')
        let total_within = 0
        let beyond_timeline = 0
        let dv_count = 0
        let grand_total = 0
        $.each(data, function(key, val) {
            const payee = val.payee
            const dv_number = val.dv_number
            const dv_amount = parseFloat(val.dv_amount)
            const dv_in = val.dv_in
            const dv_out = val.dv_out
            const cash_in = val.cash_in
            const cash_out = val.cash_out
            const issuance_date = val.issuance_date

            const cash_elapse = 0
            const check_number = val.check_or_ada_no
            grand_total += dv_amount
            let turn_around = 0
            let dv_elapse = ''
            let within_or_beyond = ''
            if (dv_in != '' && dv_out != '') {



                dv_elapse = workingDaysBetweenDates(dv_in, dv_out)
                if (dv_elapse != 'undefine') {
                    turn_around = dv_elapse + cash_elapse
                    if (turn_around == 0) {
                        turn_around = 1
                    }
                    if (turn_around <= 3) {
                        within_or_beyond = 'Within Timeline'
                        total_within++

                    } else {
                        within_or_beyond = 'Beyond Timeline'
                        beyond_timeline++
                    }
                } else {
                    dv_elapse = 'Out Date is Less Than In Date'
                }


            }
            dv_count++
            let row_color = '';
            if (within_or_beyond == '') {
                row_color = 'background-color:#ffb3b3;'
            }
            const datarow = `<tr style='${row_color}'>
            <td>${key+1}</td>
            <td>${payee}</td>
            <td >${dv_number}</td>
            <td class='amount'>${thousands_separators(dv_amount)}</td>
            <td>${dv_in}</td>
            <td>${dv_out}</td>
            <td>${dv_elapse}</td>
            <td>${issuance_date}</td>
            <td>${issuance_date}</td>
            <td>${cash_elapse}</td>
            <td>${turn_around}</td>
            <td>${within_or_beyond}</td>
            <td></td>
            </tr>`

            $('#data_table tbody').append(datarow)
        })

        const foot_row = `
        <tr><th colspan='3'>Grand Total</th><th class='amount'> ${thousands_separators(grand_total.toFixed(2))}</th>
        <td colspan='9'></td>
        </tr>
        <tr><td colspan='13'>Note: *Elapsed Day/s only include/s regular working days. Thus, excluded in the computation of elapsed Day/s are Non-working Holidays, Saturdays, and Sundays</td></tr>
   
        `
        $('#data_table tbody').append(foot_row)
        const accomplished = (parseInt(total_within) / parseInt(dv_count)) * 100
        const evaluation_row = `
        <tr>
                <td style="font-weight:bold;border: 0;padding-top:5rem;text-align:left;" colspan='3'>II. Turn-around Time Analysis and Evaluation</td>
            </tr>
            <tr>

                <td></td>
                <td>Number</td>
                <td>Accomplishment</td>

            </tr>
            <tr>
                <td>Total Claims Processed within Timeline of 3 working days</td>
                <td>${total_within}</td>
                <td rowspan="2">${accomplished.toFixed(2)}%</td>
            </tr>
            <tr>
                <td>Total Claims Received/Processed</td>
                <td>${dv_count}</td>
            </tr>
            <tr>
            <td colspan='13' style='border:none;'>To summarize, ${accomplished.toFixed(2)}% or ${total_within} out of ${dv_count} total claims for the month of February,
             2022 were processed within 3 Working Days. ${beyond_timeline} claim was processed beyond the set timeline of 3 Working days.</td>
            </tr>

            <tr>
                <td style='border:none;' colspan='3'>
                    <span style='float:left;'>Prepared By</span>
                    <br>
                    <select class="asignatory "  style="width: 100%;">
                    <option value=""></option>
                    </select>
                    <br>
                    <span class='position'></span>
                </td>
                <td style='border:none;' colspan='4'>
                    <span style='float:left;'>Reviewed By</span>
                    <br>
                    <select class="asignatory "  style="width: 100%;">
                    <option value=""></option>
                    </select>
                    <br>
                    <span class='position'></span>
                </td>
                <td style='border:none;' colspan='4'>
                    <span style='float:left;'>Approved By</span>
                    <br>
                    <select class="asignatory " style="width: 100%;">
                    <option value=""></option>
                    </select>
                    <br>
                    <span class='position'></span>
                </td>
            </tr>
            `
        $('#data_table tbody').append(evaluation_row)
        signatory()

    }

    let workingDaysBetweenDates = (d0, d1) => {
        /* Two working days and an sunday (not working day) */
        $.ajax
        var startDate = parseDate(d0);
        var endDate = parseDate(d1);

        // Validate input
        if (endDate < startDate) {
            return 'undefine';
        }

        // Calculate days between dates
        var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
        startDate.setHours(0, 0, 0, 1); // Start just after midnight
        endDate.setHours(23, 59, 59, 999); // End just before midnight
        var diff = endDate - startDate; // Milliseconds between datetime objects    
        var days = Math.ceil(diff / millisecondsPerDay);

        // Subtract two weekend days for every week in between
        var weeks = Math.floor(days / 7);
        days -= weeks * 2;

        // Handle special cases
        var startDay = startDate.getDay();
        var endDay = endDate.getDay();

        // Remove weekend not previously removed.   
        if (startDay - endDay > 1) {
            days -= 2;
        }
        // Remove start day if span starts on Sunday but ends before Saturday
        if (startDay == 0 && endDay != 6) {
            days--;
        }
        // Remove end day if span ends on Saturday but starts after Sunday
        if (endDay == 6 && startDay != 0) {
            days--;
        }
        /* Here is the code */
        holidays.forEach(day => {


            if ((day >= d0) && (day <= d1)) {
                if ((parseDate(day).getDay() % 6) != 0) {
                    days--;
                }
            }
        });
        return days - 1;

    }

    function parseDate(input) {
        // Transform date from text to date
        var parts = input.match(/(\d+)/g);
        // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
        return new Date(parts[0], parts[1] - 1, parts[2]); // months are 0-based
    }
</script>