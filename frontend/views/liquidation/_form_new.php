<?php

use app\models\AdvancesEntriesSearch;
use app\models\AdvancesSearch;
use app\models\CheckRange;
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
            if (!empty($model)) {
                $particular = $model->particular;
                $payee = $model->payee_id;
                $check_date = $model->check_date;
                $check_number = $model->check_number;
                $reporting_period = $model->reporting_period;
            }
            ?>
            <div class="row">
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
                        ]
                    ])

                    ?>


                </div>

                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <?php
                    echo Select2::widget([
                        'data' => ArrayHelper::map(Payee::find()->asArray()->all(), 'id', 'account_name'),
                        'name' => 'payee',
                        'value' => $payee,
                        'id' => 'payee',
                        'pluginOptions' => [
                            'placeholder' => 'Select Payee'
                        ]
                    ])
                    ?>
                </div>

                <div class="col-sm-3">
                    <label for="check_number">Check Number</label>

                    <?php

                    echo "<input type='text' class='form-control' id='check_number' name='check_number' value='$check_number'/>
                    ";
                    ?>
                </div>

            </div>
            <div class="row">

                <div class="col-sm-3">
                    <label for="check_range">Check Range</label>
                    <?php
                    $check = (new \yii\db\Query())
                        ->select([
                            'id',
                            "CONCAT(check_range.from,' to ',check_range.to) as range"
                        ])
                        ->from('check_range')
                        ->all();
                    echo Select2::widget([
                        'data' => ArrayHelper::map($check, 'id', 'range'),
                        'name' => 'check_range',
                        'id' => 'check_range',
                        'pluginOptions' => [
                            'placeholder' => 'Select Range'
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3">

                    <label for="transaction">Transaction</label>
                    <?php
                    $po_transaction = PoTransaction::find()->asArray()
                        ->all();
                    echo Select2::widget([
                        'data' => ArrayHelper::map($po_transaction, 'id', 'tracking_number'),
                        'name' => 'transaction',
                        'id' => 'transaction',
                        'pluginOptions' => [
                            'placeholder' => 'Select Transaction'
                        ]
                    ])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="particular">Particular</label>

                    <?php

                    echo "<textarea name='particular' id='particular' rows='2' style='width: 100%;max-width:100%' value'>$particular</textarea>";
                    ?>
                </div>
            </div>

            <table class="table table-striped" id="transaction_table">

                <thead>
                    <th>Reporting Period</th>
                    <th>NFT Number</th>
                    <th>Report</th>
                    <th>Province</th>
                    <th>Fund Source</th>
                    <th>Chart of Account</th>
                    <th>Withdrawals</th>
                    <th>Vat/Non-Vat</th>
                    <th>Expanded Tax</th>
                </thead>
                <tbody>

                </tbody>
            </table>
            <button class="btn btn-success" id='save' type="submit">Save</button>
        </form>

        <form id="add_data">

            <?php
            $searchModel = new AdvancesEntriesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $gridColumn = [

                'id',


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
                [
                    'label' => 'NFT Number',
                    'value' => 'advances.nft_number'
                ],
                [
                    "label" => "Report Type",
                    "value" => "advances.report_type"
                ],

                [
                    "label" => "Province",
                    "attribute" => "advances.province"
                ],
                [
                    "label" => "Fund Source",
                    "attribute" => "fund_source"
                ],
                [
                    "label" => "Check Number",
                    "attribute" => "cashDisbursement.check_or_ada_no"
                ],
                [
                    "label" => "SL Object Code",
                    "attribute" => "subAccountView.object_code"
                ],
                [
                    "label" => "SL Account Title",
                    "attribute" => "subAccountView.account_title"
                ],
                [
                    "label" => "Amount",
                    "attribute" => "amount"
                ],
            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => Gridview::TYPE_PRIMARY,
                    'heading' => 'List of Advances'
                ],

                'columns' => $gridColumn
            ]); ?>

            <button class="btn btn-primary" id="add" type="submit">Add</button>
        </form>

    </div>

</div>
<style>
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

    #save {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>
<script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="/dti-afms-2/frontend/web/js/select2.min.js" />
<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<link href="/dti-afms-2/frontend/web/js/maskMoney.js" />
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
        addToTransactionTable([obj], copy)

    }


    function addToTransactionTable(result, type) {


        for (var i = 0; i < result.length; i++) {
            var row = `<tr>
                    <td style='display:none'> <input value='${result[i]['id']}' id='advances_${transaction_table_count}' class='advances_id' type='text' name='advances_id[]'/></td>
                    <td > <input style='width:140px' type='month'data-date='' data-date-format='yyyy-mm' id='date_${transaction_table_count}' name='new_reporting_period[]' required /></td>
                    <td class='nft_number'> ${result[i]['nft_number']}</td>
                    <td class='report_type'> ${result[i]['report_type']}</td>
                    <td class=''province> ${result[i]['province']}</td>
                    <td class='fund_source'> ${result[i]['fund_source']}</td>

                    <td> 
                        <select id="chart-${transaction_table_count}" name="chart_of_account_id[]" required class="chart_of_account" style="width: 200px">
                            <option></option>
                        </select>
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

            if ($('#update_id') != null) {
                $(`#chart-${transaction_table_count}`).val(result[i]['chart_of_account_id']).trigger('change')
                $(`#withdrawal-${transaction_table_count}`).val(result[i]['withdrawals'])
                $(`#vat_nonvat-${transaction_table_count}`).val(result[i]['vat_nonvat'])
                $(`#ewt-${transaction_table_count}`).val(result[i]['ewt_goods_services'])
                $(`#date_${transaction_table_count}`).val(result[i]['reporting_period'])
            }
            if (type == 're-align') {
                $(`#chart-${transaction_table_count}`).prop('disabled', true)
                $(`#withdrawal-${transaction_table_count}`).prop('disabled', true)
                $(`#vat_nonvat-${transaction_table_count}`).prop('disabled', true)
                $(`#ewt-${transaction_table_count}`).prop('disabled', true)
                $(`#advances_${transaction_table_count}`).prop('disabled', true)
                $(`#date_${transaction_table_count}`).prop('disabled', true)

                // console.log("re-align")

            }
            if ($('#update_type').val() === 'create') {
                $(`#date_${transaction_table_count}`).prop('disabled', true)

            }
            transaction_table_count++;
        }



    }


    $(document).ready(function() {
        $.getJSON('/dti-afms-2/frontend/web/index.php?r=chart-of-accounts/chart-of-accounts')
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
    $("#transaction").change(function(){
        $.ajax({
            type:"POST",
            url:window.location.pathname + '?=po-transaction/get-transaction',
            data:{id:$('#transaction').val()},
            success:function(data){
                var res = JSON.parse(data)
                
            }
        })
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
                  //  ,function(){
                        // window.location.href = window.location.pathname +"?r=liquidation/view&id=" +res.id
                   // }
                    )
                }

            }
        })
    })
    $(document).ready(function(){
        if ($("#update_id").val()>0){
            $.ajax({
                type:'POST',
                url:window.location.pathname + "?r=liquidation/update-liquidation",
                data:{
                    update_id:$('#update_id').val()
                },
                success:function(data){
                    var res=JSON.parse(data)
                    console.log(res)
                    
                    addToTransactionTable(res,$("#update_type").val())
                }

            })
        }
    })
            
JS;
$this->registerJs($script);
?>