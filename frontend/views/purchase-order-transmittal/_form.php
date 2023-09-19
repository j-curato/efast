<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-order-transmittal-form card" style="padding: 1rem;">

    <?php


    $gridColumns = [

        [
            'label' => 'Action',
            'format' => 'raw',
            'value' => function ($model) {
                return "<button 
                            class = 'add_row btn-xs btn-primary'
                            type = 'button'
                            onclick = 'addRow(this)'
                            data-value = '{$model->id}'
                    ><i class='fa fa-plus'></i></button>";
            }
        ],
        [
            'attribute' => 'id',
            'format' => 'raw',
            'hidden' => true,
            'value' => function ($model) {


                return "<input type='text' class='po_id' value='{$model->id}' name='pr_purchase_order_item_ids[]'>";
            }
        ],
        'serial_number',
        'payee',
        'purpose',

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

    <?= Html::beginForm([$action, 'id' => $model->id], 'post', ['id' => 'purchase_order_transmittal_form']); ?>
    <div class="row">
        <div class="col-sm-3">

            <label for="date">Date</label>
            <?php
            echo DatePicker::widget([
                'name' => 'date',
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
            <th>PO Number</th>
            <th>Payee</th>
            <th>Purpose</th>
        </thead>
        <tbody>
            <?php
            $item_row = 1;
            if (!empty($model->id)) {
                foreach ($items as $val) {
                    $po_id = $val['po_id'];
                    $id = $val['id'];
                    $serial_number = $val['serial_number'];
                    $payee = $val['payee'];
                    $purpose = $val['purpose'];
                    echo "<tr>
                        <td style='display:none'><input type='text' class='item_id' value='{$id}' name='item_id[$item_row]'>
                        <input type='text' class='po_id' value='{$po_id}' name='pr_purchase_order_item_ids[$item_row]'></td>
                        <td>$serial_number</td>
                        <td>$payee</td>
                        <td>$purpose</td>
                        <td><button type='button' class='remove btn-xs btn-danger'><i class='fa fa-times'></i></button></td>
                    </tr>";
                    $item_row++;
                }
            }
            ?>
        </tbody>

    </table>
    <div class="row justify-content-center">
        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%;margin:3rem 0 4rem 0']); ?>

        </div>
    </div>
    <?= Html::endForm(); ?>

</div>

<script>
    let row_num = 0;

    function addRow(row) {
        const $this = $(row)
        const clone = $this.closest('tr').clone()
        clone.find('.po_id').attr('name', `pr_purchase_order_item_ids[${row_num}]`)
        // console.log(clone.find('.po_id').attr('name'))
        clone.find('.add_row').parent().remove()
        clone.append('<td><button type="button" class="remove btn-xs btn-danger"><i class="fa fa-times"></i></button></td>')
        $('#transaction_table').append(clone)
        row_num++
    }
    $(document).ready(function() {

        $('#transaction_table').on('click', '.remove', function(e) {
            console.log(this)
            $(this).closest('tr').remove()
        })
    })
</script>