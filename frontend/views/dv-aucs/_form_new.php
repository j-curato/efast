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

        <form name="add_data" id="add_data">


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
                'columns' => [

                    'id',
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
                            $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as obligated_amount,
                                raouds.record_allotment_entries_id,record_allotment_entries.amount -SUM(raouds.obligated_amount) as remain
                                From raouds,record_allotment_entries,raoud_entries
                                WHERE raouds.record_allotment_entries_id = record_allotment_entries.id
                                AND raouds.id = raoud_entries.raoud_id
                                AND raouds.process_ors_id IS NOT NULL
                                AND raouds.record_allotment_entries_id=$model->record_allotment_entries_id
                                ")->queryOne();
                            return $query['remain'];
                        }
                    ],
                    [
                        'label' => 'Obligated Amount',
                        'attribute' => 'obligated_amount'
                    ],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id, 'onchange' => 'enableDisable(this)', 'style' => 'width:20px;', 'class' => 'checkbox'];
                        }
                    ],
                    [
                        'label' => '1% EWT',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "1_percent_ewt[$model->id]",
                                'disabled' => true,
                                'id' => "1_percent_ewt_$model->id",
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
                        'label' => '2% EWT',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "2_percent_ewt[$model->id]",
                                'disabled' => true,
                                'id' => "2_percent_ewt_$model->id",
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
                        'label' => '3% FT',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "3_percent_ft[$model->id]",
                                'disabled' => true,
                                'id' => "3_percent_ft_$model->id",
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
                        'label' => '5% FT',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "5_percent_ft[$model->id]",
                                'disabled' => true,
                                'id' => "5_percent_ft_$model->id",
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
                        'label' => '5% EWT',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "5_percent_ewt[$model->id]",
                                'disabled' => true,
                                'id' => "5_percent_ewt_$model->id",
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
                    <label for="reference">Transactions</label>
                    <select id="reference" name="reference" class="reference select" style="width: 100%; margin-top:50px" >
                        <option></option>
                    </select>
                </div>

                <div class="col-sm-3" style="height:60x">
                    <label for="nature_of_transaction">Transactions</label>
                    <select id="nature_of_transaction" name="nature_of_transaction" class="nature_of_transaction select" style="width: 100%; margin-top:50px" >
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="mrd_classification">Transactions</label>
                    <select id="mrd_classification" name="mrd_classification" class="mrd_classification select" style="width: 100%; margin-top:50px" >
                        <option></option>
                    </select>
                </div>
            </div>

            <table id="transaction_table" class="table table-striped">
                <thead>
                    <th>Raoud ID</th>
                    <th>Object Code</th>
                    <th>General Ledger</th>
                    <th>Obligated Amount</th>
                    <th>1% EWT</th>
                    <th>2% EWT</th>
                    <th>3% FT</th>
                    <th>5% FT</th>
                    <th>5% EWT</th>
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
            $(`#1_percent_ewt_${index}-disp`).prop('disabled', isDisable);
            $(`#1_percent_ewt_${index}`).prop('disabled', isDisable);
            $(`#2_percent_ewt_${index}-disp`).prop('disabled', isDisable);
            $(`#2_percent_ewt_${index}`).prop('disabled', isDisable);
            $(`#3_percent_ft_${index}-disp`).prop('disabled', isDisable);
            $(`#3_percent_ft_${index}`).prop('disabled', isDisable);
            $(`#5_percent_ft_${index}-disp`).prop('disabled', isDisable);
            $(`#5_percent_ft_${index}`).prop('disabled', isDisable);
            $(`#5_percent_ewt_${index}-disp`).prop('disabled', isDisable);
            $(`#5_percent_ewt_${index}`).prop('disabled', isDisable);
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
                    url: window.location.pathname + '?r=dv-aucs/sample',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        var result = JSON.parse(data).results
                        console.log(result)

                        for (var i = 0; i < result.length; i++) {

                            var row = `<tr>
                            
                            <td> <input value='${result[i]['raoud_id']}' type='text' name='raoud_id'/></td>
 
                            <td> ${result[i]['object_code']}</td>
                            <td> 
                            ${result[i]['general_ledger']}
                            </td>
                            <td> ${result[i]['obligated_amount']}</td>
                            <td> <input value='${result[i]['1_percent_ewt']}' type='text' name='1_percent_ewt[]'/></td>
                            <td> <input value='${result[i]['2_percent_ewt']}' type='text' name='2_percent_ewt[]'/></td>
                            <td> <input value='${result[i]['3_percent_ft']}' type='text' name='3_percent_ft[]'/></td>
                            <td> <input value='${result[i]['5_percent_ft']}' type='text' name='5_percent_ft[]'/></td>
                            <td> <input value='${result[i]['5_percent_ewt']}' type='text' name='5_percent_ewt[]'/></td>
                        `
                            $('#transaction_table').append(row);

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
        var nature_of_transaction=[];
        var reference=[];
        var mrd_classification=[];
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
            reference = ["ADADJ", "CDJ", "CKDJ", "CRJ", "GJ"]
            $('#reference').select2({
                data: reference,
                placeholder: "Select Reference",

            })      
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
                    }
                });
      
        })
    })
    JS;
$this->registerJs($script);
?>