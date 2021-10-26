<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\Books;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = "CDJ";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">

    <form id='filter'>
        <label for="reporting_period">Reporting Period</label>
        <div class="row">

            <div class="col-sm-3">


                <?php
                echo DatePicker::widget([
                    'id' => 'reporting_period',
                    'name' => 'reporting_period',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView' => 'months',
                        'minViewMode' => 'months',
                        'format' => 'yyyy-mm'
                    ]
                ])

                ?>
            </div>
            <div class="col-sm-3">
                <?php
                $data = [
                    '101' => 'Fund 101',
                    'Fund 07' => 'Fund 07',
                    'RAPID LP' => 'RAPID LP',

                ];
                echo Select2::widget([
                    'data' => $data,
                    'name' => 'book',
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ]);
                ?>
            </div>
            <div class="col-sm-3">
                <button type="submit" class="btn btn-success">Generate</button>
            </div>
        </div>
    </form>
    <div class="con">

        <table id="cdj_table">
            <thead>
                <tr>
                    <th colspan="16" style="border: 0;">
                        <span> CASH DISBURSEMENT JOURNAL</span>
                        <br>
                        <span>For the month of </span>
                        <span id="r_period"></span>

                    </th>
                </tr>
                <tr>
                    <th  style="text-align: left;border:0;" colspan="16">
                        <span>Entity Name: DEPARTMENT OF TRADE AND INDUSTRY - CARAGA</span>
                    </th>
                </tr>
                <tr>
                    <th  style="text-align: left;border:0;padding-top:0;" colspan="16">
                    <span>Fund Cluster:</span>    
                    <span id="cluster"></span>    

                    </th>
                </tr>
                <tr>
                    <th rowspan="3">DATE</th>
                    <th rowspan="3"> JEV No.</th>
                    <th rowspan="3">RCDISB No.</th>
                    <th rowspan="3">Name of Disbursing Officer</th>
                    <th rowspan="1" colspan="7">Credit</th>
                    <th rowspan="1" colspan="5">Debit</th>

                </tr>
                <tr>
                    <th rowspan="1"> 1990101000</th>
                    <th rowspan="1"> 1990103000</th>
                    <th rowspan="1"> 2020101000</th>
                    <th rowspan="2"> Total</th>
                    <th rowspan="1" colspan="3"> Sundry</th>
                    <th rowspan="1"> </th>
                    <th rowspan="2"> Total</th>
                    <th rowspan="1" colspan="3"> Sundry</th>


                </tr>
                <tr>
                    <th rowspan="1"> Advances for Operating Expenses</th>
                    <th rowspan="1"> Advances to Special Disbursing Officer</th>
                    <th rowspan="1"> Due to BIR</th>
                    <th rowspan="1"> UACS Object Code</th>
                    <th rowspan="1"> p</th>
                    <th rowspan="1"> Amount</th>
                    <th rowspan="1"> </th>
                    <th rowspan="1"> UACS Object Code</th>
                    <th rowspan="1"> P</th>
                    <th rowspan="1"> Amount</th>
                </tr>

            </thead>
            <tbody></tbody>

        </table>
        <div style="margin-top: 30px;">
            <table id="conso_table">

                <thead>
                    <th>Account Code</th>
                    <th>Account Title</th>
                    <th> Debit</th>
                    <th>Credit</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>


</div>
<div id="dots5" style="display: none;">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<style>
    .amount {
        text-align: right;
        padding: 12px;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
        text-align: center;
        margin-top: 20px;
    }

    #conso_table {
        margin-left: auto;
        margin-right: auto;
    }

    .header {
        border: none;
        font-weight: bold;

    }

    .t_head {
        text-align: center;
        font-weight: bold;
    }

    @media print {

        td,
        th {
            font-size: 10px;
            padding: 2px;
        }

        .amount {
            padding: 5px;
        }

        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>

<script>
    var grand_total_opex = 0
    var grand_total_sdo = 0
    var grand_total_due = 0
    var period = ''
    var officer = {
        'adn': 'ROSIE R. VELLESCO',
        'ads': 'PRESCYLIN C. LADEMORA',
        'sdn': 'FERDINAND R. INRES',
        'pdi': 'VENUS A. CUSTODIO',
        'sds': 'FRITZIE N. USARES',
    }
    var months = {
        '0': 'January',
        '1': 'February',
        '2': 'March',
        '3': 'April',
        '4': 'May',
        '5': 'June',
        '6': 'July',
        '7': 'August',
        '8': 'September',
        '9': 'October',
        '10': 'November',
        '11': 'December',
    }
    $("#filter").submit((e) => {
        e.preventDefault()
        $('.con').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=report/cdj',
            data: $('#filter').serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                grand_total_opex = 0
                grand_total_sdo = 0
                grand_total_due = 0
                period = res.period
                displayData(res.result)
                displayConsoTable(res.conso)
                var date = new Date(period)
                $('#r_period').text(months[date.getMonth()] + ', ' + date.getFullYear())
                $('#cluster').text(res.book)
                setTimeout(() => {
                    $('#dots5').hide()
                    $('.con').show()
                }, 1000);

            }
        })
    })


    function displayData(data) {
        $('#cdj_table tbody').html('')
        var serial_number_keys = Object.keys(data)
        var grand_total = 0
        for (var serial_loop = 0; serial_loop < serial_number_keys.length; serial_loop++) {
            var serial_number = serial_number_keys[serial_loop]
            var total_withdrawal = 0
            var total_due_to_bir = 0
            var opex_id = '';
            var sdo_id = '';
            var due_id = '';
            var advance_type = ''
            var officer_name = ''
            var officer_id = ''
            var total_id = ''
            var period_id = ''
            for (var i = 0; i < data[serial_number].length; i++) {
                var ser_num = ''
                var withdrawal = parseFloat(data[serial_number][i]['withdrawals'])
                var vat_nonvat = parseFloat(data[serial_number][i]['vat_nonvat'])
                var expanded_tax = parseFloat(data[serial_number][i]['expanded_tax'])
                var opex = ''
                var sdo = ''
                var due = ''
                var total = ''
                var ofr_id = ''
                var r_period
                if (i == 0) {
                    ser_num = serial_number
                    opex_id = 'opex_' + data[serial_number][i]['id']
                    opex = 'opex_' + data[serial_number][i]['id']
                    sdo_id = 'sdo_' + data[serial_number][i]['id']
                    sdo = 'sdo_' + data[serial_number][i]['id']
                    due_id = 'due_' + data[serial_number][i]['id']
                    due = 'due_' + data[serial_number][i]['id']
                    total_id = 'total_' + data[serial_number][i]['id']
                    total = 'total_' + data[serial_number][i]['id']
                    total_id = 'total_' + data[serial_number][i]['id']
                    officer_id = 'officer_' + data[serial_number][i]['id']
                    ofr_id = 'officer_' + data[serial_number][i]['id']
                    period_id = 'period_' + data[serial_number][i]['id']
                    r_period = 'period_' + data[serial_number][i]['id']
                    // advance_type = data[serial_number][i]['advance_type']
                    officer_name = officer[data[serial_number][i]['province']]
                    if (data[serial_number][i]['advance_type'] == 'Advances to Special Disbursing Officer') {

                        advance_type = 'sdo'
                    } else {
                        advance_type = 'opex'
                    }
                }
                // console.log(officer[data[serial_number][i]['province']])

                row = `<tr>
                <td id='${period_id}'>` + period + `</td>
                <td></td>
                <td>` + ser_num + `</td>
                <td id='${ofr_id}' ></td>
                <td class='amount' id='${opex}'></td>
                <td class='amount' id='${sdo}'></td>
                <td class='amount' id='${due}'></td>
                <td class='amount' id='${total}'></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>` + data[serial_number][i]['uacs'] + ' - ' + data[serial_number][i]['general_ledger'] + `</td>
                <td></td>
                <td class='amount'>` + thousands_separators(withdrawal) + `</td>
            
            </tr>`
                $('#cdj_table').append(row)
                total_withdrawal += withdrawal
                total_due_to_bir += vat_nonvat + expanded_tax
            }
            var total = total_withdrawal + total_due_to_bir
            grand_total_due += total_due_to_bir
            grand_total += total
            $(`#${officer_id}`).text(officer_name)
            $(`#${period_id}`).text()
            $(`#${due_id}`).text(thousands_separators(total_due_to_bir.toFixed(2)))
            $(`#${total_id}`).text(thousands_separators(total.toFixed(2)))
            if (advance_type == 'sdo') {
                grand_total_sdo += total_withdrawal

                $(`#${sdo_id}`).text(thousands_separators(total_withdrawal.toFixed(2)))
            } else if (advance_type == 'opex') {
                grand_total_opex += total_withdrawal

                $(`#${opex_id}`).text(thousands_separators(total_withdrawal.toFixed(2)))
            }
        }
        row = `<tr>
         
        <td class='amount' colspan='5' style='font-weight:bold;text-align:center;'>Total</td>
   
         <td>` + thousands_separators(grand_total_opex.toFixed(2)) + `</td>
         <td>` + thousands_separators(grand_total_sdo.toFixed(2)) + `</td>
         <td>` + thousands_separators(grand_total_due.toFixed(2)) + `</td>
         <td>` + thousands_separators(grand_total.toFixed(2)) + `</td>
         <td></td>


     
        </tr>`
        $('#cdj_table tbody').append(row)
    }

    function displayConsoTable(data) {
        var total_debit = 0
        var total_credit = 0
        $('#conso_table tbody').html('')
        for (var i = 0; i < data.length; i++) {
            row = `<tr>
         
                <td>` + data[i]['uacs'] + `</td>
                <td>` + data[i]['general_ledger'] + `</td>
                <td class='amount'>` + thousands_separators(data[i]['debit']) + `</td>
                <td></td>
            
            </tr>`
            $('#conso_table').append(row)
            total_debit += parseFloat(data[i]['debit'])
        }
        // grand_total_opex
        // grand_total_sdo
        // grand_total_due
        total_credit = grand_total_due + grand_total_sdo + grand_total_opex
        row = `<tr>
                <td>1990101000</td>
                <td>Advances for Operating Expenses</td>
                <td></td>
                <td class='amount'>` + thousands_separators(grand_total_opex.toFixed(2)) + `</td>
            </tr>`
        $('#conso_table').append(row)
        row = `<tr>
                <td>1990103000</td>
                <td>Advances to Special Disbursing Officer</td>
                <td></td>
                <td class='amount'>` + thousands_separators(grand_total_sdo.toFixed(2)) + `</td>
            </tr>`
        $('#conso_table').append(row)
        row = `<tr>
                <td>2020101000</td>
                <td>Due to BIR</td>
                <td></td>
                <td class='amount'>` + thousands_separators(grand_total_due.toFixed(2)) + `</td>
            </tr>`
        $('#conso_table').append(row)
        row = `<tr>
                <td colspan='2' style='text-align:center;font-weight:bold'>Total</td>
                <td class='amount'>` + thousands_separators(total_debit.toFixed(2)) + `</td>
                <td class='amount'>` + thousands_separators(total_credit.toFixed(2)) + `</td>
            </tr>`
        $('#conso_table').append(row)
    }
</script>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/app.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>