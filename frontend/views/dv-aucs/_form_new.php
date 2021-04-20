<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->

<?php

use app\models\FundClusterCode;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use GuzzleHttp\Psr7\Query;
use kartik\money\MaskMoney;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<div class="test">




    <div id="container" class="container">
        <form id='save_data' method='POST'>
            <input type="text" name='book_id' id="book_id" style="display: none;">
            <?php
            $q = 0;
            if (!empty($update_id)) {

                $q = $update_id;
            }
            echo " <input type='text' id='update_id' name='update_id' value='$q' style='display:none' >";
            ?>
            <div class="row">

                <div class="col-sm-3">
                    <label for="reporting_period">Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'id' => 'reporting_period',
                        // 'value' => '12/31/2010',
                        // 'options' => ['required' => true],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView' => "year",
                            'minViewMode' => "months",
                        ]
                    ]);
                    ?>
                </div>

                <div class="col-sm-3" style="height:60x">
                    <label for="transaction">Transaction Type</label>
                    <select id="transaction" name="transaction_type" class="transaction select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>

                <div class="col-sm-3" style="height:60x">
                    <label for="nature_of_transaction">Nature of Transaction</label>
                    <select id="nature_of_transaction" name="nature_of_transaction" class="nature_of_transaction select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="mrd_classification">MRD Classification</label>
                    <select id="mrd_classification" name="mrd_classification" class="mrd_classification select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <select id="payee" name="payee" class="payee select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
                <!-- <div class="col-sm-3">
                    <label for="book">Book</label>
                    <select id="book" name="book" class="book select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div> -->
            </div>
            <div class="row">
                <textarea name="particular" name="particular" id="particular" placeholder="PARTICULAR" required rows="3"></textarea>
            </div>

            <table id="transaction_table" class="table table-striped">
                <thead>
                    <th>Ors ID</th>
                    <th>Serial Number</th>
                    <th>Particular</th>
                    <th>Payee</th>
                    <th>Total Obligated</th>
                    <th>Amount Disbursed</th>
                    <th>2306 (VAT/ Non-Vat)</th>
                    <th>2307 (EWT Goods/Services)</th>
                    <th>1601C (Compensation)</th>
                    <th>Other Trust Liabilities</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success" style="width: 100%;" id="save" name="save"> SAVE</button>
        </form>
        <form name="add_data" id="add_data">

            <div style="display: none;">
                <input type="text" id="transaction_type" name="transaction_type">
                <input type="text" id="dv_count" name="dv_count">
            </div>
            <!-- RAOUDS ANG MODEL ANI -->
            <!-- NAA SA CREATE CONTROLLER NAKO GE CHANGE -->

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    // 'type' => GridView::TYPE_PRIMARY,
                    'heading' => 'List of Areas',
                ],
                'floatHeaderOptions' => [
                    'top' => 50,
                    'position' => 'absolute',
                ],
                'pjax' => true,
                'columns' => [

                    // [
                    //     'label' => 'MFO/PAP Code',
                    //     'attribute' => 'recordAllotmentEntries.recordAllotment.mfoPapCode.code',
                    //     // 'filter' => Html::activeDropDownList(
                    //     //     $searchModel,
                    //     //     'recordAllotment.fund_cluster_code_id',
                    //     //     ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                    //     //     ['class' => 'form-control', 'prompt' => 'Major Accounts']
                    //     // )

                    // ],
                    // [
                    //     'label' => 'MFO/PAP Code Name',
                    //     'attribute' => 'recordAllotmentEntries.recordAllotment.mfoPapCode.name'
                    // ],

                    // [
                    //     'label' => 'Fund Source Code',
                    //     'attribute' => 'recordAllotmentEntries.recordAllotment.fundSource.name'
                    // ],
                    // [
                    //     'label' => 'Object Code',
                    //     'value' => function ($model) {
                    //         if ($model->process_ors_id != null) {
                    //             return $model->raoudEntries->chartOfAccount->uacs;
                    //         } else {
                    //             return $model->recordAllotmentEntries->chartOfAccount->uacs;
                    //         }
                    //     }
                    // ],
                    'serial_number',
                    'transaction.particular',
                    'transaction.payee.account_name',
                    // [
                    //     'label' => 'Ors Number',
                    //     'attribute' => 'process_ors_id',
                    //     'value' => function ($model) {
                    //         // if ($model->process_ors_id != null) {
                    //         //     return $model->raoudEntries->chartOfAccount->general_ledger;
                    //         // } else {
                    //         //     return $model->recordAllotmentEntries->chartOfAccount->general_ledger;
                    //         // }
                    //         return $model->processOrs->serial_number;
                    //     }
                    // ],
                    // [
                    //     'label' => 'General Ledger',
                    //     // 'attribute' => 'recordAllotmentEntries.chartOfAccount.general_ledger'
                    //     'value' => function ($model) {
                    //         if ($model->process_ors_id != null) {
                    //             return $model->raoudEntries->chartOfAccount->general_ledger;
                    //         } else {
                    //             return $model->recordAllotmentEntries->chartOfAccount->general_ledger;
                    //         }
                    //     }
                    // ],
                    // [
                    //     'label' => 'Amount',
                    //     'attribute' => 'recordAllotmentEntries.amount'
                    // ],

                    [
                        'label' => 'Total Obligated',
                        'value' => function ($model) {
                            $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount)as total,process_ors.id as ors_id
                            FROM process_ors,raouds,raoud_entries
                            where process_ors.id = raouds.process_ors_id
                            AND raouds.id=raoud_entries.raoud_id
                            AND process_ors.id= :ors_id
                            GROUP BY process_ors.id")
                                ->bindValue(":ors_id", $model->id)
                                ->queryOne();
                            return $query['total'];
                        },
                        'format' => ['decimal', 2]
                    ],
                    // [
                    //     'label' => 'Obligated Amount',
                    //     'attribute' => 'obligated_amount',
                    //     'filter'=>false,
                    //     'format'=>['decimal',2]
                    // ],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id, 'onchange' => 'enableDisable(this)', 'style' => 'width:20px;', 'class' => 'checkbox', ''];
                        }
                    ],
                    [
                        'label' => 'Amount Disbursed',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "amount_disbursed[$model->id]",
                                'disabled' => true,
                                'id' => "amount_disbursed_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => '2306 (VAT/ Non-Vat)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "vat_nonvat[$model->id]",
                                'disabled' => true,
                                'id' => "vat_nonvat_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => '2307 (EWT Goods/Services)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "ewt_goods_services[$model->id]",
                                'disabled' => true,
                                'id' => "ewt_goods_services_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => '1601C (Compensation)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "compensation[$model->id]",
                                'disabled' => true,
                                'id' => "compensation_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => 'Other Trust Liabilities',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "other_trust_liabilities[$model->id]",
                                'disabled' => true,
                                'id' => "other_trust_liabilities_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    // [
                    //     'label' => 'Actions',
                    //     'format' => 'raw',
                    //     'value' => function ($model) {
                    //         return ' ' .  MaskMoney::widget([
                    //             'name' => "amount[$model->id]",
                    //             'disabled' => true,
                    //             'id' => "amount_$model->id",
                    //             'options' => [
                    //                 'class' => 'amounts',
                    //             ],
                    //             'pluginOptions' => [
                    //                 'prefix' => '₱ ',
                    //                 'allowNegative' => true
                    //             ],
                    //         ]);
                    //     }
                    // ],

                ],
            ]); ?>
            <button type="submit" class="btn btn-primary" name="submit" id="submit" style="width: 100%;"> ADD</button>
        </form>







    </div>
    <style>
        textarea {
            max-width: 100%;
            width: 100%;
        }

        .grid-view td {
            white-space: normal;
        }

        .select {
            width: 500px;
            height: 2rem;
        }

        #submit {
            margin: 10px;
        }

        input {
            width: 100%;
            font-size: 15px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid black;

        }

        .row {
            margin: 5px;
        }

        .container {
            background-color: white;
            height: auto;
            padding: 10px;
            border-radius: 2px;
        }

        .accounting_entries {
            background-color: white;
            padding: 2rem;
            border: 1px solid black;
            border-radius: 5px;
        }

        .swal-text {
            background-color: #FEFAE3;
            padding: 17px;
            border: 1px solid #F0E1A1;
            display: block;
            margin: 22px;
            text-align: center;
            color: #61534e;
        }
    </style>

    <!-- <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" ></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" type="text/css" rel="stylesheet" /> -->
    <link href="/dti-afms-2/frontend/web/js/select2.min.js" />
    <link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />

    <!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
    <script>
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

        function remove(i) {
            i.closest("tr").remove()
            dv_count--
        }

        function addDvToTable(result) {
            if ($("#transaction").val() == 'Single') {
                $('#particular').val(result[0]['transaction_particular'])
                $('#payee').val(result[0]['transaction_payee_id']).trigger('change')
                // console.log(result[0]['particulars'])
            }
            for (var i = 0; i < result.length; i++) {
                if ($('#transaction').val() == 'Single' && i == 1) {
                    break;
                }
                $('#book_id').val(result[0]['book_id'])
                var row = `<tr>
                            
 
                            <td> <input value='${result[i]['ors_id']}' type='text' name='process_ors_id[]'/></td>
 
                            <td> ${result[i]['serial_number']}</td>
                            <td> 
                            ${result[i]['transaction_particular']}
                            </td>
                            <td> ${result[i]['transaction_payee']}</td>
                            <td> ${result[i]['total']}</td>
                            <td> <input value='${result[i]['amount_disbursed']}' type='text' name='amount_disbursed[]'/></td>
                            <td> <input value='${result[i]['vat_nonvat']}' type='text' name='vat_nonvat[]'/></td>
                            <td> <input value='${result[i]['ewt_goods_services']}' type='text' name='ewt_goods_services[]'/></td>
                            <td> <input value='${result[i]['compensation']}' type='text' name='compensation[]'/></td>
                            <td> <input value='${result[i]['other_trust_liabilities']}' type='text' name='other_trust_liabilities[]'/></td>
                            <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
                        `
                $('#transaction_table').append(row);

                select_id++;
                dv_count++;

            }
            $("#dv_count").val(dv_count)

        }
        var select_id = 0;

        var transaction_type = $("#transaction").val();
        var dv_count = 1;
        $(document).ready(function() {

            // MAG ADD OG DATA NA BUHATAN OG DV
            $('#submit').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=dv-aucs/get-dv',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        var result = JSON.parse(data).results
                        console.log(result)
                        addDvToTable(result)

                    }
                });
                $('.checkbox').prop('checked', false); // Checks it
                $('.amounts').prop('disabled', true);
                $('.amounts').val(null);
            })

        })
    </script>
</div>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php SweetAlertAsset::register($this); ?>
<?php

$script = <<< JS
        var reporting_period = '';
        var transactions=[];
        var nature_of_transaction=[];
        var reference=[];
        var mrd_classification=[];
        var books=[];

    $("#transaction").change(function(){
        var transaction_type=$("#transaction").val()
        $("#transaction_type").val(transaction_type)
        // if (transaction_type =='Single'){
        //     console.log(select_id)
            
        // }
        console.log(transaction_type)
    })
      $(document).ready(function() {


        $.getJSON('/dti-afms-2/frontend/web/index.php?r=chart-of-accounts/get-general-ledger')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.object_code + ' ' + val.title
                        })
                    })
                    accounts = array
       
                })
                // GET ALL MRD CLASSIFICATIOn
        $.getJSON('/dti-afms-2/frontend/web/index.php?r=mrd-classification/get-mrd-classification')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    mrd_classification = array
                    $('#mrd_classification').select2({
                        data:mrd_classification,
                        placeholder:"Select MRD Classification"
                    })
       
                })

                // BOOKS
                
                $.getJSON('/dti-afms-2/frontend/web/index.php?r=books/get-books')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    books = array
                    $('#book').select2({
                        data:books,
                        placeholder:"Select MRD Classification"
                    })
       
                })
                // GET ALL NATURE OF TRANSCTION
     $.getJSON('/dti-afms-2/frontend/web/index.php?r=nature-of-transaction/get-nature-of-transaction')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    nature_of_transaction = array
                    $('#nature_of_transaction').select2({
                        data:nature_of_transaction,
                        placeholder:"Select Nature of Transaction"
                    })
       
                })
                    // GET FINANCING SOURCE CODES
        $.getJSON('/dti-afms-2/frontend/web/index.php?r=transaction/get-transaction')
            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.tracking_number
                    })
                })
                transaction = array
                $('#transaction_id').select2({
                    data: transaction,
                    placeholder: "Select Transaction",

                })

            });
                    // GET PAYEE
                var payee=[];
        $.getJSON('/dti-afms-2/frontend/web/index.php?r=payee/get-payee')
            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.account_name
                    })
                })
                payee = array
                $('#payee').select2({
                    data: payee,
                    placeholder: "Select Payee",
                })

            });
           var transaction = ["Single", "Multiple"]
            $('#transaction').select2({
                data: transaction,
                placeholder: "Select transaction",

            })      
            // INSERT ANG DATA SA DATABASE
        $('#save_data').submit(function(e) {
  

            e.preventDefault();


                $.ajax({
                    url: window.location.pathname + '?r=dv-aucs/insert-dv',
                    method: "POST",
                    data: $('#save_data').serialize(),
                    success: function(data) {
                        var res=JSON.parse(data)
                        console.log(res)
                        if (res.isSuccess) {
                            swal({
                                title: "Success",
                                // text: "You will not be able to undo this action!",
                                type: "success",
                                timer: 3000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                                // window.location.href = window.location.pathname + '?r=process-ors-entries/index'
                            });
                            $('#add_data')[0].reset();
                        }
                        else{

                            swal({
                                title: "Error",
                                text: res.error,
                                type: "error",
                                timer: 6000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            });
                        }
                    }
                });
      
        })


        var update_id= $('#update_id').val()
        if (update_id>0){
            console.log (update_id)
            $.ajax({
                url:window.location.pathname + "?r=dv-aucs/update-dv",
                type:"POST",
                data:{dv_id:update_id},
                success:function(data){

                    var res = JSON.parse(data)
                    console.log(res.result)
                    addDvToTable(res.result)
  
                    $("#particular").val(res.result[0]['particular'])
                    $("#payee").val(res.result[0]['payee_id']).trigger('change');
                    $("#mrd_classification").val(res.result[0]['mrd_classification_id']).trigger("change");
                    $("#nature_of_transaction").val(res.result[0]['nature_of_transaction_id']).trigger("change");
                    $("#reporting_period").val(res.result[0]['reporting_period'])
                    $('#transaction').val(res.result[0]['transaction_type']).trigger('change')
                    
                }
            })
        }
    })

    JS;
$this->registerJs($script);
?>