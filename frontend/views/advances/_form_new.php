<?php

use app\models\CashDisbursementSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advances-form">



    <!-- <div class="container panel panel-default"> -->
        <form id='save_data' style="margin:12px;">
            <?php
            if (!empty($model->id)) {


                echo "<input type='text' style='display:none' id='update_id' name='update_id' value='$model->id'/>";
            }

            ?>
            <div class="row">
                <div class="col-sm-3">
                    <label for="report"> Report Type</label>
                    <?php

                    $report = [
                        'Advances for Operating Expenses' => '101 OPEX CDR',
                        'Advances to Special Disbursing Officer' => '101 SDO CDR',
                        'RAPID LP SDO CDR' => 'RAPID LP SDO CDR',
                        'GJ' => 'GJ'
                    ];

                    echo Select2::widget([
                        'data' => $report,
                        'name' => 'report',
                        'id' => 'report',
                        'pluginOptions' => [
                            'placeholder' => 'Select Report'
                        ]
                    ])
                    ?>
                </div>
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
                        ]
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
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="particular">Fund Source</label>
                    <textarea name="particular" id="particular" cols="100" rows="2" style="width: 100%;max-width:100%"></textarea>
                </div>
            </div>

            <table class="table tabl-striped" id='transaction_table'>
                <thead>
                    <th>DV Number</th>
                    <!-- <th>Mode of Payment</th> -->
                    <th>Check Number</th>
                    <!-- <th>Ada Number</th> -->
                    <th>Check Date</th>
                    <th>Payee</th>
                    <th>Particular</th>
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
            $searchModel = new CashDisbursementSearch();
            $searchModel->is_cancelled = 0;

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];

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
                    "attribute" => "book_id",
                    "value" => "book.name"
                ],
                'mode_of_payment',
                'check_or_ada_no',
                'ada_number',
                'issuance_date',
                [
                    'label' => "DV Number",
                    "attribute" => "dv_aucs_id",
                    'value' => 'dvAucs.dv_number'
                ],
                [
                    'label' => "Payee",
                    "attribute" => "dvAucs.payee.account_name"
                ],
                [
                    'label' => "Particular",
                    "attribute" => "dvAucs.particular"
                ],
                [
                    'label' => "Amount Disbursed",
                    'format' => ['decimal', 2],
                    'value' => function ($model) {
                        $query = (new \yii\db\Query())
                            ->select(["SUM(dv_aucs_entries.amount_disbursed) as total_disbursed"])
                            ->from('dv_aucs')
                            ->join("LEFT JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
                            ->where("dv_aucs.id =:id", ['id' => $model->dv_aucs_id])
                            ->one();

                        return $query['total_disbursed'];
                    }
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
            <button type="submit" name="" id="add" class="btn btn-success" style="width: 100%;">Add</button>
        </form>
    <!-- </div> -->
    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }
        .fund_source{
            max-width: 400px;
            max-height: 50px;
        }
    </style>
</div>

<script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="/dti-afms-2/frontend/web/js/select2.min.js" />
<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<link href="/dti-afms-2/frontend/web/js/maskMoney.js" />

<script>
    var vacant = 0;
    var i = 1;
    var x = [0];
    var update_id = undefined;
    var cashflow = [];
    var accounts = [];
    var transaction_table_count = 0;


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
            var row = `<tr>
                    <td style='display:none' >
                     <input value='${result[i]['cash_disbursement_id']}'
                      type='text' name='cash_disbursement_id[]' class='cash_disbursement_id'/></td>

                    <td class='dv_number'> ${result[i]['dv_number']}</td>
                    <td class='check_number'> ${result[i]['check_or_ada_no']}</td>
                    <td class='issuance_date'> ${result[i]['issuance_date']}</td>
                    <td class='payee'> ${result[i]['payee']}</td>
                    <td class='particular'>${result[i]['particular']}</td>
                    <td> 
                         <textarea type='text' id='fund_source-${transaction_table_count}' class='fund_source' name='fund_source[]'>
                         ${result[i]['particular']}
                         </textarea>
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
            if ($("#update_id").val() > 0) {
                $(`#chart-${transaction_table_count}`).val(result[i]['object_code']).trigger('change')
                $(`#amount-${transaction_table_count}`).val(result[i]['amount']).trigger('change')
            }
            transaction_table_count++;
        }
    }
    $(document).ready(function() {

        $.getJSON('/dti-afms-2/frontend/web/index.php?r=database-view/sub-accounts')
            .then(function(data) {
                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.object_code,
                        text: val.object_code + ' ' + val.account_title
                    })
                })
                accounts = array
            })
    })
</script>
<?php

$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);

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

    $(document).ready(function(){

        // KUNG NAAY SULOD ANG UPDATE ID MAG POPULATE SA MGA DATA

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
    })

    
JS;

$this->registerJs($script);

?>