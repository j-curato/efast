<?php

use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use app\models\ForTransmittalSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transmittal-form">
    <form id="add_data">
        <?php
        $searchModel = new ForTransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumns = [

            [
                'class' => '\kartik\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id,  'style' => 'width:20px;', 'class' => 'checkbox', ''];
                }
            ],
            [
                'label' => 'DV Number',
                'attribute' => 'dv_number',
            ],
            [
                'label' => 'Check/ada Number',
                'attribute' => 'check_or_ada_no'
                // 'value' => function ($model) {
                //     $q = $model->mode_of_payment  . '-' . $model->check_or_ada_no;
                //     return $q;
                // }
            ],
            [
                'label' => 'Payee',
                'attribute' => 'account_name'

            ],
            [
                'label' => 'Particular',
                'attribute' => 'particular'

            ],
            [
                'label' => "Amount Disbursed",
                'format' => ['decimal', 2],
                'attribute' => 'total_dv'
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
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                // 'heading' => 'List of Areas',
            ],
            'pjax' => true,
            'export' => false,
            'floatHeaderOptions' => [
                'top' => 50,
                'position' => 'absolute',

            ],

            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            'columns' => $gridColumns,
        ]); ?>

        <button type="button" class="btn btn-primary" name="add" id="add" style="width: 100%;"> ADD</button>
    </form>
    <form id="save_data">
        <div class="row">

            <div class="col-sm-3" style="margin:12px">

                <label for="date">Date</label>
                <?php
                $val = !empty($model) ? $model->id : '';
                echo "<input value='$val' name='update_id' style='display:none'/>";
                echo DatePicker::widget([
                    'name' => 'date',
                    'id' => 'date',
                    'value' => !empty($model->date) ? $model->date : date('Y-m-d'),
                    'options' => ['required' => true],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',

                    ]
                ]);
                ?>
            </div>
        </div>
        <table class="table table-striped" id="transaction_table" style="background-color: white;">
            <thead>
                <th>DV Number</th>
                <th>Check/ADA Number</th>
                <th>Payee</th>
                <th>Particular</th>
                <th>Amount</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <?php
                if (!empty($model)) {
                    echo "<tr>";
                    foreach ($model->transmittalEntries as $val) {
                        $query = (new \yii\db\Query())
                            ->select("SUM(dv_aucs_entries.amount_disbursed) as total_disbursed")
                            ->from("dv_aucs")
                            ->join("LEFT JOIN", 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
                            ->where("dv_aucs.id = :id", [
                                'id' => $val->cashDisbursement->dv_aucs_id
                            ])
                            ->one();
                        echo "<td style='display:none'><input  value='$val->cash_disbursement_id' type='text' name='cash_disbursement_id[]'/></td>
                        <td>{$val->cashDisbursement->dvAucs->dv_number}</td>
                        <td>{$val->cashDisbursement->check_or_ada_no}</td>
                        <td>{$val->cashDisbursement->dvAucs->payee->account_name}</td>
                        <td>{$val->cashDisbursement->dvAucs->particular}</td>
                        <td>{$query['total_disbursed']}</td>
                        <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class='fa fa-times'></i></button></td></tr>
                    ";
                    }
                    echo  "</tr>";
                }
                ?>

                </tr>
                <tr>
                    <td colspan="7">
                        <button type="submit" class="btn btn-success" style="width: 100%;" id="save" name="save"> SAVE</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>


</div>
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
    function remove(i) {
        i.closest("tr").remove()
    }
    var payee = undefined;
    var particular = undefined;
    var dv_number = undefined;
    var check_number = undefined;
    var amount = undefined;

    function addDvToTable(result) {
        if ($("#transaction").val() == 'Single') {
            $('#particular').val(result[0]['transaction_particular'])
            $('#payee').val(result[0]['transaction_payee_id']).trigger('change')
            // console.log(result[0]['particulars'])
        }
        for (var i = 0; i < result.length; i++) {
            payee = result[i]['payee']
            particular = result[i]['particular']
            dv_number = result[i]['dv_number']
            check_number = result[i]['check_or_ada_no']
            amount = result[i]['total_disbursed']
            if ($('#transaction').val() == 'Single' && i == 1) {
                break;
            }
            $('#book_id').val(result[0]['book_id'])

            var row = `<tr>
                        <td style='display:none'><input  value='${result[i]['id']}' type='text' name='cash_disbursement_id[]'/></td>
                        <td>${dv_number}</td>
                        <td>${check_number}</td>
                        <td>${payee}</td>
                        <td>${particular}</td>
                        <td>${amount}</td>
                        <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="fa fa-times"></i></button></td></tr>
                    </tr>
                        `
            $('#transaction_table tbody').append(row);


        }



    }
</script>


<?php
SweetAlertAsset::register($this);
$script = <<< JS
        function enableDisable(checkbox) {
            var isDisable = true
            if (checkbox.checked) {
                isDisable = false
            }
            enableInput(isDisable, checkbox.value)

        }

    // MAG ADD OG CASH DISBURSEMENT PARA BUHATAN OG TRANSMITTAL
    $("#add").click(function(){
        $.ajax({
            type:"POST",
            url:window.location.pathname + "?r=cash-disbursement/get-cash-disbursement",
            data:$("#add_data").serialize(),
            success:function(data) {
                console.log(JSON.parse(data))
                var res = JSON.parse(data)
                addDvToTable(res.results)
            },
        })
    })

    $("#save_data").submit(function(e){
        e.preventDefault();
        
        $.ajax({
            type:'POST',
            url:window.location.pathname + "?r=transmittal/insert-transmittal",
            data:$("#save_data").serialize(),
            success:function(data){
                var res= JSON.parse(data)
                if (res.isSuccess){

                    swal({
                        title: "Success",
                        // text: res.error,
                        type: "success",
                        timer: 3000,
                        button: false
                                // confirmButtonText: "Yes, delete it!",
                    },
                    function(){
                        window.location.href  = window.location.pathname + "?r=transmittal/view&id="+res.id
                    }
                    
                    );
                }

            }
        })
    })

JS;
$this->registerJs($script);
?>