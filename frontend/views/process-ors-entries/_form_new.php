<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->

<?php

use app\models\FundClusterCode;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use GuzzleHttp\Psr7\Query;
use kartik\grid\GridView;
use kartik\money\MaskMoney;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<div class="test">




    <div id="container" class="container">

        <form name="add_data" id="add_data">


            <!-- RAOUDS ANG MODEL ANI -->
            <!-- NAA SA CREATE CONTROLLER NAKO GE CHANGE -->

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,

                'floatHeaderOptions' => [
                    'top' => 50,
                    'position' => 'absolute',
                ],
                'columns' => [

                    'id',
                    [
                        'label' => 'MFO/PAP Code',
                        'attribute' => 'recordAllotmentEntries.recordAllotment.mfoPapCode.code',
                        // 'filter' => Html::activeDropDownList(
                        //     $searchModel,
                        //     'recordAllotment.fund_cluster_code_id',
                        //     ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                        //     ['class' => 'form-control', 'prompt' => 'Major Accounts']
                        // )

                    ],
                    [
                        'label' => 'MFO/PAP Code Name',
                        'attribute' => 'recordAllotmentEntries.recordAllotment.mfoPapCode.name'
                    ],

                    [
                        'label' => 'Fund Source Code',
                        'attribute' => 'recordAllotmentEntries.recordAllotment.fundSource.name'
                    ],
                    [
                        'label' => 'Object Code',
                        'value' => function ($model) {
                            if ($model->process_ors_id != null) {
                                return $model->raoudEntries->chartOfAccount->uacs;
                            } else {
                                return $model->recordAllotmentEntries->chartOfAccount->uacs;
                            }
                        }
                    ],
                    [
                        'label' => 'General Ledger',
                        // 'attribute' => 'recordAllotmentEntries.chartOfAccount.general_ledger'
                        'value' => function ($model) {
                            if ($model->process_ors_id != null) {
                                return $model->raoudEntries->chartOfAccount->general_ledger;
                            } else {
                                return $model->recordAllotmentEntries->chartOfAccount->general_ledger;
                            }
                        }
                    ],
                    [
                        'label' => 'Amount',
                        'attribute' => 'recordAllotmentEntries.amount'
                    ],

                    [
                        'label' => 'Balance',
                        'value' => function ($model) {
                            // $query = (new \yii\db\Query())
                            //     ->select([

                            //         'entry.obligation_total', 'record_allotment_entries.amount', 'entry.remain'
                            //     ])
                            //     ->from('raouds')
                            //     ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                            //     ->join("LEFT JOIN", "(SELECT SUM(raouds.obligated_amount) as obligation_total,
                            //     raouds.record_allotment_entries_id,record_allotment_entries.amount -SUM(raouds.obligated_amount) as remain
                            //      From raouds,record_allotment_entries
                            //      WHERE 
                            //     raouds.record_allotment_entries_id = record_allotment_entries.id
                            //     AND raouds.process_ors_id IS NOT NULL
                            //     GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
                            //     ->where("raouds.id = :id", ['id' => $model->id])->one();
                            $query = Yii::$app->db->createCommand("SELECT SUM(raouds.obligated_amount) as obligated_amount,
                            SUM(raouds.burs_amount) as burs_amount,
                            raouds.record_allotment_entries_id,record_allotment_entries.amount -SUM(raouds.obligated_amount) as remain,
                            record_allotment_entries.amount as record_allotment_amount
                            From raouds,record_allotment_entries,raoud_entries
                            WHERE raouds.record_allotment_entries_id = record_allotment_entries.id
                            AND raouds.id = raoud_entries.raoud_id
                            AND raouds.record_allotment_entries_id=$model->record_allotment_entries_id
                            ")->queryOne();
                            $burs_ors_amount = $query['obligated_amount'] + $query['burs_amount'];
                            $remain = $query['record_allotment_amount'] - $burs_ors_amount;
                            return $remain;
                        }
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
                        }
                    ]

                ],
            ]); ?>
            <button type="submit" class="btn btn-primary" name="submit" style="width: 100%;"> ADD</button>
        </form>
        <form id='save_data' method='POST'>
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
                    <label for="transaction_id">Transactions</label>
                    <select id="transaction_id" name="transaction_id" class="transaction_id select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
            </div>
            <table id="transaction_table" class="table table-striped">
                <thead>
                    <!-- <th>Raoud ID</th> -->
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






    </div>
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
            // console.log(index)
            // button = document.querySelector('.amount_1').disabled=false;
            // console.log(  $('.amount_1').disaled)

        }

        function remove(i) {
            i.closest("tr").remove()
        }
        var select_id = 0;
        $(document).ready(function() {


            $('#add_data').submit(function(e) {


                e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=process-ors-entries/sample',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        var result = JSON.parse(data).results
                        console.log(result)
                        var object_code = ''
                        var chart_id = ''
                        for (var i = 0; i < result.length; i++) {
                            object_code = result[i]['object_code']
                            chart_id = result[i]['chart_of_account_id']

                            if (result[i]['object_code'] == "5010000000") {
                                object_code = ''
                                chart_id = ''
                            }
                            var row = `<tr>
                            
                            <td style="display:none"> <input value='${result[i]['raoud_id']}' type='text' name='raoud_id[]' /></td>
                            <td> ${result[i]['mfo_pap_code_code']}</td>
                            <td> ${result[i]['mfo_pap_name']}</td>
                            <td> ${result[i]['fund_source_name']}</td>
                            <td> ${object_code}</td>
                            <td> 
                                <div>
                                    <select id="chart-${select_id}" required name="chart_of_account_id[]" class="chart-of-account" style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>
                            </td>
                            <td> <input value='${result[i]['obligation_amount']}' type='text' name='obligation_amount[]'/></td>
                            <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>`
                            $('#transaction_table').append(row);
                            $(`#chart-${select_id}`).select2({
                                data: accounts,
                                placeholder: "Select Chart of Account",

                            }).val(`${chart_id}`).trigger('change');
                            select_id++;
                        }
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
        $('#save_data').submit(function(e) {
  

            e.preventDefault();


                $.ajax({
                    url: window.location.pathname + '?r=process-ors-entries/insert-process-ors',
                    method: "POST",
                    data: $('#save_data').serialize(),
                    success: function(data) {
                        var res=JSON.parse(data)
   

                        if (res.isSuccess) {
                            swal({
                                title: "Success",
                                // text: "You will not be able to undo this action!",
                                type: "success",
                                timer: 3000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                                window.location.href = window.location.pathname + '?r=process-ors-entries/index'
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
    })
    JS;
$this->registerJs($script);
?>