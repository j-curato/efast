<?php

use app\models\AdvancesEntriesSearch;
use app\models\AdvancesSearch;
use app\models\Payee;
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


    <div class="container panel panel-default">
        <form id='save_data'>
            <?php
            !empty($model->id) ? $x = $model->id : $x = '';
            echo "<input type='text' value='$x' name='update_id' id='update_id' style='display:none'/>";
            $particular = '';
            $payee = '';
            $check_date = '';
            $check_number = '';
            if (!empty($model)) {
                $particular = $model->particular;
                $payee = $model->payee_id;
                $check_date = $model->check_date;
                $check_number = $model->check_number;
            }
            ?>
            <div class="row">
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
                <div class="col-sm-12">
                    <label for="particular">Particular</label>

                    <?php

                    echo "<textarea name='particular' id='particular' rows='2' style='width: 100%;max-width:100%' value'>$particular</textarea>";
                    ?>
                </div>
            </div>

            <table class="table table-striped" id="transaction_table">

                <thead>
                    <th>NFT Number</th>
                    <th>Report</th>
                    <th>Province</th>
                    <th>Fund Source</th>
                    <th>Advances NFT Number</th>
                    <th>Withdrawals</th>
                    <th>Tax1</th>
                    <th>Tax2</th>
                </thead>
                <tbody>

                </tbody>
            </table>
            <button class="btn btn-success" id='save' type="submit">Save</button>
        </form>

        <form id="add_data">

            <?php
            $searchModel = new AdvancesSearch();
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
                'nft_number',
                [
                    "label" => "Report",
                    "attribute" => "report_type"
                ],

                [
                    "label" => "Province",
                    "attribute" => "province"
                ],
                [
                    "label" => "Fund Source",
                    "attribute" => "particular"
                ],
                // [
                //     'label' => 'Amount Disbursed',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         return ' ' .  MaskMoney::widget([
                //             'name' => "amount_disbursed[$model->id]",
                //             'disabled' => true,
                //             'id' => "amount_disbursed_$model->id",
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
                // [
                //     'label' => '2306 (VAT/ Non-Vat)',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         return ' ' .  MaskMoney::widget([
                //             'name' => "vat_nonvat[$model->id]",
                //             'disabled' => true,
                //             'id' => "vat_nonvat_$model->id",
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
                // [
                //     'label' => '2307 (EWT Goods/Services)',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         return ' ' .  MaskMoney::widget([
                //             'name' => "ewt_goods_services[$model->id]",
                //             'disabled' => true,
                //             'id' => "ewt_goods_services_$model->id",
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


                ['class' => 'yii\grid\ActionColumn'],
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

            <button class="btn btn-primary" id="add" type="submit" >Add</button>
        </form>

    </div>

</div>
<style>
    .container {
        padding: 2rem;
    }

    .grid-view td {
        white-space: normal;
        width: 10rem;
        padding: 0;
    }
    #add{
        width:100%;
    }
    #save{
        width:100%;
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
        dv_count--
        getTotal()
    }

    function addToTransactionTable(result) {


        for (var i = 0; i < result.length; i++) {
            var row = `<tr>
                    <td style='display:none'> <input value='${result[i]['id']}' type='text' name='advances_id[]'/></td>

                    <td> ${result[i]['nft_number']}</td>
                    <td> ${result[i]['report_type']}</td>
                    <td> ${result[i]['province']}</td>
                    <td> ${result[i]['particular']}</td>

                    <td> 
                        <select id="chart-${transaction_table_count}" name="chart_of_account_id[]" required class="chart_of_account_id" style="width: 200px">
                            <option></option>
                        </select>
                    </td>
                    
                    <td> 
                        <div class='form-group' style='width:150px'>
                        <input type='text' id='withdrawal-${transaction_table_count}' class='form-control' name='withdrawal[]'>
                        </div>
                    </td>
                    <td> 
                        <div class='form-group' style='width:150px'>

                            <input type='text' id='vat_nonvat-${transaction_table_count}' class='form-control' name='vat_nonvat[]'>
                        </div>

                    </td>
                    <td> 
                         <div class='form-group' style='width:150px'>
                            <input type='text' id='ewt-${transaction_table_count}' class='form-control' name='ewt[]'>
                         </div>

                    </td>
     
                  
                    <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
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
                // addToTransactionTable(res)
                if (res.isSuccess){
                    swal({
                        title:'Success',
                        type:'success',
                        button:false,

                    })
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
                    addToTransactionTable(res)
                }

            })
        }
    })
            
JS;
$this->registerJs($script);
?>