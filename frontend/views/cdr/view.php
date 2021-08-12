<?php

use app\models\Books;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "CDR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">
    <div class="row">

        <?php

        $color = $model->is_final === 1 ? 'btn-danger' : 'btn-success';
        if (Yii::$app->user->can('create_cdr')) {

            echo Html::a($model->is_final === 1 ? 'Draft' : 'Final', null, ['class' => "btn $color", 'type' => 'button', 'id' => 'final']);
            if ($model->is_final ===1){
                echo "<button id='cdr_jev' class='btn btn-warning'>Jev</button>";
            }
        
        }


        ?>
    </div>
    <form id='filter' >


        <?php
        echo "<input type='hidden' id='reporting_period' name='reporting_period' value='$model->reporting_period'>";
        echo "<input type='hidden' id='province' name='province' value='$model->province'>";
        echo "<input type='hidden' id='book' name='book' value='$model->book_name'>";
        echo "<input type='hidden' id='report_type' name='report_type' value='$model->report_type'>";
        echo "<input type='hidden' id='cdr_id' name='cdr_id' value='$model->id'>";
        ?>

    </form>

    <?php
    $prov = [];
    $municipality = '';
    $officer = '';
    $location = '';

    if (!empty($province)) {
        $prov = Yii::$app->memem->cibrCdrHeader($province);
        $municipality = $prov['province'];
        $officer = $prov['officer'];
        $location = $prov['location'];
    }



    ?>
    <table id='data_table'>
        <thead>
            <tr>
                <td colspan="12" class="header" style="text-align: center;border:1px solid white">CASH DISBURSEMENT REGISTER</td>
            </tr>
            <tr>
                <td colspan="12" class="header" style="text-align: center;border:1px solid white">

                    <span class="reporting_period">
                        For the month of
                    </span>
                </td>
            </tr>
            <tr style="border:1px solid white">
                <td colspan="8" class="header">
                    <span> Entity Name:Department of Trade and Industry</span>
                </td>
                <td colspan="4" class="header ">
                    <span>
                        Name of Accountable Officer:
                    </span>
                    <span class="officer">

                    </span>
                </td>
            </tr>
            <tr style="border:1px solid white">
                <td colspan="8" class="header">
                    <span> Sub-Office/District/Division: Provincial Office</span>
                </td>
                <td colspan="4" class="header">
                    <span class="">
                        Official Designation: 
                    </span>
           
                    <span id="advance_type">

                    </span>
                </td>
            </tr>
            <tr style="border:1px solid white">
                <td colspan="8" class="header ">
                    <span> Municipality/City/Province: </span>
                    <span class="municipality"></span>
                </td>
                <td colspan="4" class="header">
                    <span>
                        Station:DTI -
                    </span>
                    <span class="municipality">
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="8" rowspan="2" style="border-left:1px solid white;border-right:1px solid white;" class="header">
                    <span> Fund Cluster : </span>
                    <span class="book"></span>
                </td>
                <td colspan="4" class="header" style="border-left:1px solid white;border-right:1px solid white;">
                    <span>
                        Register No : 
                    </span>
                    <span id="register_name">
                        <?php
                        if (!empty($model->serial_number)){
                            echo $model->serial_number;
                        }
                        ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border-left:1px solid white;border-right:1px solid white;" class="header">
                    <span>
                        Sheet No.: __________________________
                    </span>
                </td>

            </tr>
            <tr>

                <td rowspan="7" class="t_head">Date</td>
                <td rowspan="7" class="t_head">Check No.</td>
                <td rowspan="7" class="t_head">Particular</td>
                <td rowspan="3" class="t_head" colspan="3">Advances to Special Disbursing Officer (1990103000)</td>
            </tr>

            <tr>
                <td colspan="6" class="t_head">BREAKDOWN </td>
            </tr>
            <tr>
                <td rowspan="4" class="t_head">Salaries and Wages - Regular</td>
                <td rowspan="4" class="t_head">Salaries and Wages -Casual/ Contractual</td>
                <td rowspan="4" class="t_head"> Office Supplies Expenses </td>
                <td colspan="3" class="t_head" rowspan="3">OTHERS</td>
            </tr>

            <tr>
                <td rowspan="" colspan="3" class="t_head">Amount</td>
            </tr>
            <tr>
                <td rowspan="3" class="t_head">Deposits</td>
                <td rowspan="3" class="t_head">Withdrawals/ Payments</td>
                <td rowspan="3" class="t_head"> Balances</td>
            </tr>
            <tr>

                <td rowspan="3" class="t_head"> Account Description</td>
                <td rowspan="3" class="t_head">UACS Code</td>
                <td rowspan="3" class="t_head">Amount</td>
            </tr>
            <tr>
                <td rowspan="1" class="t_head">(50101010)</td>
                <td rowspan="1" class="t_head">(50101020)</td>
                <td rowspan="1" class="t_head"> (50203010)</td>
            </tr>
        </thead>

        <tbody>

           


        </tbody>
        <tfoot style="display: table-row-group">

            <tr>
                <td colspan="6">

                </td>

                <td colspan="6">
                    <span>
                        The total of the ‘Advances for Operating Expenses – Payments’ column must always be equal to the sum of the totals of the ‘Breakdown of Payments’ columns.

                    </span>
                </td>

            </tr>
            <tr>
                <td colspan="2" style="border-right: none;"></td>
                <td colspan="4" style="text-align: center;border-left:none">
                    <div style="margin-left:-25rem;margin-top:1rem;margin-bottom:2rem"><span>CERTIFIED CORRECT:</span></div>
                    <div><span style="font-weight: bold;" class="officer"><?= $officer ?></span></div>
                    <div><span>Signature Over Printed Name</span></div>
                    <div style="margin-left:-15rem;"><span>Date</span></div>
                </td>
                <td colspan="6" style="text-align: center;">
                    <div style="margin-left:-25rem;margin-top:1rem;margin-bottom:2rem"><span>RECEIVED BY:</span></div>
                    <div><span style="font-weight: bold;">MARION T. MONROID</span></div>
                    <div><span>Signature Over Printed Name</span></div>
                    <div style="margin-left:-15rem;"><span>Date</span></div>
                </td>

            </tr>
        </tfoot>

    </table>

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

        .btn {
            display: none;
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
    const data_table = $('#data_table tbody')


    function addToDataTable(data, balance) {
        var deposit = data['amount'] != '' ? thousands_separators(data['amount']) : ''
        var withdrawal = data['withdrawals'] != '' ? thousands_separators(data['withdrawals']) : ''
        var bal = thousands_separators(balance)
        var x = ''
        if (data['particular'] == 'Total') {
            x = 'font-weight:bold;text-align:center'
        }
        var row = `<tr>                 
                            <td>${data['reporting_period']}</td>
                            <td>${data['check_number']}</td>
                            <td style='${x}'>${data['particular']}</td>
                            <td class='amount'>${deposit}</td>
                            <td class='amount'>${withdrawal}</td>
                            <td class='amount'>${bal}</td>
                            <td ></td>
                            <td > </td>
                            <td > </td>
                            <td>${data['gl_account_title']}</td>
                            <td>${data['gl_object_code']}</td>
                            <td class='amount'>${withdrawal}</td>
                        </tr>`

        data_table.append(row)
    }

    var x = 0;
    let balance = 0;

    function addCdrRow(data) {

        var total_deposits = 0;
        var total_withdrawals = 0;
        $("#data_table > tbody").html("");
        for (var i = 0; i < data.length; i++) {


            if (data[i]['amount'] == '') {
                data[i]['amount'] = 0
            }
            if (data[i]['withdrawals'] == '') {
                data[i]['withdrawals'] = 0
            }
            // balance += data[i]['amount'] - data[i]['withdrawals'];

            // console.log(balance)


            // if (data[i]['reporting_period'] == $("#reporting_period").val()) {


            if (x != 1) {
                var am = data[i]['amount']
                var wth = data[i]['withdrawals']
                var beginning_balance = parseFloat(balance, 2) - parseFloat(data[i]['amount'], 2) + parseFloat(data[i]['withdrawals'], 2)
                var d = []
                total_deposits += balance
                d['reporting_period'] = '';
                d['check_number'] = '';
                d['particular'] = 'Beginning Balance';
                d['amount'] = balance;
                d['withdrawals'] = 0;
                d['gl_account_title'] = '';
                d['gl_object_code'] = '';
                addToDataTable(d, balance)
                x++

            }
            balance = parseFloat(balance) + parseFloat(data[i]['amount'], 2) - parseFloat(data[i]['withdrawals'], 2)
            var bal = parseFloat(balance).toFixed(2);
            addToDataTable(data[i], bal)

            total_deposits = parseFloat(total_deposits, 2) + parseFloat(data[i]['amount'], 2)
            total_withdrawals = parseFloat(total_withdrawals, 2) + parseFloat(data[i]['withdrawals'], 2)

            // }


        }
        var q = []
        q['reporting_period'] = '';
        q['check_number'] = '';
        q['particular'] = 'Total';
        q['amount'] = parseFloat(total_deposits, 2).toFixed(2);
        q['withdrawals'] = parseFloat(total_withdrawals, 2).toFixed(2);
        q['gl_account_title'] = '';
        q['gl_object_code'] = '';
        addToDataTable(q, bal)

    }

    function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

    function addConsoRow(data) {
        var total_vat = 0
        var total_expanded = 0
        var total_gross_amount = 0
        var total_gross_expense = 0
        for (var i = 0; i < data.length; i++) {
            var vat = data[i]['vat_nonvat']
            var expanded = data[i]['expanded_tax']
            var gross_expense = data[i]['gross_expense']
            var gross_amount = data[i]['gross_amount']
            var row = `<tr>
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td class='amount'></td>
                        <td class='amount'></td>
                        <td></td>

                        <td class='amount'>${thousands_separators(parseFloat(vat).toFixed(2))}</td>
                        <td class='amount'>${thousands_separators(parseFloat(expanded).toFixed(2))}</td>
                        <td class='amount'>${thousands_separators(parseFloat(gross_expense).toFixed(2))}</td>
                        <td>${data[i]['account_title']}</td>
                        <td>${data[i]['object_code']}</td>
                        <td class='amount' >${gross_amount}</td>
            </tr>`
            total_vat += vat
            total_expanded += expanded
            total_gross_amount += gross_amount
            total_gross_expense += gross_expense

            data_table.append(row)
        }
        data_table.append(
            `<tr>
                        <td></td> 
                        <td ></td>
                        <td></td>
                        <td class='amount'></td>
                        <td class='amount'></td>
                        <td></td>

                        <td class='amount'>${thousands_separators( parseFloat(total_vat).toFixed(2))}</td>
                        <td class='amount'>${thousands_separators( parseFloat(total_expanded).toFixed(2))}</td>
                        <td class='amount'>${thousands_separators( parseFloat(total_gross_expense).toFixed(2))}</td>
                        <td style='font-weight:bold'>Total</td>
                        <td></td>
                        <td class='amount' >${thousands_separators(parseFloat(total_gross_amount).toFixed(2))}</td>
            </tr>`
        )

    }

    function cdrAjaxRequest() {
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=cdr/cdr',
            data: $('#filter').serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                var cdr = res.cdr
                var conso = res.consolidate
                var book = res.book
                var reporting_period = res.reporting_period
                var location = res.location
                var municipality = res.municipality
                var officer = res.officer
                console.log(res)
                balance = res.balance
                // console.log(res)
                $('.book').text(book)
                $('.reporting_period').text(reporting_period)
                $('.location').text(location)
                $('.officer').text(officer)
                $('.municipality').text(municipality)
                $('#advance_type').text(res.advance_type)
                addCdrRow(cdr)
                data_table.append(`
                <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: bold;text-align:center;"> Due to BIR - VAT/NonVAT</td>
                <td style="font-weight: bold;text-align:center;"> Due To Bir Expanded</td>
                <td style="font-weight: bold;text-align:center;">Gross Expense</td>
                <td style="font-weight: bold;text-align:center;">Account Description</td>
                <td style="font-weight: bold;text-align:center;">UACS Object Code</td>
                <td style="font-weight: bold;text-align:center;">Amount</td>
            </tr>
                `)
                addConsoRow(conso)

            }
        })
    }
    $('#generate').click(function(e) {
        balance = 0
        x = 0;

        e.preventDefault()
        $('.book').text('')
        $('.reporting_period').text('')
        $('.location').text('')
        $('.officer').text('')
        $('.municipality').text('')
        cdrAjaxRequest()
    })
</script>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile(yii::$app->request->baseUrl . "/frontend/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
        // function generatData(e){
        //     e.preventDefault();
        $(document).ready(function() {
                cdrAjaxRequest()
         })
      

        $("#cdr_jev").click(function(e){
            // e.preventDefault();
            
            window.location.href = window.location.pathname + '?r=jev-preparation/cdr-jev&id=' + $('#cdr_id').val()
            // window.location.href = window.location.pathname + '?r=jev-preparation/cdr-jev&reporting_period=' + $('#reporting_period').val()
            // $.ajax({
            //     type:'POST',
            //     url:window.location.pathname +"?r=jev-preparation/insert-cdr",
            //     data:$("#filter").serialize(),
            // })
        
        })
        $('#final').click(function(e){
            e.preventDefault();
            $.ajax(
                {
                    type:'POST',
                    url:window.location.pathname + '?r=cdr/cdr-final',
                    data:{id:$('#cdr_id').val()},
                    success:function(data){
                        var res = JSON.parse(data)
                        if (res.isScuccess){
                            swal({
                                title:'Success',
                                type:'success',
                                button:false,
                                timer:3000
                            },function(){
                                location.reload(true);
                            })
                        }
                    }
                }
                
            )
        })

        

JS;
$this->registerJs($script);
?>