<?php

use yii\helpers\Html;
use common\models\User;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;
use app\models\CashDisbursement;
use app\models\ForTransmittalSearch;
use app\models\LiquidationViewSearch;
use app\models\CashDisbursementSearch;
use aryelds\sweetalert\SweetAlertAsset;
use app\models\LiquidationEntriesViewSearch;


/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transmittal-form">
    <form id="add_data">
        <?php
        $viewSearchModel = new LiquidationViewSearch();
        if (!YIi::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $viewSearchModel->province = strtolower($user_data->employee->office->office_name);
        }
        $viewSearchModel->status = 'at_po';

        $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);

        $viewDataProvider->pagination = ['pageSize' => 10];
        $viewColumn = [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id,  'style' => 'width:20px;', 'class' => 'checkbox', ''];
                }
            ],
            'province',

            'check_date',
            'check_number',
            'dv_number',
            'reporting_period',
            'payee',
            'particular',

            [
                'label' => 'Total Disbursements',
                'attribute' => 'total_withdrawal',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'label' => 'Total Sales Tax (VAT/Non-VAT)',
                'attribute' => 'total_vat',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'label' => 'Income Tax (Expanded Tax)',
                'attribute' => 'total_expanded',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'label' => 'Total Liquidation',
                'attribute' => 'total_liquidation_damage',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'label' => 'Gross Payment',
                'attribute' => 'gross_payment',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],


        ];
        ?>
        <?= GridView::widget([
            'dataProvider' => $viewDataProvider,
            'filterModel' => $viewSearchModel,
            'columns' => $viewColumn,
            'pjax' => true,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => 'Liquidations',
            ],
            'export' => false

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
    $("#add").on('click', function(e) {
        e.preventDefault()
        // var q = $('.checkbox :checked').val();
        // console.log(q)
        // $.each($(".checkbox :checked"), function(){
        //         console.log($(this).val())
        //     });
        var checkedValue = null;
        // var inputElements = $('.checkbox');
        // for (var i = 0; inputElements[i]; ++i) {
        //     if (inputElements[i].checked) {
        //         checkedValue = inputElements[i].closest('tr');
        //           console.log(checkedValue)



        //     }
        // }
        // var yourArray=[]
        $(".checkbox:checked").each(function() {
            checkedValue = $(this).closest('tr');
            checkedValue.closest('.checkbox').removeAttr('checked')


            // console.log()
            // $.each(checkedValue.find('td:has([data-col-seq])').attr("data-col-seq"), () => {
            //     console.log($(this).val())
            // })
            // checkedValue.find('td[data-col-seq]').attr("data-col-seq").each(function() {
            //     // do your cool stuff
            //     console.log($(this).val())
            // });
            var row = `
             <td><a id='copy' class='btn btn-success ' type='button' onclick='copy(this)'><i class="fa fa-copy "></i></a></td>
                            <td><button id='remove' class='btn btn-danger ' onclick='remove(this)'><i class="fa fa-times"></i></button></td>`
            var clone = checkedValue.clone();
            clone.children('td').eq(0).remove();
            clone.append(row)

            $('#transaction_table tbody').append(checkedValue);


        });

    })
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
  

    $("#sumb").click(function(e){
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