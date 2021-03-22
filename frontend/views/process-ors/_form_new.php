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
            <?php
            $q = 0;
            if (!empty($model)) {

                $q = $model;
            }
            echo " <input type='text' id='update_id' name='update_id'  style='display:none'>";
            ?>



            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => 'List of Areas',
                ],
                'floatHeaderOptions' => [
                    'top' => 50,
                    'position' => 'absolute',
                ],
                'columns' => [

                    'id',
                    [
                        'label' => 'MFO/PAP Code',
                        'attribute' => 'recordAllotment.mfoPapCode.code',
                        // 'filter' => Html::activeDropDownList(
                        //     $searchModel,
                        //     'recordAllotment.fund_cluster_code_id',
                        //     ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                        //     ['class' => 'form-control', 'prompt' => 'Major Accounts']
                        // )

                    ],
                    [
                        'label' => 'MFO/PAP Code Name',
                        'attribute' => 'recordAllotment.mfoPapCode.name'
                    ],
                    [
                        'label' => 'Fund Source Code',
                        'attribute' => 'recordAllotment.fundSource.name'
                    ],
                    [
                        'label' => 'Object Code',
                        'attribute' => 'raoudEntries.chartOfAccount.uacs'
                    ],
                    [
                        'label' => 'General Ledger',
                        'attribute' => 'processOrs.id'
                    ],

                    [
                        'label' => 'Balance',
                        'value' => function ($model) {
                            $query = (new \yii\db\Query())
                                ->select([
                                   
                                    'entry.obligation_total', 'record_allotment_entries.amount', '(record_allotment_entries.amount - entry.obligation_total) AS remain'
                                ])
                                ->from('raouds')
                                ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as obligation_total,
                                        raouds.id, raouds.record_allotment_id,raouds.process_ors_id,
                                        raouds.record_allotment_entries_id
                                        FROM raouds,raoud_entries,process_ors
                                        WHERE raouds.process_ors_id= process_ors.id
                                        AND raouds.id = raoud_entries.raoud_id
                                        AND raouds.process_ors_id IS NOT NULL 
                                        GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
                                // ->join("LEFT JOIN","","raouds.process_ors_id=process_ors.id")
                                ->where("raouds.id = :id", ['id' => $model->id])->one();

                            return $query['remain'];
                        }
                    ],



                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id, 'onchange' => 'enableDisable(this)', 'style' => 'width:20px;', 'class' => 'checkbox'];
                        }
                    ],
                    // [
                    //     'label' => 'Actions',
                    //     'format' => 'raw',
                    //     'value' => function ($model) {
                    //         return ' ' . Html::input('text', "sample[$model->id]", '', ['class' => 'amounts']);
                    //     }
                    // ],
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
                                    'prefix' => 'PHP ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ]

                ],
            ]); ?>
            <input type="submit" name="submit">
        </form>
        <form id='save_data' method='POST'>
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

            </div>

            <table id="transaction_table">
                <thead>
                    <th>a</th>
                    <th>w</th>
                    <th>e</th>
                    <th>r</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <button type="submit" id="save" name="save"> save</button>
        </form>






    </div>
    <style>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
        $(document).ready(function() {


            $('#add_data').submit(function(e) {


                e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=process-ors/sample',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        var result = JSON.parse(data).results
                        console.log(result)

                        for (var i = 0; i < result.length; i++) {

                            var row = `<tr>
                            
                            <td> <input value='${result[i]['raoud_id']}' type='text' name='raoud_id[]'/></td>
                            <td> <input value='${result[i]['chart_of_account_id']}' type='text' name='chart_of_account_id[]'/></td>
                            <td> <input value='${result[i]['obligation_amount']}' type='text' name='final_amount[]'/></td>
                            <td>r</td>
                            <td><button id='remove' onclick='remove(this)'>remove</button></td></tr>`
                            $('#transaction_table').append(row);
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

<?php

$script = <<< JS
        var reporting_period = '';
      $(document).ready(function() {
          $("#reporting_period").change(function(){
              reporting_periiod =$(this).val()
              console.log($())
          })
        $('#save_data').submit(function(e) {
  

            e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=process-ors/insert-process-ors',
                    method: "POST",
                    data: $('#save_data').serialize(),
                    success: function(data) {
                        console.log(data)
                    }
                });
            })
    })
    JS;
$this->registerJs($script);
?>