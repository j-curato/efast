<?php

use app\models\AdvancesEntriesForLiquidationSearch;
use app\models\AdvancesEntriesSearch;
use app\models\Payee;
use app\models\PoTransaction;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

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
            $particular = '';

            $payee = '';
            $check_date = '';
            $check_number = '';
            $reporting_period = '';
            $check_range = '';
            $transaction_id = '';
            if (!empty($model)) {
                $particular = $model->particular;
                $payee = $model->payee_id;
                $check_date = $model->check_date;
                $check_number = $model->check_number;
                $reporting_period = $model->reporting_period;
                $check_range = $model->check_range_id;
                $transaction_id = $model->po_transaction_id;
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
                    $province = Yii::$app->user->identity->province;
                    $q = PoTransaction::find();
                    if (
                        $province === 'adn' ||
                        $province === 'sdn' ||
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
                            'required' => true,
                        ]
                    ])
                    ?>
                </div>



            </div>
            <div class="row">




                <div class="col-sm-3">

                    <label for="transaction">Transaction</label>
                    <?php
                    // $po_transaction = PoTransaction::find()->asArray()
                    //     ->all();
                    // echo Select2::widget([
                    //     'data' => ArrayHelper::map($po_transaction, 'id', 'tracking_number'),
                    //     'name' => 'transaction',
                    //     'id' => 'transaction',
                    //     'value' => $transaction_id,
                    //     'pluginOptions' => [
                    //         'placeholder' => 'Select Transaction'
                    //     ],
                    //     'options' => [
                    //         'required' => true
                    //     ]

                    // ])
                    ?>
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
                    <th>Amount</th>
                </thead>
                <tbody>
                    <tr>
                        <td id="transaction_payee"></td>
                        <td id="transaction_particular"></td>
                        <td id="transaction_r_center"></td>
                        <td id="transaction_amount"></td>


                    </tr>
                </tbody>
            </table>


            <table class="table table-striped" id="transaction_table">

                <thead>
                    <th>Reporting Period</th>

                    <th>Province</th>
                    <th>Fund Source</th>
                    <th>Chart of Account</th>
                    <th>Miscellaneous Income</th>
                    <th>Withdrawals</th>
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
            <button class="btn btn-success" id='save' type="submit">Save</button>
        </form>

        <form id="add_data">

            <?php
            $searchModel = new AdvancesEntriesForLiquidationSearch();
            if (!empty(\Yii::$app->user->identity->province)) {
                $searchModel->province = \Yii::$app->user->identity->province;
                // echo \Yii::$app->user->identity->province;
            }
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $dataProvider->pagination = ['pageSize' => 10];
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
                    'label' => 'Amount',
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

            <button class="btn btn-primary" id="add" type="submit">Add</button>
        </form>

    </div>

</div>
<style>
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
</style>
<script src="/afms/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<script src="/afms/js/maskMoney.js" type="text/javascript"></script>
<link href="/afms/frontend/web/js/select2.min.js" />
<link href="/afms/frontend/web/css/select2.min.css" rel="stylesheet" />
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
        var fund_source = qwer.find('.fund_source').text();
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
                    <td > <input style='width:140px' type='month'data-date='' data-date-format='yyyy-mm' id='date_${transaction_table_count}' name='new_reporting_period[]' required /></td>
                    <td class='nft_number' style='display:none'> ${result[i]['nft_number']}</td>
                    <td class='report_type' style='display:none'> ${result[i]['report_type']}</td>
                    <td class=''province> ${result[i]['province']}</td>
                    <td class='fund_source'> ${result[i]['fund_source']}</td>

                    <td> 
                        <select id="chart-${transaction_table_count}" name="chart_of_account_id[]" required class="chart_of_account" style="width: 200px">
                            <option></option>
                        </select>
                    </td>
                    
                    <td> 
                        <div class='form-group' style='width:150px'>
                        <input type='text' id='liq_damages-${transaction_table_count}' class='form-control liq_damages' name='liq_damages[]'>
                        </div>
                    </td>
                    <td> 
                        <div class='form-group' style='width:150px'>
                        <input type='text' id='withdrawal-${transaction_table_count}' class='form-control withdrawal' name='withdrawal[]'>
                        </div>
                    </td>
                    <td> 
                        <div class='form-group' style='width:150px'>

                            <input type='text' id='vat_nonvat-${transaction_table_count}' class='form-control vat_nonvat' name='vat_nonvat[]'>
                        </div>

                    </td>
                    <td> 
                         <div class='form-group' style='width:150px'>
                            <input type='text' id='ewt-${transaction_table_count}' class='form-control expanded_tax' name='ewt[]'>
                         </div>

                    </td>
                    <td><a id='copy_${transaction_table_count}' class='btn btn-success ' type='button' onclick='copy(this)'><i class="fa fa-copy "></i></a></td>
                  
                    <td><button  class='btn btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
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
            $(`#chart-${transaction_table_count}`).select2({
                data: accounts,
                placeholder: "Select Chart of Account",

            });
            console.log(type)

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

                // console.log("re-align")

            }
            if ($('#update_type').val() === 'create') {
                $(`#date_${transaction_table_count}`).prop('disabled', true)

            }
            if (type == 'copy') {

                $(`#chart-${transaction_table_count} option:not(:selected)`).attr("disabled", true)
            }
            transaction_table_count++;
        }



    }

    var transaction = [];

    function chart() {
        return $.getJSON('/afms/frontend/web/index.php?r=chart-of-accounts/chart-of-accounts')
            .then(function(data) {
                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.uacs + ' ' + val.general_ledger
                    })
                })
                accounts = array
            })
    }
    $(document).ready(function() {

        chart()
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
    $('#add_data').submit(function(e) {
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
                }

            }
        })
    })
    $(document).ready(function(){
        if ($("#update_id").val()>0){
            $.when(chart() ).done((chart)=>{
                $.ajax({
                type:'POST',
                url:window.location.pathname + "?r=liquidation/update-liquidation",
                data:{
                    update_id:$('#update_id').val()
                },
                success:function(data){
                    var res=JSON.parse(data).entries
                    var liq = JSON.parse(data).liquidation
                    console.log(liq)
                    $("#transaction").val(liq['po_transaction_id']).trigger('change')
                    addToTransactionTable(res,$("#update_type").val())
                    getTotalAmounts()
                }

            })
            })
      
        }
    })
            
JS;
$this->registerJs($script);
?>