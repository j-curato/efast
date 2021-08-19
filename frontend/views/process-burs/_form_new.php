<link href="/afms/frontend/web/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="/afms/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/afms/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/afms/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->

<?php

use app\models\FundClusterCode;
use app\models\MfoPapCode;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use GuzzleHttp\Psr7\Query;
use kartik\grid\GridView;
use kartik\money\MaskMoney;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;
?>
<div class="test">
    <!-- <div id="container" class="container">
        <div>
            <?php

            ?>
        </div> -->
    <form id='save_data' method='POST' style="margin-bottom:20px">
        <?php
        $q = 0;
        if (!empty($update_id)) {

            $q = $update_id;
        }


        echo " <input type='text' id='update' name='update' value='$update' style='display:none' >";
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
            <div class="col-sm-3">
                <label for="date">Date</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'date',
                    'id' => 'date',
                    // 'value' => '12/31/2010',
                    // 'options' => ['required' => true],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'mm-dd-yyyy',
                    ]
                ]);
                ?>
            </div>
            <div class="col-sm-3" style="height:60x">
                <label for="book_id">Book</label>
                <select id="book" name="book_id" class="book_id select" style="width: 100%; margin-top:50px">
                    <option></option>
                </select>
            </div>
            <div class="col-sm-3" style="height:60x">
                <label for="transaction_id">Transactions</label>
                <select id="transaction_id" name="transaction_id" class="transaction_id select" style="width: 100%; margin-top:50px">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="card" style="background-color: white;margin:14px">
            <table class="table table-striped" id="transaction_detail">
                <thead>
                    <th>Payee</th>
                    <th>Particular</th>
                    <th>Gross Amount</th>
                </thead>
                <tr>
                    <td>
                        <div id="payee"></div>
                    </td>
                    <td>
                        <div id="transaction_particular"></div>
                    </td>
                    <td>
                        <div id="transaction_amount"></div>
                    </td>
                </tr>
                <tbody>
                </tbody>
            </table>
        </div>

        <table id="transaction_table" class="table table-striped">
            <thead>
                <th>Reporting Period</th>
                <th>MFO/PAP Code</th>
                <th>MFO/PAP Code Name</th>
                <th>Fund Source</th>
                <th>Object Code</th>
                <th>General Ledger</th>
                <th>Amount</th>
            </thead>
            <tbody>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success" style="width: 100%;" id="save" name="save"> SAVE</button>
    </form>
    <form name="add_data" id="add_data">
        <!-- RAOUDS ANG MODEL ANI -->
        <!-- NAA SA CREATE CONTROLLER NAKO GE CHANGE -->

        <?php
        $col = [
            'id',
            'serial_number',
            'mfo_code',
            'mfo_name',
            'fund_source_name',
            'uacs',
            'general_ledger',
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'balance',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],

            [
                'class' => '\kartik\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id, 'onchange' => 'enableDisable(this)', 'style' => 'width:20px;', 'class' => 'checkbox'];
                }
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',

                'value' => function ($model) {
                    return ' ' .  MaskMoney::widget([
                        'name' => "amount[$model->id]",
                        'disabled' => true,
                        'id' => "amount_$model->id",
                        'options' => [
                            'class' => 'amounts',
                        ],
                        'pluginOptions' => [
                            'prefix' => '₱ ',
                            'allowNegative' => true
                        ],
                    ]);
                },
                'contentOptions' => ['style' => 'width:200px; white-space: normal;'],
            ]
        ];
        // $columns = [

        //     // 'id',
        //     [
        //         'label' => 'ID',

        //         'attribute' => 'recordAllotmentEntries.id'

        //     ],
        //     [
        //         'label' => 'Serial Number',

        //         'attribute' => 'recordAllotmentEntries.recordAllotment.serial_number'

        //     ],

        //     [
        //         'label' => 'MFO/PAP Code',
        //         'attribute' => 'recordAllotmentEntries.recordAllotment.mfoPapCode.code',
        //         'filter' => Select2::widget([
        //             'model' => $searchModel,
        //             'attribute' => 'is_parent',
        //             'data' =>  ArrayHelper::map(MfoPapCode::find()->asArray()->all(), 'id', 'code'),
        //             'options' => ['placeholder' => 'Select '],
        //             'pluginOptions' => [
        //                 'allowClear' => true
        //             ],
        //         ])

        //     ],
        //     [
        //         'label' => 'MFO/PAP Code Name',
        //         'attribute' => 'recordAllotmentEntries.recordAllotment.mfoPapCode.name'
        //     ],
        //     [
        //         'label' => 'MFO/PAP Code id',
        //         'attribute' => 'recordAllotmentEntries.recordAllotment.mfo_pap_code_id'
        //     ],

        //     [
        //         'label' => 'Fund Source Code',
        //         'attribute' => 'recordAllotmentEntries.recordAllotment.fundSource.name'
        //     ],
        //     [
        //         'label' => 'Object Code',
        //         'value' => function ($model) {
        //             if ($model->process_ors_id != null) {
        //                 return $model->raoudEntries->chartOfAccount->uacs;
        //             } else {
        //                 return $model->recordAllotmentEntries->chartOfAccount->uacs;
        //             }
        //         }
        //     ],
        //     [
        //         'label' => 'General Ledger',
        //         // 'attribute' => 'recordAllotmentEntries.chartOfAccount.general_ledger'
        //         'value' => function ($model) {
        //             if ($model->process_ors_id != null) {
        //                 return $model->raoudEntries->chartOfAccount->general_ledger;
        //             } else {
        //                 return $model->recordAllotmentEntries->chartOfAccount->general_ledger;
        //             }
        //         }
        //     ],
        //     [
        //         'label' => 'Amount',
        //         'attribute' => 'recordAllotmentEntries.amount',
        //         'format' => ['decimal', 2],
        //         'hAlign' => 'right',
        //         'vAlign' => 'middle',
        //     ],

        //     [
        //         'label' => 'Balance',
        //         'value' => function ($model) {
        //             // $query = (new \yii\db\Query())
        //             //     ->select([

        //             //         'entry.obligation_total', 'record_allotment_entries.amount', 'entry.remain'
        //             //     ])
        //             //     ->from('raouds')
        //             //     ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
        //             //     ->join("LEFT JOIN", "(SELECT SUM(raouds.obligated_amount) as obligation_total,
        //             //     raouds.record_allotment_entries_id,record_allotment_entries.amount -SUM(raouds.obligated_amount) as remain
        //             //      From raouds,record_allotment_entries
        //             //      WHERE 
        //             //     raouds.record_allotment_entries_id = record_allotment_entries.id
        //             //     AND raouds.process_ors_id IS NOT NULL
        //             //     GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
        //             //     ->where("raouds.id = :id", ['id' => $model->id])->one();
        //             $query = Yii::$app->db->createCommand("SELECT SUM(raouds.obligated_amount) as obligated_amount,
        //                 SUM(raouds.burs_amount) as burs_amount,
        //                 raouds.record_allotment_entries_id,record_allotment_entries.amount -SUM(raouds.obligated_amount) as remain,
        //                 record_allotment_entries.amount as record_allotment_amount
        //                 From raouds,record_allotment_entries,raoud_entries
        //                 WHERE raouds.record_allotment_entries_id = record_allotment_entries.id
        //                 AND raouds.id = raoud_entries.raoud_id
        //                 AND raouds.record_allotment_entries_id=$model->record_allotment_entries_id
        //                 ")->queryOne();
        //             $burs_ors_amount = $query['obligated_amount'] + $query['burs_amount'];
        //             $remain = $query['record_allotment_amount'] - $burs_ors_amount;
        //             return $remain;
        //         },
        //         'format' => ['decimal', 2],
        //         'hAlign' => 'right',
        //         'vAlign' => 'middle',

        //     ],

        //     [
        //         'class' => '\kartik\grid\CheckboxColumn',
        //         'checkboxOptions' => function ($model, $key, $index, $column) {
        //             return ['value' => $model->id, 'onchange' => 'enableDisable(this)', 'style' => 'width:20px;', 'class' => 'checkbox'];
        //         }
        //     ],
        //     [
        //         'label' => 'Actions',
        //         'format' => 'raw',

        //         'value' => function ($model) {
        //             return ' ' .  MaskMoney::widget([
        //                 'name' => "amount[$model->id]",
        //                 'disabled' => true,
        //                 'id' => "amount_$model->id",
        //                 'options' => [
        //                     'class' => 'amounts',
        //                 ],
        //                 'pluginOptions' => [
        //                     'prefix' => '₱ ',
        //                     'allowNegative' => true
        //                 ],
        //             ]);
        //         },
        //         'contentOptions' => ['style' => 'width:200px; white-space: normal;'],
        //     ]

        // ];

        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'floatHeaderOptions' => [
                'top' => 50,
                'position' => 'absolute',
            ],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
            ],
            'export' => false,
            'pjax' => true,

            'columns' => $col,
        ]); ?>
        <button type="submit" class="btn btn-primary" name="submit" id='add' style="width: 100%;"> ADD</button>
    </form>







    <!-- </div> -->
    <style>
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

    <script src="/afms/frontend/web/js/jquery.min.js" type="text/javascript"></script>
    <link href="/afms/frontend/web/js/select2.min.js" />
    <link href="/afms/frontend/web/css/select2.min.css" rel="stylesheet" />
    <link href="/afms/frontend/web/js/maskMoney.js" />
    <script>
        var update_id = null;
        var account_name = undefined;
        var select_id = 0;
        var accounts = []
        var chart_of_accounts = undefined;


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
            // console.log(index)
            // button = document.querySelector('.amount_1').disabled=false;
            // console.log(  $('.amount_1').disaled)

        }

        function remove(i) {
            i.closest("tr").remove()
        }
        var i = 1;

        function copy(q) {
            var qwer = $(q).closest('tr')
            var raoud_id = qwer.find('.raoud_id').val();
            var mfo_pap_code = qwer.find('.mfo_pap_code').text();
            var mfo_pap_name = qwer.find('.mfo_pap_name').text();
            var fund_source_name = qwer.find('.fund_source_name').text();
            var object_code = qwer.find('.object_code').text();
            var chart_id = qwer.find('.chart-of-account').val();
            // var obj = JSON.parse('{
            //     "raoud_id":${raoud_id},
            //     "mfo_pap_code_code": ${mfo_pap_code},
            //     "mfo_pap_name": ${mfo_pap_name},
            //     "fund_source_name": ${fund_source_name},
            //     "object_code": ${object_code},
            //     "chart_id": ${chart_id},
            //     }');
            // var arr = {
            //     'raoud_id': raoud_id,
            //     'mfo_pap_code': mfo_pap_code,
            //     'mfo_pap_name': mfo_pap_name,
            //     'fund_source_name': fund_source_name,
            //     'object_code': object_code,
            // };
            var obj = JSON.parse(`{
                "raoud_id":${raoud_id},
                "mfo_pap_code_code":"${mfo_pap_code}",
                "mfo_pap_name": "${mfo_pap_name}",
                "fund_source_name": "${fund_source_name}",
                "object_code": "${object_code}",
                "chart_of_account_id": ${chart_id},
                "obligation_amount":0
        }`);

            console.log([obj])
            var qwe = '';
            if ($('#update').val() != 'create') {
                qwe = 'copy';
            }

            addData([obj], qwe)

            // var r = $(q).closest('tr').clone().find("input").each(function() {
            //     $(this).attr({
            //         'id': function(_, id) {
            //             return id + select_id
            //         },
            //         'name': function(_, name) {
            //             return name
            //         },
            //         'value': '',
            //         'disabled': function(_, id) {
            //             return false
            //         },
            //     });
            // }).end().appendTo("#transaction_table");
            // r.closest('tr').find("button").each(function() {
            //     $(this).attr({
            //         'id': function(_, id) {
            //             return id + select_id
            //         },
            //         'name': function(_, name) {
            //             return name
            //         },
            //         'value': '',
            //         'disabled': function(_, id) {
            //             return false
            //         },
            //     });
            // }).end()

            // select_id++;
        }

        function addData(result, isUpdate) {
            for (var i = 0; i < result.length; i++) {
                object_code = result[i]['object_code']
                chart_id = result[i]['chart_of_account_id']

                if (result[i]['object_code'] == "5010000000" || result[i]['object_code'] == "5020000000") {
                    object_code = ''
                    chart_id = ''
                }
                var row = `<tr>
                            <td style="display:none"> <input value='${result[i]['raoud_id']}' type='text' name='raoud_id[]' id='raoud_${select_id}' class='raoud_id' /></td>
                            <td > <input  type='month' id='date_${select_id}' name='new_reporting_period[]' required /></td>
                            <td> <div class='mfo_pap_code'>${result[i]['mfo_pap_code_code']}</div></td>
                            <td><div class='mfo_pap_name'> ${result[i]['mfo_pap_name']}</div></td>
                            <td><div class='fund_source_name'> ${result[i]['fund_source_name']}</div></td>
                            <td><div class='object_code'> ${object_code}</div></td>
                            <td> 
                                <div>
                                    <select id="chart-${select_id}" required name="chart_of_account_id[]" class="chart-of-account" style="width: 100%" required>
                                        <option></option>
                                    </select>
                                </div>
                            </td>
                            <td> <input value='${result[i]['obligation_amount']}'  type='text' name='obligation_amount[]' id='amount_${select_id}' class='amount'/></td>
                            <td><a id='copy_${select_id}' class='btn btn-success ' type='button' onclick='copy(this)'><i class="fa fa-copy "></i></a></td>
                            <td><button id='remove_${select_id}' class='btn btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td>
                            </tr>`
                $('#transaction_table').append(row);
                $(`#amount_${select_id}`).maskMoney({
                    allowNegative: true
                });
                var filtered_chart_of_accounts = chart_of_accounts.filter(function(chart) {
                    // console.log(object_code)

                    if (result[i]['object_code'] == "5010000000") {
                        // console.log(object_code)
                        var x = chart.object_code.match(/^501.*$/);

                        if (x != "5010000000") {

                            return x;
                        }

                    } else if (result[i]['object_code'] == "5020000000") {

                        var y = chart.object_code.match(/^502.*$/);
                        if (y != "5020000000") {

                            return y;
                        }

                    } else if (result[i]['object_code'] == "5060000000") {

                        var q = chart.object_code.match(/^502.*$/);
                        if (q != "5060000000") {

                            return q;
                        }

                    } else {
                        return chart
                    }

                });
                // delete filtered_chart_of_accounts[0].id
                // delete filtered_chart_of_accounts[0].object_code
                // delete filtered_chart_of_accounts[0].title
                // console.log(delete filtered_chart_of_accounts[0])
                var q = []
                $.each(filtered_chart_of_accounts, function(key, val) {
                    q.push({
                        id: val.id,
                        text: val.object_code + ' ' + val.title
                    })
                })
                // console.log(firstGradeStudents)
                $(`#chart-${select_id}`).select2({
                    data: q,
                    placeholder: "Select Chart of Account",

                }).val(`${chart_id}`).trigger('change');
                if (result[i]['object_code'] == "5060000000" ||
                    result[i]['object_code'] == "5010000000" ||
                    result[i]['object_code'] == "5020000000"

                ) {

                } else {
                    $(`#chart-${select_id} option:not(:selected)`).attr('disabled', true);
                }



                if (isUpdate == 'copy') {
                    $(`#chart-${select_id} option:not(:selected)`).attr("disabled", true)
                }
                if (isUpdate == true) {
                    $(`#chart-${select_id}`).prop('disabled', true);
                    $(`#amount_${select_id}`).prop('disabled', true);
                    $(`#remove_${select_id}`).prop('disabled', true);
                    // $(`#date_${select_id}`).val('12/12/2021');
                    var dateControl = document.querySelector(`#date_${select_id}`);
                    dateControl.value = result[i]['reporting_period'];
                    $(`#date_${select_id}`).prop('disabled', true);
                    $(`#raoud_${select_id}`).prop('disabled', true);

                }
                if ($('#update').val() == 'create') {
                    $(`#date_${select_id}`).prop('disabled', true);

                    if (result[i]['object_code'] == "5010000000" || result[i]['object_code'] == "5020000000") {} else {
                        // $(`#chart-${select_id}`).prop('disabled', true);

                    }
                }
                select_id++;

            }
            // console.log(result)




        }

        $(document).ready(function() {
            // GET CHART OF ACCOUNTS
            $.getJSON('/afms/frontend/web/index.php?r=chart-of-accounts/get-general-ledger&id='+$('#update_id').val())
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.object_code + ' ' + val.title
                        })
                    })
                    accounts = array
                    // var y=JSON.parse(accounts)
                    chart_of_accounts = data

                })
            // GET TRANSACTIONs

            $.getJSON('/afms/frontend/web/index.php?r=transaction/get-all-transaction')
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



            // GET TRANSACTIONs

            // GET ALL BOOKS
            const url = window.location.pathname
            $.getJSON(url + '?r=books/get-books')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    book = array
                    $('#book').select2({
                        data: book,
                        placeholder: "Select Book",

                    })

                });





        })
        $('#add_data').submit(function(e) {

            console.log($('#add_data').serialize()),
                e.preventDefault();
            $.ajax({
                url: window.location.pathname + '?r=process-ors-entries/add-data',
                method: "POST",
                data: $('#add_data').serialize(),
                success: function(data) {
                    var result = JSON.parse(data).results
                    console.log(result)
                    var object_code = ''
                    var chart_id = ''
                    addData(result, false)
                }
            });
            $('.checkbox').prop('checked', false); // Checks it
            $('.amounts').prop('disabled', true);
            $('.amounts').val(null);
        })
    </script>
</div>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<?php SweetAlertAsset::register($this); ?>
<?php

$script = <<< JS
        var reporting_period = '';
        var transactions=[];
        var book=[];
       
    $("#transaction_id").change(function(){
        // console.log($("#transaction_id").val())
        var transaction_id = $("#transaction_id").val()
        
        $.ajax({
            url:window.location.pathname + "?r=transaction/get-transaction",
            type:"POST",
            data:{transaction_id:transaction_id},
            success:function(data){
                var res = JSON.parse(data)
                account_name = res.result['account_name']
                var particular = res.result['particular']
                var amount = res.result['gross_amount']
                // var x = document.getElementById("transaction_detail");
                //  x.deleteRow(0);
                $("#payee").text(res.result['account_name'])
                $("#transaction_particular").text(res.result['particular'])
                $("#transaction_amount").text(res.result['gross_amount'])
                
                console.log(account_name)
 
                var row = "<tr><td>"+account_name+"</td></tr>"
                // $('#transaction_detail').tbody(row);
                // var x = document.getElementById("transaction_detail").getElementsByTagName("tbody")[0]row;


            }

        })
    })
    $(document).ready(function() {
        console.log("qwerty".substring(0,3))
       

            // SAVE DATA TO DATABASE
        $('#save_data').submit(function(e) {
  

            e.preventDefault();

            // $('.amount').maskMoney('unmasked');
                $.ajax({
                    url: window.location.pathname + '?r=process-burs/insert-process-burs',
                    method: "POST",
                    data: $('#save_data').serialize(),
                    success: function(data) {
                        // console.log(data)
                        var res=JSON.parse(data)
   

                        if (res.isSuccess) {
                            swal({
                                title: "Success",
                                // text: "You will not be able to undo this action!",
                                type: "success",
                                timer: 30000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                                window.location.href = window.location.pathname + '?r=process-burs/view&id='+res.id
                            });
                            $('#add_data')[0].reset();
                        }
                        else{
                            var length = Object.keys(res.error).length
                            var keys = Object.keys(res.error)
                            var text=''
                            console.log(keys[0])
                            for(var i = 0;i<length;i++){
                                var x=keys[i]
                                text += res.error[x] 
                            }
                            console.log(text)
                            swal({
                                title: "Error",
                                text: text,
                                type: "error",
                                timer: 3000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                            });
                        }
                    }
                });
      
        })
        update_id = $('#update_id').val()
        console.log(update_id)
        //   update_id.change(function(){
        //       console.log(update_id.val())
        //   })
          if (update_id!=0 ){
            //   $('#add').prop('disabled',true)
            $.ajax({
                type:"POST",
                url:window.location.pathname + "?r=process-burs/update-burs",
                data:{update_id:update_id},
                success:function(data){
                    var res = JSON.parse(data)
                    // console.log(res)
                    console.log(res.result)
                    $("#reporting_period").val(res.result[0]['reporting_period']).trigger('change')
                    $("#date").val(res.result[0]['date']).trigger('change')
                    $("#book").val(res.result[0]['book_id']).trigger('change')
                    $("#transaction_id").val(res.result[0]['transaction_id']).trigger('change')
                    addData(res.result,true)
                }
            })
        }


    })
JS;
$this->registerJs($script);
?>