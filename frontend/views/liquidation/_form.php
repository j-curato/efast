<?php

use yii\helpers\Url;
use app\models\Payee;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\PoTransaction;
use app\models\AdvancesEntriesSearch;
use aryelds\sweetalert\SweetAlertAsset;
use app\models\AdvancesEntriesForLiquidationSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="liquidation-form">


    <div class="">
        <form id='save_data'>
            <?php
            !empty($model->id) ? $x = $model->id : $x = '';
            !empty($update_type) ? $t = $update_type : $t = '';
            echo "<input type='text' value='$x' name='update_id' id='update_id' style='display:none'/>";
            echo "<input type='text' value='$t' name='update_type' id='update_type' style='display:none'/>";
            $_SESSION['nonce'] = $nonce = md5('salt' . microtime());
            echo "<input type='hidden' value='$nonce' name='save_form_token' />";
            $particular = '';
            $payee = '';
            $check_date = '';
            $check_number = '';
            $reporting_period = '';
            $check_range = '';
            $transaction_id = '';
            if (!empty($model)) {

                $check_date = $model->check_date;
                $check_number = $model->check_number;
                $reporting_period = $model->reporting_period;
                $check_range = $model->check_range_id;
                $transaction_id = $model->po_transaction_id;
                if (empty($model->po_transaction_id)) {
                    $particular = $model->particular;
                    $payee = $model->payee;
                }
            }

            ?>



            <div class="row ">
                <div class="col-sm-3">
                    <label for="reporting_peirod">Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'id' => 'reporting_period',
                        'value' => $reporting_period,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'autoclose' => true,
                            'startView' => 'months',
                            'minViewMode' => 'months'
                        ],
                        'options' => [
                            'required' => true,
                            'readOnly' => true,
                            'style' => 'background-color:white'
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="check_date">Date</label>
                    <?php
                    echo DatePicker::widget([

                        'name' => 'check_date',
                        'id' => 'check_date',
                        'value' => $check_date,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true
                        ],
                        'options' => [
                            'required' => true, 'readOnly' => true,
                            'style' => 'background-color:white'
                        ]
                    ])

                    ?>


                </div>
                <div class="col-sm-3">
                    <label for="check_range">Check Range</label>
                    <?php
                    $province = strtolower(Yii::$app->user->identity->province);
                    $q = PoTransaction::find();
                    if (
                        $province === 'adn' ||
                        $province === 'ads' ||
                        $province === 'sds' ||
                        $province === 'sdn' ||
                        $province === 'pdi'
                    ) {
                        $check = (new \yii\db\Query())
                            ->select([
                                'id',
                                "CONCAT(check_range.from,' to ',check_range.to) as range"
                            ])
                            ->from('check_range')
                            ->where('province =:province', ['province' => $province])
                            ->all();
                    } else {
                        $check = (new \yii\db\Query())
                            ->select([
                                'id',
                                "CONCAT(check_range.from,' to ',check_range.to) as range"
                            ])
                            ->from('check_range')
                            ->all();
                    }

                    echo Select2::widget([
                        'data' => ArrayHelper::map($check, 'id', 'range'),
                        'name' => 'check_range',
                        'id' => 'check_range',
                        'value' => $check_range,
                        'pluginOptions' => [
                            'placeholder' => 'Select Range'
                        ],
                        'options' => [
                            // 'required' => true,
                        ]
                    ])
                    ?>
                </div>



            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= Select2::widget([
                        'name' => 'fk_certified_by',
                        // 'data' => ArrayHelper::map($requested_by, 'employee_id', 'employee_name'),
                        'options' => ['placeholder' => 'Search for a Employee ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['employee/search-employee']),
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],

                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <?= Select2::widget([
                        'name' => 'fk_approved_by',
                        // 'data' => ArrayHelper::map($approved_by, 'employee_id', 'employee_name'),
                        'options' => ['placeholder' => 'Search for a Employee ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['employee/search-employee']),
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row">




                <div class="col-sm-3">

                    <label for="dv_number">DV Number</label>
                    <input type="text" name="dv_number" id='dv_number' required class="form-control">
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="transaction">Transactions</label>
                    <select id="transaction" name="transaction" class="transaction select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="check_number">Check Number</label>

                    <?php

                    echo "<input type='number' class='form-control' id='check_number' required name='check_number' placeholder='Check Number' value='$check_number'/>
                    ";
                    ?>
                </div>
            </div>

            <table id="transaction_details" class="table
            ">
                <thead>

                    <th>Payee</th>
                    <th>Particular</th>
                    <th>Responsibility Center</th>
                    <th>Gross Amount</th>
                </thead>
                <tbody>
                    <tr>
                        <td id="transaction_payee"><?php echo $payee ?></td>
                        <td id="transaction_particular"><?php echo $particular ?></td>
                        <td id="transaction_r_center"><?php ?></td>
                        <td id="transaction_amount"><?php ?></td>

                    </tr>
                </tbody>
            </table>


            <table class="" id="transaction_table">

                <thead>
                    <th>Reporting Period</th>

                    <th>Province</th>
                    <th>Fund Source</th>
                    <th>Chart of Account</th>
                    <th>Miscellaneous Income</th>
                    <th style="padding-left:30px">Withdrawals (Net Amount)</th>
                    <th>Sales Tax(Vat/Non-Vat)</th>
                    <th>Income Tax (Expanded Tax)</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>

                        <td class="total_row" colspan="4">Total</td>
                        <td class="total_row" id="total_liquidation"></td>
                        <td class="total_row" id="total_withdrawal"></td>
                        <td class="total_row" id="total_vat"></td>
                        <td class="total_row" id="total_expanded"></td>

                    </tr>
                    <tr>
                        <td class="total_row" colspan="4">Grand Total</td>
                        <td class="total_row" id="grand_total"></td>

                    </tr>

                </tfoot>
            </table>
            <?php
            $session = Yii::$app->session;

            // $form_token =$session['form_token'];

            echo "<input type='hidden' style='width:100%' value='{$session->get('form_token')}' name='token' />"
            ?>
            <button class="btn btn-success" id='save' type="submit">Save</button>
        </form>
        <form id="add_data">

            <?php

            $gridColumn = [
                [
                    'hAlign' => 'center',
                    'class' => '\kartik\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return [
                            'value' => $model->id,
                            'onchange' => 'enableDisable(this)',
                            'style' => 'width:20px;',
                            'name' => 'check',
                            'class' => 'checkbox', ''
                        ];
                    }
                ],
                'province',
                'fund_source',
                [
                    'label' => 'Gross Amount',
                    'attribute' => 'amount',
                    'format' => ['decimal', 2]
                ],
                [
                    'attribute' => 'total_liquidation',
                    'format' => ['decimal', 2]
                ],
                [
                    'label' => 'Balance',
                    'attribute' => 'balance',
                    'format' => ['decimal', 2]
                ],
                'particular'



            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => Gridview::TYPE_PRIMARY,
                    'heading' => 'List of Advances'
                ],
                'pjax' => true,

                'columns' => $gridColumn
            ]); ?>

            <button class="btn btn-primary" id="add" type="text">Add</button>
        </form>


    </div>

</div>
<style>
    table,
    tr {
        max-width: 100%;
    }

    td {
        padding: 5px;
    }

    .total_row {
        text-align: center;
        font-weight: bold;
    }

    .liquidation-form {
        padding: 2rem;
        background-color: white;
    }

    .grid-view td {
        white-space: normal;
        width: 10rem;
        padding: 0;
    }

    #add {
        width: 100%;
    }

    .form-control {
        border-radius: 5px;
    }

    #save {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        border-left: none;
    }

    .select2-container .select2-selection--single {

        height: 34px;
    }

    .amount {
        margin-top: 15px;


    }
</style>
<script src="<?= yii::$app->request->baseUrl ?>/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<script src="<?= yii::$app->request->baseUrl ?>/js/maskMoney.js" type="text/javascript"></script>
<link href="<?= yii::$app->request->baseUrl ?>/frontend/web/js/select2.min.js" />
<link href="<?= yii::$app->request->baseUrl ?>/frontend/web/css/select2.min.css" rel="stylesheet" />
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);

?>

<script>
    var vacant = 0;
    var i = 1;
    var x = [0];
    var update_id = undefined;
    var accounts = [];
    var dv_count = 0;
    var transaction_table_count = 0

    function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
        console.log(num)
    }

    function remove(i) {
        i.closest("tr").remove()

    }

    function getTotalAmounts() {
        var total_liquidation = 0;
        var total_withdrawal = 0;
        var total_vat = 0;
        var total_expanded = 0;
        var grand_total = 0;

        $('.liq_damages').maskMoney('unmasked');

        $(".liq_damages").each(function() {
            // console.log($(this).val().split(",").join(""))
            total_liquidation += parseFloat($(this).val().split(",").join("")) || 0;
        });
        $(".withdrawal").each(function() {
            total_withdrawal += parseFloat($(this).val().split(",").join("")) || 0;
        });
        $(".vat_nonvat").each(function() {
            total_vat += parseFloat($(this).val().split(",").join("")) || 0;
        });
        $(".expanded_tax").each(function() {
            total_expanded += parseFloat($(this).val().split(",").join("")) || 0;
        });

        $("#total_liquidation").text(thousands_separators(total_liquidation))
        $("#total_withdrawal").text(thousands_separators(total_withdrawal))
        $("#total_vat").text(thousands_separators(total_vat))
        $("#total_expanded").text(thousands_separators(total_expanded))
        grand_total = total_liquidation + total_withdrawal + total_vat + total_expanded
        $("#grand_total").text(thousands_separators(grand_total))

    }

    function copy(q) {
        var qwer = $(q).closest('tr')
        var id = qwer.find('.advances_id').val();
        var nft_number = qwer.find('.nft_number').text();
        var report_type = qwer.find('.report_type').text();
        var province = qwer.find('.province').text();
        var fund_source = qwer.find('.fund_source').text().trim();
        var chart_of_account_id = qwer.find('.chart_of_account').val() != null ? qwer.find('.chart_of_account').val() : 0;
        var withdrawal = qwer.find('.withdrawal').val();
        var vat_nonvat = qwer.find('.vat_nonvat').val();
        var expanded_tax = qwer.find('.expanded_tax').val();
        var obj = JSON.parse(`{
                "id":${id},
                "nft_number":"${nft_number}",
                "report_type": "${report_type}",
                "province": "${province}",
                "fund_source": "${fund_source}",
                "chart_of_account_id":"${chart_of_account_id}" ,
                "withdrawals":0,
                "vat_nonvat":0,
                "expanded_tax": 0
     
        }`);

        console.log([obj])
        var qwe = '';
        if ($('#update').val() != 'create') {
            qwe = 'copy';
        }
        console.log(qwe)
        addToTransactionTable([obj], qwe)

    }


    function addToTransactionTable(result, type) {


        for (var i = 0; i < result.length; i++) {
            var row = `<tr>
                    <td style='display:none'> <input value='${result[i]['id']}' id='advances_${transaction_table_count}' class='advances_id' type='text' name='advances_id[]'/></td>
                    <td > <input type='month'data-date='' data-date-format='yyyy-mm' id='date_${transaction_table_count}' name='new_reporting_period[]' required /></td>
                    <td class='nft_number' style='display:none'> ${result[i]['nft_number']}</td>
                    <td class='report_type' style='display:none'> ${result[i]['report_type']}</td>
                    <td class=''province> ${result[i]['province']}</td>
                    <td class='fund_source'> ${result[i]['fund_source']}</td>

                    <td> 
                        <select id="chart-${transaction_table_count}" name="chart_of_account_id[]" required class="chart-of-accounts" style="width: 200px">
                            <option></option>
                        </select>
                    </td>
                    
                    <td> 
                        <div class='form-group' style='width:150px'>
                        <input type='text' id='liq_damages-${transaction_table_count}' class='form-control liq_damages amount' name='liq_damages[]'>
                        </div>
                    </td>
                    <td> 
                        <div class='form-group' style='width:150px'>
                        <input type='text' id='withdrawal-${transaction_table_count}' class='form-control withdrawal amount' name='withdrawal[]'>
                        </div>
                    </td>
                    <td> 
                        <div class='form-group' style='width:150px'>

                            <input type='text' id='vat_nonvat-${transaction_table_count}' class='form-control vat_nonvat amount' name='vat_nonvat[]'>
                        </div>

                    </td>
                    <td> 
                         <div class='form-group' style='width:150px'>
                            <input type='text' id='ewt-${transaction_table_count}' class='form-control expanded_tax amount' name='ewt[]'>
                         </div>

                    </td>
                    <td><button id='copy_${transaction_table_count}' class='btn-xs btn-success ' type='button' onclick='copy(this)'><i class="fa fa-copy "></i></button></td>
                  
                    <td><button  class='btn-xs btn-danger ' id='remove_${transaction_table_count}'  onclick='remove(this)'><i class="fa fa-times"></i></button></td></tr>
                `
            $("#transaction_table tbody").append(row)
            $(`#liq_damages-${transaction_table_count}`).maskMoney({
                allowNegative: true
            });
            $(`#withdrawal-${transaction_table_count}`).maskMoney({
                allowNegative: true
            });
            $(`#vat_nonvat-${transaction_table_count}`).maskMoney({
                allowNegative: true
            });
            $(`#ewt-${transaction_table_count}`).maskMoney({
                allowNegative: true
            });
            // $(`#chart-${transaction_table_count}`).select2({
            //     data: accounts,
            //     placeholder: "Select Chart of Account",

            // });
            accountingCodesSelect()

            var x = result[i]['fund_source']
            var y = x.split(' ').slice(0, 2).join(' ');
            console.log(x.split(' ').slice(0, 2))
            if (y.toLowerCase() == 'rapid lp') {
                $(`#vat_nonvat-${transaction_table_count}`).maskMoney('destroy')
                $(`#ewt-${transaction_table_count}`).maskMoney('destroy')
                $(`#liq_damages-${transaction_table_count}`).maskMoney('destroy')
                $(`#vat_nonvat-${transaction_table_count}`).prop('readonly', true)
                $(`#ewt-${transaction_table_count}`).prop('readonly', true)
                $(`#liq_damages-${transaction_table_count}`).prop('readonly', true)

            }

            if ($('#update_id') != null) {
                $(`#chart-${transaction_table_count}`).val(result[i]['chart_of_account_id']).trigger('change')
                $(`#withdrawal-${transaction_table_count}`).val(result[i]['withdrawals'])
                $(`#liq_damages-${transaction_table_count}`).val(result[i]['liquidation_damage'])
                $(`#vat_nonvat-${transaction_table_count}`).val(result[i]['vat_nonvat'])
                $(`#ewt-${transaction_table_count}`).val(result[i]['expanded_tax'])
                $(`#date_${transaction_table_count}`).val(result[i]['reporting_period'])
            }
            if (type == 're-align') {
                $(`#chart-${transaction_table_count}`).prop('disabled', true)
                $(`#withdrawal-${transaction_table_count}`).prop('disabled', true)
                $(`#vat_nonvat-${transaction_table_count}`).prop('disabled', true)
                $(`#liq_damages-${transaction_table_count}`).prop('disabled', true)
                $(`#ewt-${transaction_table_count}`).prop('disabled', true)
                $(`#advances_${transaction_table_count}`).prop('disabled', true)
                $(`#date_${transaction_table_count}`).prop('disabled', true)
                $(`#remove_${transaction_table_count}`).hide()

                // console.log("re-align")

            }
            if ($('#update_type').val() === 'create') {
                $(`#date_${transaction_table_count}`).val('')

                $(`#date_${transaction_table_count}`).prop('disabled', true)

            }
            if (type == 'copy') {

                $(`#chart-${transaction_table_count} option:not(:selected)`).attr("disabled", true)
            }
            transaction_table_count++;
        }



    }

    var transaction = [];

    // function chart() {
    //     return $.getJSON(window.location.pathname + '?r=chart-of-accounts/chart-of-accounts')
    //         .then(function(data) {
    //             var array = []
    //             $.each(data, function(key, val) {
    //                 array.push({
    //                     id: val.id,
    //                     text: val.uacs + ' ' + val.general_ledger
    //                 })
    //             })
    //             accounts = array
    //         })
    // }
    $(document).ready(function() {

        // chart()
        $('.chart-of-accounts').select2({
            ajax: {
                url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code',
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                }
            },
            placeholder: 'Search Accounting Code'
        });

        $.getJSON(window.location.pathname + '?r=po-transaction/get-all-transaction')
            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.tracking_number
                    })
                })
                transaction = array
                $('#transaction').select2({
                    data: transaction,
                    placeholder: "Select Transaction",

                })

            });



        $("#check_range").on('change', function(e) {
            e.preventDefault()
            // console.log($(this).val())
            console.log(window.location.href)
            $.pjax({
                container: "#w0-pjax",
                url: window.location.href,
                type: 'POST',
                data: {
                    check_range_id: $(this).val()
                }
            });
        })
    })



    function enableDisable(checkbox) {
        var isDisable = true
        if (checkbox.checked) {
            isDisable = false
        }
        enableInput(isDisable, checkbox.value)

    }

    function enableInput(isDisable, index) {
        $(`#amount_${index}-disp`).prop('disabled', isDisable);
        $(`#amount_${index}`).prop('disabled', isDisable);
        $(`#amount_disbursed_${index}-disp`).prop('disabled', isDisable);
        $(`#amount_disbursed_${index}`).prop('disabled', isDisable);
        $(`#vat_nonvat_${index}-disp`).prop('disabled', isDisable);
        $(`#vat_nonvat_${index}`).prop('disabled', isDisable);
        $(`#ewt_goods_services_${index}-disp`).prop('disabled', isDisable);
        $(`#ewt_goods_services_${index}`).prop('disabled', isDisable);
        $(`#compensation_${index}-disp`).prop('disabled', isDisable);
        $(`#compensation_${index}`).prop('disabled', isDisable);
        $(`#other_trust_liabilities_${index}-disp`).prop('disabled', isDisable);
        $(`#other_trust_liabilities_${index}`).prop('disabled', isDisable);
        // console.log(index)
        // button = document.querySelector('.amount_1').disabled=false;
        // console.log(  $('.amount_1').disaled)

    }
</script>


<?php
$script = <<<JS
    // ON change transction dropdown
    $("#transaction").change(function(e){
        e.preventDefault();
        
          console.log('qwer')
        $.ajax({
            type:"POST",
            url:window.location.pathname + '?r=po-transaction/get-transaction',
            data:{id:$("#transaction").val()},
            success:function(data){
                var res = JSON.parse(data)
                console.log(res)
                $("#transaction_payee").text(res.payee)
                $("#transaction_particular").text(res.particular)
                $("#transaction_amount").text(res.amount)
                $("#transaction_r_center").text(res.r_center_name)

                
            }
        })
    })

    $('#transaction_table').on('change keyup',['.liq_damages',
        '.withdrawal',
        '.vat_nonvat',
        '.expanded_tax'],()=>{
           getTotalAmounts()
        })


//  ADD DATA TO TRANSACTION TABLE
    $('#add').click(function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=liquidation/add-advances',
            data: $('#add_data').serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                console.log(res)
                addToTransactionTable(res)

            }
        })
    })
    $('#reporting_period').change(function(){
        if ($('#update_type').val()!='re-align'){
            $('.new_reporting_period').each(function(){
                this.val($('#reporting_period').val())
            })
        }
    })
    
    // SAVE DATA TO DATABASE
    $('#save_data').submit(function(e) {
        e.preventDefault();
        $('#save').attr('disabled',true)
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=liquidation/insert-liquidation',
            data: $('#save_data').serialize(),
            success: function(data) {
                console.log(data)
                var res = JSON.parse(data)
                console.log(res.id)
                // addToTransactionTable(res)
                if (res.isSuccess){
                    swal({
                        title:'Success',
                        type:'success',
                        button:false,

                    }
                   ,function(){
                        window.location.href = window.location.pathname +"?r=liquidation/view&id=" +res.id
                   }
                    )
                }
                else{
                    swal({
                        title:res.error,
                        type:'error',
                        button:false,

                    })
                    $('#save').attr('disabled',false)
                }

            }
        })
    })
    $('#reporting_period').change(function(){
        var r_period =$('#reporting_period').val()
        var d1 = new Date(r_period);
        var d2 = new Date('2021-09');
        var notSame = d1.getTime() >= d2.getTime();
        console.log(notSame)



        $('#dv_number').attr('disabled',notSame)

    })
    $(document).ready(function(){
        if ($("#update_id").val()>0){
            // $.when(chart() ).done((chart)=>{
            //     $.ajax({
            //     type:'POST',
            //     url:window.location.pathname + "?r=liquidation/update-liquidation",
            //     data:{
            //         update_id:$('#update_id').val()
            //     },
            //     success:function(data){
            //         var res=JSON.parse(data).entries
            //         var liq = JSON.parse(data).liquidation
            //         console.log(liq)
            //         $("#transaction").val(liq['po_transaction_id']).trigger('change')
            //         $("input[name='dv_number']").val(liq['dv_number'])
            //         addToTransactionTable(res,$("#update_type").val())
            //         getTotalAmounts()

            //     }

            // })
            // })
      
        }
    })
            
JS;
$this->registerJs($script);
?>