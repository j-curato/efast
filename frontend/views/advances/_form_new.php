<?php

use app\models\CashDisbursementSearch;
use app\models\DvAucs;
use app\models\DvAucsSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advances-form">



    <div class="container panel panel-default">
        <form id='save_data'>
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
                        '101 OPEX CDR' => '101 OPEX CDR',
                        '101 SDO CDR' => '101 SDO CDR',
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
                    <th>Mode of Payment</th>
                    <th>Check Number</th>
                    <th>Ada Number</th>
                    <th>Check Date</th>
                    <th>Payee</th>
                    <th>Particular</th>
                    <th>Sub Account</th>
                    <th>Amount</th>
                </thead>
                <tbody></tbody>
            </table>

            <button class="btn btn-success" type="submit">Save</button>
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
                    'heading' => "List Of DV's",
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
    </div>
    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
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


    function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
        console.log(num)
    }

    function remove(i) {
        i.closest("tr").remove()
        dv_count--
        getTotal()
    }

    function addToTransactionTable(result) {


        for (var i = 0; i < result.length; i++) {
            var row = `<tr>
                    <td style='display:none' > <input value='${result[i]['cash_disbursement_id']}' type='text' name='cash_disbursement_id[]'/></td>

                    <td> ${result[i]['dv_number']}</td>
                    <td> ${result[i]['mode_of_payment']}</td>
                    <td> ${result[i]['check_or_ada_no']}</td>
                    <td> ${result[i]['ada_number']}</td>
                    <td> ${result[i]['issuance_date']}</td>
                    <td> ${result[i]['payee']}</td>
                    <td> 
                        ${result[i]['particular']}
                    </td>

                    <td> 
                             <select id="chart-${i}" name="sub_account1[]" required class="sub_account_1" style="width: 200px">
                                <option></option>
                            </select>
                    </td>
                    
                    <td> 
                         <input type='text' id='amount-${i}' class='q' name='amount[]'>
                    </td>
     
                  
                    <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
                `
            $("#transaction_table tbody").append(row)
             $(`#amount-${i}`).maskMoney({
                allowNegative: true
            });
            $(`#chart-${i}`).select2({
                data: accounts,
                placeholder: "Select Chart of Account",

            });
            if ($("#update_id").val() > 0) {
                $(`#chart-${i}`).val( result[i]['sub_account1_id']).trigger('change')
                $(`#amount-${i}`).val( result[i]['amount']).trigger('change')
            }
           

        }
    }
    $(document).ready(function() {

        $.getJSON('/dti-afms-2/frontend/web/index.php?r=sub-accounts1/get-all-sub-account1')
            .then(function(data) {
                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.object_code + ' ' + val.name
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

                    addToTransactionTable(res)

                }
                

            })

        }   
    })

    
JS;

$this->registerJs($script);

?>