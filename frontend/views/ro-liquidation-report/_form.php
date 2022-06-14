<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */
/* @var $form yii\widgets\ActiveForm */



?>

<div class="ro-liquidation-report-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'minViewMode' => 'months',
                    'format' => 'yyyy-mm'
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) ?>
        </div>
    </div>



    <table class="entry_table table">
        <thead>
            <tr class="danger">

                <th colspan="10" style="text-align: center;">ENTRY</th>
            </tr>

        </thead>
        <tbody>
            <?php
            $items_row_number = 0;
            foreach ($entries as  $val) {
                $cash_id = $val['cash_id'];
                $item_id = $val['id'];
                $amount = $val['amount'];
                $object_code = $val['object_code'];
                $account_title = $val['account_title'];
                $reporting_period = $val['reporting_period'];
                $payee = $val['payee'];
                $check_or_ada_no = $val['check_or_ada_no'];
                $particular = $val['particular'];
                $issuance_date = $val['issuance_date'];
                $ada_number = $val['ada_number'];
                $total_disbursed = $val['total_disbursed'];
                echo "<tr>
                <td><input name='entry_reporting_period[$items_row_number]' type='month' format='yyyy-MM' value='$reporting_period'></td>
                <td style='display:none;'><input name='item_ids[$items_row_number]' type='hidden' value='$item_id'></td>
                <td>$payee</td>
                <td>$check_or_ada_no</td>
                <td>$ada_number</td>
                <td>$particular</td>
                <td>$issuance_date</td>
                <td>$total_disbursed</td>
                <td>
                    <select  name='entry_object_code[$items_row_number]' required class='chart-of-accounts' style='width: 200px'>
                     <option value='$object_code'> $object_code-$account_title</option>
                    </select>
                </td>
                <td>
                    <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount'  value='$amount'>
                    <input type='hidden'  class=' entry_main_amount' name='entry_amount[$items_row_number]' value='$amount'>
                </td>
                <td style='display:none;'><input name='entry_cash_id[$items_row_number]' class='entry_cash_id' value='$cash_id'></td>
                <td>
            
            <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
            <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
            </td>
                </tr>";
                $items_row_number++;
            }
            ?>
        </tbody>
    </table>
    <table class="refund_table table">
        <thead>
            <tr class="warning">
                <th colspan="10" style="text-align: center;">REFUND</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $refunds_row_number = 0;
            foreach ($refund_items as  $val) {
                $cash_id = $val['cash_id'];
                $item_id = $val['id'];
                $amount = $val['amount'];
                $reporting_period = $val['reporting_period'];
                $payee = $val['payee'];
                $check_or_ada_no = $val['check_or_ada_no'];
                $particular = $val['particular'];
                $issuance_date = $val['issuance_date'];
                $ada_number = $val['ada_number'];
                $total_disbursed = $val['total_disbursed'];
                $or_date = $val['or_date'];
                $or_number = $val['or_number'];
                echo "<tr>
                <td><input name='refund_reporting_period[$refunds_row_number]' type='month' format='yyyy-MM' value='$reporting_period'></td>
                <td style='display:none;'><input name='refund_ids[$refunds_row_number]' type='hidden' value='$item_id'></td>
                <td>$payee</td>
                <td>$check_or_ada_no</td>
                <td>$ada_number</td>
                <td>$particular</td>
                <td>$issuance_date</td>
                <td>$total_disbursed</td>
                <td><input name='refund_or_date[$refunds_row_number]' class='or_date' type='date' value='$or_date'></td>
                <td><input name='refund_or_number[$refunds_row_number]'  value='$or_number'></td>
                <td>
                    <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount'  value='$amount'>
                    <input type='hidden'  class=' entry_main_amount' name='refund_amount[$refunds_row_number]' value='$amount'>
                </td>
                <td style='display:none;'><input name='refund_cash_id[$refunds_row_number]' class='refund_cash_id' value='$cash_id'></td>
                <td>
            
            <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
            <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
            </td>
                </tr>";
                $refunds_row_number++;
            }
            ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => "DV's"
        ],
        'columns' => [
            [
                'label' => 'My Label',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::button('Add Entry', ['class' => 'btn btn-primary add_entry', 'data-val' => $model->cash_id]);
                }

            ],
            [
                'label' => 'My Label',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::button('Add Refund', ['class' => 'btn btn-primary add_refund', 'data-val' => $model->cash_id]);
                }

            ],
            'payee',
            'check_number',
            'ada_number',
            'particular',
            'issuance_date',
            'total_disbursed',
            'liquidated_amount',
            'balance'
        ],  
    ]); ?>
</div>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);


?>

<script>
    let items_row_number = <?php echo $items_row_number ?>;
    let refunds_row_number = <?php echo $refunds_row_number ?>;


    function unmaskAmount(amount) {

        const unmaskAmount = $(amount).maskMoney('unmasked')[0]
        $(amount).closest('td').find('.entry_main_amount').val(unmaskAmount)
        getTotalAmounts()
    }



    $(document).ready(function() {

        accountingCodesSelect()
        maskAmount()
        $('.add_entry').click(function(e) {
            e.preventDefault()
            const clone = $(this).closest('tr').clone()
            const id = clone.find('.add_refund').attr('data-val')
            // cash id 
            const entry_amount = `<td>

            <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount' >
                            <input type='hidden'  class=' entry_main_amount' name='entry_amount[${items_row_number}]'>
            </td>`
            const entry_reporting_period = `<td><input name='entry_reporting_period[${items_row_number}]' type='month'></td>`
            const entry_object_code = `<td>
            <select  name="entry_object_code[${items_row_number}]" required class="chart-of-accounts" style="width: 200px">
                            <option></option>
                        </select>
            </td>`
            clone.append(`<td style='display:none;'><input name='entry_cash_id[${items_row_number}]' class='entry_cash_id' value='${id}'></td>`)
            clone.append(entry_object_code)
            clone.append(entry_amount)
            clone.children().eq(0).after(entry_reporting_period)
            clone.find('.add_refund').parent().remove()
            clone.find('.add_entry').parent().remove()
            const action_buttons = `<td>
            
            <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
            <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
            </td>`
            clone.append(action_buttons)
            $('.entry_table tbody').append(clone)

            items_row_number++
            accountingCodesSelect()
            maskAmount()
        })
        // ADD TABLE IN REFUND TABLE
        $('.add_refund').click(function(e) {
            e.preventDefault()
            const clone = $(this).closest('tr').clone()

            const id = clone.find('.add_refund').attr('data-val')
            const refund_amount = `<td>
            <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount' >
       
                            <input type='hidden'  class=' entry_main_amount' name='refund_amount[${refunds_row_number}]'>
            </td>`
            const refund_reporting_period = `<td><input name='refund_reporting_period[${refunds_row_number}]' type='month'></td>`
            const refund_object_code = `
            <td><input name='refund_or_date[${refunds_row_number}]' class='or_date' type='date'></td>
            <td><input name='refund_or_number[${refunds_row_number}]' ></td>
            `
            clone.append(refund_object_code)
            clone.append(refund_amount)
            clone.children().eq(0).after(refund_reporting_period)
            clone.children().eq(0).after(`<td style='display:none;'><input name='refund_cash_id[${refunds_row_number}]' class='refund_cash_id' value='${id}'></td>`)
            clone.find('.add_refund').parent().remove()
            clone.find('.add_entry').parent().remove()
            const action_buttons = `<td>
            
            <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
            <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
            </td>`
            clone.append(action_buttons)
            $('.refund_table tbody').append(clone)

            refunds_row_number++
            accountingCodesSelect()
            maskAmount()

        })
        $('.refund_table , .entry_table').on('click', '.remove', function() {
            $(this).closest('tr').remove()
        })

        $('.refund_table').on('click', '.copy', function() {
            const clone = $(this).closest('tr').clone()
            clone.find('.refund_cash_id').attr('name', `refund_cash_id[${refunds_row_number}]`)
            $('.refund_table tbody').append(clone)
            refunds_row_number++
        })
        $('.entry_table').on('click', '.copy', function() {
            const clone = $(this).closest('tr').clone()
            clone.find('.entry_cash_id').attr('name', `entry_cash_id[${items_row_number}]`)
            $('.entry_table tbody').append(clone)
            items_row_number++

        })
    })
</script>