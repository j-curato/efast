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
$bank_account_id  = '';
$cibr_id = '';

if (!empty($model->id)) {
    $bank_account_id = $model->fk_bank_account_id;
}
?>
<div class="jev-preparation-index" style="background-color: white;">
    <form id='filter' <?php if (!empty($model->id)) : ?> style="display:none" <?php endif; ?>>
        <div class="row">
            <div class="col-sm-2">
                <label for="reporting_period">Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'reporting_period',
                    'name' => 'reporting_period',
                    'value' => !empty($model->reporting_period) ? $model->reporting_period : '',
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
                <label for="bank_account">Bank Account</label>
                <?php
                $user_province = Yii::$app->user->identity->province;
                $val = '';
                $and = '';
                $sql = '';
                $params = [];
                if (
                    $user_province === 'adn' ||
                    $user_province === 'ads' ||
                    $user_province === 'sdn' ||
                    $user_province === 'sds' ||
                    $user_province === 'pdi'
                ) {
                    $and = 'WHERE';
                    $sql = YIi::$app->db->getQueryBuilder()->buildCondition('province=:province', $params);
                }
                $bank_accounts = Yii::$app->db->createCommand("SELECT id ,CONCAT(account_number,'-',province,'-',account_name) as account FROM bank_account
                $and $sql
                ")
                    ->bindValue(':province', $user_province)
                    ->queryAll();



                echo Select2::widget([
                    'name' => 'bank_account_id',
                    'data' => ArrayHelper::map($bank_accounts, 'id', 'account'),
                    'value' => $bank_account_id,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Bank Account'
                    ]
                ])

                ?>
            </div>
            <!-- <div class="col-sm-2">
                <label for="book">Book</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'id' => 'book',
                    'value' => !empty($model->book_name) ? $model->book_name : '',
                    'name' => 'book',
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div> -->
            <div class="col-sm-3">
                <label for="report_type">Advance Type</label>
                <?php
                $report_type  = Yii::$app->db->createCommand("SELECT `name`as id,`name` FROM report_type ")->queryAll();
                echo Select2::widget([
                    'data' => ArrayHelper::map($report_type, 'id', 'name'),
                    'value' => !empty($model->report_type) ? $model->report_type : '',
                    'name' => 'report_type',
                    'id' => 'report_type',
                    'pluginOptions' => [
                        'placeholder' => 'Select Advance Type'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <button class="btn btn-success" id="generate" style="margin-top:23px">Generate</button>

                <?php
                if (empty($model->id)) {

                    if (Yii::$app->user->can('create_cdr')) {
                        echo "<button class='btn btn-success' id='save' style='margin-top:23px'>Save</button>";
                        // echo "<input type='text' value='$model->is_final'>";
                    }
                } else {
                    echo "  <input type='text' id='cdr_id' value='$model->id' style='display:none'>";
                    if ($model->is_final === 1) {
                        echo "  <button class='btn btn-success' id='cdr_jev'  style='margin-top:23px'>Create Jev</button>";
                    }
                    if ($model->is_final === 0) {
                        echo "  <button class='btn btn-success' id='final'  style='margin-top:23px' >Final</button>";
                    }
                }

                ?>
            </div>
        </div>



    </form>

    <?php Pjax::begin(['id' => 'cibr', 'clientOptions' => ['method' => 'POST']]) ?>
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
                        Official Designation: Special Disbursing Officer
                    </span>
                    <span class="advance_type">

                    </span>
                </td>
            </tr>
            <tr style="border:1px solid white">
                <td colspan="8" class="header municipality">
                    <span> Municipality/City/Province: <?php echo $municipality; ?></span>
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
                    <span class="book"><?= !empty($book) ? $book : '' ?></span>
                </td>
                <td colspan="4" class="header" style="border-left:1px solid white;border-right:1px solid white;">
                    <span>
                        Register No : Landbank of the Philippines
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

            <?php
            $total_cash_advance = 0;
            $total_payments = 0;
            $x = 0;
            $balance = 0;
            $amount = 0;
            $withdrawals = 0;
            // if (!empty($dataProvider)) {
            //     foreach ($dataProvider as $data) {
            //         $amount =  (float) $data['amount'];
            //         $withdrawals = (float) $data['withdrawals'];
            //         $balance += $amount  - $withdrawals;
            //         if ($data['reporting_period'] === $reporting_period) {
            //             if ($x === 0) {
            //                 echo "<tr>
            //                 <td></td>
            //                 <td ></td>
            //                 <td></td>
            //                 <td class='amount'></td>
            //                 <td></td>
            //                 <td class='amount'>" . number_format($balance - $amount + $withdrawals, 2) . "</td>
            //                 <td></td>
            //                 <td></td>
            //                 <td></td>
            //                 <td></td>
            //                 <td></td>
            //                 <td class='amount'></td>
            //               </tr>";
            //                 $x++;
            //             }
            //             echo "<tr>
            //             <td>" . $data['reporting_period'] . "</td>
            //             <td >" . $data['check_number'] . "</td>
            //             <td>" . $data['particular'] . "</td>
            //             <td class='amount'>" . $data['amount'] . "</td>
            //             <td class='amount'>" . $data['withdrawals'] . "</td>
            //             <td class='amount'>" . number_format($balance, 2) . "</td>
            //             <td></td>
            //             <td></td>
            //             <td></td>
            //             <td>" . $data['gl_account_title'] . "</td>
            //             <td>" . $data['gl_object_code'] . "</td>
            //             <td class='amount'>" . $data['withdrawals'] . "</td>
            //            </tr>";
            //             $total_cash_advance += (float)$data['amount'];
            //             $total_payments += (float)$data['withdrawals'];
            //         }
            //     }
            //     //     echo "<pre>";
            //     //         var_dump($dataProvider);
            //     //    echo" </pre>";
            // }


            ?>
            <tr>

                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan="" style="text-align: center;font-weight:bold">Total</td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_cash_advance, 2) ?></td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_payments, 2) ?></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_payments, 2) ?></td>
            </tr>
            <!-- <tr>
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
            </tr> -->
            <?php
            $total_conso = 0;
            $total_vat = 0;
            $total_expanded = 0;
            $total_gross = 0;
            // if (!empty($consolidated)) {
            //     foreach ($consolidated as $conso) {
            //         $amnt = $conso['total'] != 0 ? number_format($conso['total'], 2) : '-';
            //         $vat = $conso['vat_nonvat'] != 0 ? number_format($conso['vat_nonvat'], 2) : '-';
            //         $expanded = $conso['expanded_tax'] != 0 ? number_format($conso['expanded_tax'], 2) : '-';
            //         $gross = $conso['gross_amount'] != 0 ? number_format($conso['gross_amount'], 2) : '-';

            //         echo "<tr>
            //             <td></td>
            //             <td ></td>
            //             <td></td>
            //             <td class='amount'></td>
            //             <td class='amount'></td>
            //             <td></td>

            //             <td class='amount'>" . $vat . "</td>
            //             <td class='amount'>" . $expanded . "</td>
            //             <td class='amount'>" . $gross . "</td>
            //             <td>" . $conso['account_title'] . "</td>
            //             <td>" . $conso['object_code'] . "</td>
            //             <td class='amount' >" . $amnt  . "</td>
            //         </tr>";
            //         $total_conso += (float)$conso['total'];
            //         $total_vat += (float)$conso['vat_nonvat'];
            //         $total_expanded += (float)$conso['expanded_tax'];
            //         $total_gross += (float)$conso['gross_amount'];
            //     }
            // }
            ?>
            <tr>

                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class='amount'><?= number_format($total_vat, 2) ?></td>
                <td class='amount'><?= number_format($total_expanded, 2) ?></td>
                <td class='amount'><?= number_format($total_gross, 2) ?></td>
                <td style="text-align: center;font-weight:bold">Total</td>
                <td></td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_conso, 2) ?></td>
            </tr>



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

    <?php Pjax::end() ?>
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
        var total_balance = 0;
        $("#data_table > tbody").html("");

        if (x != 1) {
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



            balance = parseFloat(balance) + parseFloat(data[i]['amount'], 2) - parseFloat(data[i]['withdrawals'], 2)
            var bal = parseFloat(balance).toFixed(2);
            addToDataTable(data[i], bal)

            total_balance = parseFloat(balance, 2)
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
        addToDataTable(q, balance)

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
                var book = res.book_name
                var reporting_period = res.reporting_period
                var location = res.location
                var municipality = res.municipality
                var officer = res.officer
                balance = res.balance
                console.log(conso)
                $('.book').text(book)
                $('.reporting_period').append(reporting_period)
                $('.location').append(location)
                $('.officer').append(officer)
                $('.municipality').text(municipality)
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
            if ($("#cdr_id").val() > 0) {
                cdrAjaxRequest()
            }
         })
      
        $("#save").click(function(e){
            e.preventDefault();
            $.ajax({
                type:'POST',
                url:window.location.pathname +"?r=cdr/insert-cdr",
                data:$("#filter").serialize(),
                success:function(data){
                    var res = JSON.parse(data)
                    console.log(res)
                    if (res.isSuccess){
                        swal({
                            title:'success',
                            type:'success',
                            button:false,
                            timer:3000,
                        },function(){
                            window.location.href= window.location.pathname +'?r=cdr/view&id=' +res.id
                        })
                    }
                    else{
                        swal({
                            title:'Failed',
                            text:'CDR already saved',
                            type:'error',
                            button:false,
                            timer:3000
                        })
                    }
                }
            })
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