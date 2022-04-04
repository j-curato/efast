<?php

use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use app\models\ForTransmittalSearch;
use app\models\LiquidationEntriesViewSearch;
use app\models\LiquidationViewSearch;
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
        $viewSearchModel = new LiquidationViewSearch();
        if (!empty(\Yii::$app->user->identity->province)) {
            $viewSearchModel->province = \Yii::$app->user->identity->province;
            // echo \Yii::$app->user->identity->province;
        }
        $viewSearchModel->status = 'at_po';
        $viewSearchModel->is_cancelled = 0;

        $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);

        $viewDataProvider->pagination = ['pageSize' => 10];
        // echo \Yii::$app->user->identity->province;
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
        <?php
        echo "<input type='text'  style='display:none'  value='$model->transmittal_number' id='transmittal_update_id' name='transmittal_update_id'/ >";
        ?>
        <div class="row">

            <div class="col-sm-3" style="margin:12px">
                <label for="date">Date</label>
                <?php
                // $val = !empty($model) ? $model->id : '';
                // echo "<input value='$val' name='update_id' style='display:none'/>";
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
                <th>Province</th>
                <th>Check Date</th>
                <th>Check Number</th>
                <th>DV Number</th>
                <th>Reporting Period</th>
                <th>Payee</th>
                <th>Particular</th>
                <th>Total Disbursements</th>
                <th>Total Sales Tax</th>
                <th>Total Income Tax</th>
                <th>Total Liquidation Damage</th>
                <th>Gross Payment</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <?php

                ?>

                </tr>
                <tr>
                    <td colspan="14">
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

    function addToTable(result) {

        for (var i = 0; i < result.length; i++) {

            const dv_num = result[i]['dv_number'] != null ? result[i]['dv_number'] : '';
            const parti = result[i]['particular'] != null ? result[i]['particular'] : '';
            const total_withdrawal = result[i]['total_withdrawal'] != null ? result[i]['total_withdrawal'] : '';
            const total_vat = result[i]['total_vat'] != null ? result[i]['total_vat'] : '';
            const total_expanded = result[i]['total_expanded'] != null ? result[i]['total_expanded'] : '';
            const total_liquidation_damage = result[i]['total_liquidation_damage'] != null ? result[i]['total_liquidation_damage'] : '';


            var row = `<tr>
                        <td style='display:none'><input  value='${result[i]['id']}' type='text' name='liquidation_id[]'/></td>
                        <td>${result[i]['province']}</td>
                        <td>${result[i]['check_date']}</td>
                        <td>${result[i]['check_number']}</td>
                        <td>${dv_num}</td>
                        <td>${result[i]['reporting_period']}</td>
                        <td>${result[i]['payee']}</td>
                        <td>${parti}</td>
                        <td>${total_withdrawal}</td>
                        <td>${total_vat}</td>
                        <td>${total_expanded}</td>
                        <td>${total_liquidation_damage}</td>
                        <td>${result[i]['gross_payment']}</td>
                        <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
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
            var row = `<td><a id='copy' class='btn btn-success ' type='button' onclick='copy(this)'><i class="fa fa-copy "></i></a></td>
                            <td><button id='remove' class='btn btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td>`
            var clone = checkedValue.clone();
            // console.log(clone.children('td').eq(0).find('.checkbox').val())
            // clone.children('td').eq(0).remove();
            clone.children('td').eq(0).find('.checkbox').prop('type', 'text');
            clone.children('td').eq(0).find('.checkbox').prop('name', 'liquidation_id[]');
            clone.children('td').eq(0).prop('style', 'display:none');
            clone.append(row)

            $('#transaction_table tbody').append(clone);


        });
        $('.checkbox').prop('checked', false)

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
  

    $("#save").click(function(e){
        e.preventDefault();
        
        $.ajax({
            type:'POST',
            url:window.location.pathname + "?r=po-transmittal/insert-po-transmittal",
            data:$("#save_data").serialize(),
            success:function(data){
                var res= JSON.parse(data)
                console.log(res)
                if (!res.isSuccess){

                    swal({
                        title: "Error",
                        text: res.error,
                        type: "error",
                        timer: 3000,
                        button: false
                                // confirmButtonText: "Yes, delete it!",
                    },
                    // function(){
                    //     window.location.href  = window.location.pathname + "?r=transmittal/view&id="+res.id
                    // }
                    
                    );
                }

            }
        })
    })

    $(document).ready(()=>{
        var transmittal_id = $('#transmittal_update_id').val()
        if (transmittal_id!=null){
            console.log(transmittal_id)
            $.ajax({
                type:'POST',
                url:window.location.pathname +'?r=po-transmittal/update-transmittal',
                data:{id:transmittal_id},
                success:function(data){
                    var res =JSON.parse(data).entries
                    console.log(res)
                    addToTable(res)
                }

            })
        }
    })
    

JS;
$this->registerJs($script);
?>