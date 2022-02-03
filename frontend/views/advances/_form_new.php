<?php

use app\models\AdvancesCashDisbursementSearch;
use app\models\CashDisbursementSearch;
use app\models\FundSourceType;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */
/* @var $form yii\widgets\ActiveForm */
$bank_account_id='';
?>
<div id="dots5">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<div class="advances-form" style="display: none;">



    <!-- <div class="container panel panel-default"> -->
    <form id='save_data' style="margin:12px;">
        <?php
        if (!empty($model->id)) {


            echo "<input type='text' style='display:none' id='update_id' name='update_id' value='$model->id'/>";
        }

        ?>
        <div class="row">
            <!-- <div class="col-sm-3">
                <label for="report"> Report Type</label>
                <?php

                $report = [
                    'Advances for Operating Expenses' => 'Advances for Operating Expenses',
                    'Advances to Special Disbursing Officer' => 'Advances to Special Disbursing Officer',

                ];

                echo Select2::widget([
                    'data' => $report,
                    'name' => 'report',
                    'id' => 'report',
                    'pluginOptions' => [
                        'placeholder' => 'Select Report'
                    ],
                    'options' => []
                ])
                ?>
            </div> -->
            <div class="col-sm-3">
                <label for="report"> Province</label>

                <?php

                $province = [
                    'ADN' => 'ADN',
                    'ADS' => 'ADS',
                    'SDN' => 'SDN',
                    'SDS' => 'SDS',
                    'PDI' => 'PDI'
                ];
                echo Select2::widget([
                    'data' => $province,
                    'name' => 'province',
                    'id' => 'province',
                    'pluginOptions' => [
                        'placeholder' => 'Select Province'
                    ],
                    'options' => []
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <label for="reporting_period">Reporting Period</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'pluginOptions' => [
                        'startView' => 'months',
                        'minViewMode' => 'months',
                        'format' => 'yyyy-mm',
                        'autoclose' => true
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <label for="bank_account_id">Bank Account</label>
                <?php
                $bank_accounts_query = (new \yii\db\Query())
                    ->select(['bank_account.id', 'bank_account.account_number'])
                    ->from('bank_account');
                $bank_accounts = $bank_accounts_query->all();
                echo Select2::widget([
                    'data' => ArrayHelper::map($bank_accounts, 'id', 'account_number'),
                    'name' => 'bank_account_id',
                    'value' => $bank_account_id,
                    'pluginOptions' => [
                        'placeholder' => 'Select Bank Account'
                    ]

                ]);
                ?>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-sm-12">
                <label for="particular">Fund Source</label>
                <textarea name="particular" id="particular" cols="100" rows="2" style="width: 100%;max-width:100%"></textarea>
            </div>
        </div> -->

        <table class="table tabl-striped" id='transaction_table'>
            <thead>
                <th>Reporting Period</th>
                <th>DV Number</th>
                <th>Check Number</th>
                <!-- <th>Ada Number</th> -->
                <th>Check Date</th>
                <th>Payee</th>
                <th>Particular</th>
                <th>Report Type</th>
                <th>Fund Source Type</th>
                <th>Fund Source</th>
                <th>Sub Account</th>
                <th>Amount</th>
            </thead>
            <tbody></tbody>
        </table>

        <button class="btn btn-success" type="submit" style="width: 100%;">Save</button>
    </form>


    <form id="add_data">

        <?php
        $searchModel = new AdvancesCashDisbursementSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
        $dataProvider->pagination = ['pageSize' => 10];



        $gridColumn = [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'book_id',

            [

                'class' => '\kartik\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id,  'style' => 'width:20px;', 'class' => 'checkbox'];
                }
            ],
            [
                "label" => "Book",
                "attribute" => "book_name"
            ],
            'mode_of_payment',
            'check_or_ada_no',
            'ada_number',
            'issuance_date',
            [
                'label' => "DV Number",
                "attribute" => "dv_number",

            ],
            [
                'label' => "Payee",
                "attribute" => "payee"
            ],
            [
                'label' => "Particular",
                "attribute" => "particular"
            ],
            [
                'label' => "Amount Disbursed",
                'attribute' => "total_amount_disbursed",
                'format' => ['decimal', 2],
                // 'value' => function ($model) {
                //     $query = (new \yii\db\Query())
                //         ->select(["SUM(dv_aucs_entries.amount_disbursed) as total_disbursed"])
                //         ->from('dv_aucs')
                //         ->join("LEFT JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
                //         ->where("dv_aucs.id =:id", ['id' => $model->dv_aucs_id])
                //         ->one();

                //     return $query['total_disbursed'];
                // }
            ],


        ];
        // echo "<pre>";
        // var_dump($qwe);
        // echo "</pre>";
        // return ob_get_clean();
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => "List Of Disbursements",
            ],

            'toggleDataOptions' => ['maxCount' => 100],
            'pjax' => true,
            'export' => false,
            'floatHeaderOptions' => [
                'top' => 50,
                'position' => 'absolute',
            ],

            'columns' => $gridColumn
        ]); ?>
        <button type="submit" name="" id="add" class="btn btn-primary" style="width: 100%;">Add</button>
    </form>
    <!-- </div> -->
    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }

        .fund_source {
            max-width: 400px;
            max-height: 50px;
        }
    </style>
</div>

<!-- <script src="/afms/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="/afms/frontend/web/js/select2.min.js" />
<link href="/afms/frontend/web/css/select2.min.css" rel="stylesheet" />
<link href="/afms/frontend/web/js/maskMoney.js" /> -->

<script>
    var vacant = 0;
    var i = 1;
    var x = [0];
    0
    var update_id = undefined;
    var cashflow = [];
    var accounts = [];
    var transaction_table_count = 0;
    var report_types = []
    $(document).ready(function() {
        $.getJSON(url + '?r=report-type/get-report-type')
            .then(function(data) {
                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.name
                    })
                })
                report_types = array
            })
    })

    function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
        console.log(num)
    }

    function remove(i) {
        i.closest("tr").remove()
        // dv_count--
        // getTotal()
    }

    function copy(q) {
        var qwer = $(q).closest('tr')
        var cash_disbursement_id = qwer.find('.cash_disbursement_id').val();
        var dv_number = qwer.find('.dv_number').text();
        var check_number = qwer.find('.check_number').text();
        var issuance_date = qwer.find('.issuance_date').text();
        var payee = qwer.find('.payee').text();
        var particular = qwer.find('.particular').text();
        var obj = JSON.parse(`{
            
                
                "cash_disbursement_id":${cash_disbursement_id},
                "dv_number":"${dv_number}",
                "check_or_ada_no": "${check_number}",
                "issuance_date": "${issuance_date}",
                "payee": "${payee}",
                "particular": "${particular}",
                "fund_source": "",
                "ewt_goods_services": ${cash_disbursement_id}
     
       }`);

        console.log([obj])
        var qwe = '';
        // if ($('#update').val() != 'create') {
        //     qwe = 'copy';
        // }
        addToTransactionTable([obj])

    }

    function addToTransactionTable(result) {


        for (var i = 0; i < result.length; i++) {
            var entry_id = ''
            if (result[i]['entry_id'] != null) {
                entry_id = result[i]['entry_id']
            }
            var row = `<tr>
                    <td style='display:none' >
                        <input value='${result[i]['cash_disbursement_id']}' 
                        type='text' name='cash_disbursement_id[]' class='cash_disbursement_id'/>
                    </td>
                    <td style='display:none' >
                        <input value='${entry_id}' 
                        type='text' name='entry_id[]' class='entry_id'/>
                    </td>
                    <td > <input  type='month' id='date_${i}' name='new_reporting_period[]' required value='${result[i]['entry_reporting_period']}' /></td>
                    <td class='dv_number'> ${result[i]['dv_number']}</td>
                    <td class='check_number'> ${result[i]['check_or_ada_no']}</td>
                    <td class='issuance_date'> ${result[i]['issuance_date']}</td>
                    <td class='payee'> ${result[i]['payee']}</td>
                    <td class='particular'>${result[i]['particular']}</td>
                    
                    <td> 
                             <select id="report_type-${transaction_table_count}" name="report_type[]"  class="report_type" style="width: 200px">
                                <option></option>
                            </select>
                    </td>
                    <td> 
                             <select id="fund_source_type-${transaction_table_count}" name="fund_source_type[]"  class="fund_source_type" style="width: 200px">
                                <option></option>
                            </select>
                    </td>
                    <td> 
                         <textarea type='text' id='fund_source-${transaction_table_count}' class='fund_source' name='fund_source[]'>${result[i]['fund_source'].trim()}</textarea>
                    </td>

                    <td> 
                             <select id="chart-${transaction_table_count}" name="sub_account1[]" required class="sub_account_1" style="width: 200px">
                                <option></option>
                            </select>
                    </td>
                    
                    <td> 
                         <input type='text' id='amount-${transaction_table_count}' class='q' name='amount[]'>
                    </td>
     
                    <td><a id='copy_${transaction_table_count}' class='btn btn-success ' type='button' onclick='copy(this)'><i class="fa fa-copy "></i></a></td>
                    <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
                `
            $("#transaction_table tbody").append(row)
            $(`#amount-${transaction_table_count}`).maskMoney({
                allowNegative: true
            });
            $(`#chart-${transaction_table_count}`).select2({
                data: accounts,
                placeholder: "Select Chart of Account",

            });
            $(`#fund_source_type-${transaction_table_count}`).select2({
                data: fund_source_type,
                placeholder: "Select Fund Source Type",

            });
            $(`#report_type-${transaction_table_count}`).select2({
                data: report_types,
                placeholder: "Select Report Type Type",

            });
            if ($("#update_id").val() > 0) {
                $(`#chart-${transaction_table_count}`).val(result[i]['object_code']).trigger('change')
                $(`#amount-${transaction_table_count}`).val(result[i]['amount']).trigger('change')
                $(`#fund_source_type-${transaction_table_count}`).val(result[i]['fund_source_type']).trigger('change')
                $(`#report_type-${transaction_table_count}`).val(result[i]['entry_report_type']).trigger('change')
            }
            transaction_table_count++;
        }
    }



    var fund_source_type = []
</script>
<?php

$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<?php
SweetAlertAsset::register($this);
$script = <<<JS


        var row=undefined
    // ADD DATA TO TRANSACTION TABLE
    $("#add_data").submit(function(e){
        e.preventDefault();
        
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=advances/add-data',
            data:$('#add_data').serialize(),
            success:function(data){
                var res = JSON.parse(data)
                console.log(res)
                addToTransactionTable(res)

            }
        })
    })
    //SAVE TO DATABASE
    $('#save_data').submit(function(e){
        e.preventDefault();
        
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=advances/insert-advances',
            data:$('#save_data').serialize(),
            success:function(data){
                console.log(data)
                var res =JSON.parse(data)
                    var keys = [];
                    for(var k in res.error) keys.push(k);
                console.log(keys)
                if (res.isSuccess){
                    swal({
                        type:'success',
                        title:'Success',
                        timer: 3000,
                         button: false
                    },function(){
                        window.location.href= window.location.pathname + "?r=advances/view&id=" + res.id
                    })
                }
                
            }
        })
    })
   function subAccounts(){
       
        return $.getJSON(window.location.pathname + '?r=database-view/sub-accounts')
    }
    $.when(subAccounts()).done(function(sub_accounts){
                var array = []
                $.each(sub_accounts, function(key, val) {
                    array.push({
                        id: val.object_code,
                        text: val.object_code + ' ' + val.account_title
                    })
                })
                accounts = array
                if ($('#update_id').val()>0){

                    $.ajax({
                        type:'POST',
                        url:window.location.pathname + "?r=advances/update-advances",
                        data:{
                            update_id:$('#update_id').val()
                        },
                        success:function(data){
                            var res= JSON.parse(data)
                            console.log(res)

                            $('#report').val(res[0]['report_type']).trigger('change');
                            $('#province').val(res[0]['province']).trigger('change');
                            $('#particular').val(res[0]['particular']).trigger('change');
                            $('#reporting_period').val(res[0]['reporting_period'])
                            addToTransactionTable(res)

                        }
                        

                    })

                }   
                

                setTimeout(() => {
                   $('.advances-form').show()
                   $("#dots5").hide()
                }, 1000);

    })
    $(document).ready(function(){
        getFundSourceType().then((data) => {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.name,
                    text: val.name 
                })
            })
            fund_source_type = array
            
            console.log(fund_source_type)
        })
        // $('#report').change(function(){
        //     addToTransactionTable()
        // })

        // $('.report_type').select2()
        

        // KUNG NAAY SULOD ANG UPDATE ID MAG POPULATE SA MGA DATA

   
    })

    
JS;

$this->registerJs($script);

?>