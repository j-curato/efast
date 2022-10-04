<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */
/* @var $form yii\widgets\ActiveForm */


$dv_aucs = [];
if (!empty($model->fk_dv_aucs_id)) {

    $dv_query = YIi::$app->db->createCommand("SELECT id,dv_number FROM dv_aucs WHERE id = :id")->bindValue(':id', $model->fk_dv_aucs_id)->queryAll();
    $dv_aucs = ArrayHelper::map($dv_query, 'id', 'dv_number');
}
?>

<div class="ro-liquidation-report-form">
    <div class="container">

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
            <div class="col-sm-3">

                <?= $form->field($model, 'fk_dv_aucs_id')->widget(Select2::class, [
                    'data' => $dv_aucs,
                    'options' => ['placeholder' => 'Search for a Dv Aucs ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=ro-liquidation-report/search-dv',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
        </div>
        <table id='dv_details_table' class="table">

            <thead>
                <tr>
                    <th>DV Details</th>
                </tr>

                <tr>
                    <th>Payee</th>
                    <th>Check Number</th>
                    <th>ADA Number</th>
                    <th>Particular</th>
                    <th>Issaunce Date</th>
                    <th>Total Disburse</th>
                    <th>Liquidated</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>


        <table class="entry_table table">
            <thead>
                <tr class="danger">

                    <th colspan="10" style="text-align: center;">ENTRY</th>
                </tr>
                <tr>
                    <th>Reporting Period</th>
                    <th>UACS</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $items_row_number = 0;
                foreach ($entries as  $val) {
                    $item_id = $val['id'];
                    $amount = $val['amount'];
                    $object_code = $val['object_code'];
                    $account_title = $val['account_title'];
                    $reporting_period = $val['reporting_period'];

                    echo "<tr>
                            <td>

                            <div class='input-group date entry_reporting_period reporting_period'>
                                <input type='text' class='form-control entry_reporting_period'  value='$reporting_period' name='entry_reporting_period[$items_row_number]'/>
                                <span class='input-group-addon'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            </div>
                            </td>
                            <td style='display:none;'><input class='item_ids' name='item_ids[$items_row_number]' type='hidden' value='$item_id'></td>
                        
                            <td style='max-width:300px'>
                                <select  name='entry_object_code[$items_row_number]' required class='entry_object_code chart-of-accounts' style='width: 100%'>
                                <option value='$object_code'> $object_code-$account_title</option>
                                </select>
                            </td>
                            <td>
                                <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount'  value='$amount'>
                                <input type='hidden'  class='entry_amount entry_main_amount' name='entry_amount[$items_row_number]' value='$amount'>
                            </td>
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
                <tr>
                    <th>Reporting Period</th>
                    <th>OR Date</th>
                    <th>OR Number</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $refunds_row_number = 0;
                foreach ($refund_items as  $val) {
                    $item_id = $val['id'];
                    $amount = $val['amount'];
                    $reporting_period = $val['reporting_period'];
                    $or_date = DateTime::createFromFormat('Y-m-d', $val['or_date'])->format('Y-m-d');
                    $or_number = $val['or_number'];
                    echo "<br>";
                    echo "<tr>
                            <td>
                            <div class='input-group date refund_reporting_period reporting_period'>
                                <input type='text' class='form-control refund_reporting_period'  value='$reporting_period'  name='refund_reporting_period[$refunds_row_number]'/>
                                <span class='input-group-addon'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            </div>
                            </td>
                            <td style='display:none;'><input class='refund_ids' name='refund_ids[$refunds_row_number]' type='hidden' value='$item_id'></td>
                            <td>
                                <div class='input-group date or_date' id=''>
                                    <input type='text' class='form-control refund_or_date'   value='$or_date'  name='refund_or_date[$refunds_row_number]'/>
                                    <span class='input-group-addon'>
                                        <span class='glyphicon glyphicon-calendar'></span>
                                    </span>
                                </div>
                            </td>
                            <td><input name='refund_or_number[$refunds_row_number]'  value='$or_number' class='refund_or_number form-control'></td>
                            <td>
                                <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount'  value='$amount'>
                                <input type='hidden'  class=' refund_amount entry_main_amount' name='refund_amount[$refunds_row_number]' value='$amount'>
                            </td>
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

        <div class="row">
            <div class="col-sm-5"></div>
            <div class="col-sm-2">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%;']) ?>
                </div>

            </div>
            <div class="col-sm-5"></div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <!-- <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'type' => 'primary',
                    'heading' => "DV's"
                ],
                'pjax' => true,
                'pjaxSettings' => [
                    'options' => [
                        'id' => 'qwe'

                    ]

                ],
                'columns' => [
                    [
                        'label' => 'Actions',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::button('<i class="glyphicon glyphicon-plus"></i> Entry', ['class' => 'btn-xs btn-success ', 'data-val' => $model->cash_id, 'onclick' => 'addEntry(this)']) . ' '
                                . Html::button('<i class="glyphicon glyphicon-plus"></i> Reim', ['class' => 'btn-xs btn-warning add_reim', 'data-val' => $model->cash_id, 'onclick' => "addEntry(this, 'reimbursement')"]) . ' ' .
                                Html::button('<i class="glyphicon glyphicon-plus"></i> Refund', ['class' => 'btn-xs btn-primary add_refund', 'data-val' => $model->cash_id, 'onclick' => 'addRefund(this)']);
                        }

                    ],

                    'payee',
                    'check_number',
                    'ada_number',
                    'particular',
                    'issuance_date',
                    ['attribute' => 'total_disbursed', 'format' => ['decimal', 2], 'hAlign' => 'right'],
                    ['attribute' => 'liquidated_amount', 'format' => ['decimal', 2], 'hAlign' => 'right'],
                    ['attribute' => 'balance', 'format' => ['decimal', 2], 'hAlign' => 'right'],
                ],
            ]); ?> -->


</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    .mask-amount {
        max-width: 200px;
    }

    .amount {
        text-align: right;
    }

    .reporting_period {
        max-width: 180px;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/moment.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/css/bootstrap-datepicker.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/bootstrap-datepicker.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;

?>

<script>
    function bootstrapDate() {
        $('.or_date').datetimepicker({
            format: 'YYYY-MM-DD',
        });
    };

    function reportingPeriodDatePicker() {
        $('.reporting_period').datetimepicker({
            format: 'YYYY-MM',
            viewMode: 'months'
        });
    };
    let items_row_number = <?php echo $items_row_number ?>;
    let refunds_row_number = <?php echo $refunds_row_number ?>;


    function unmaskAmount(amount) {

        const unmaskAmount = $(amount).maskMoney('unmasked')[0]
        $(amount).closest('td').find('.entry_main_amount').val(unmaskAmount)
        // getTotalAmounts()
    }


    function addEntry(this_row, type = '') {
        const clone = $(this_row).closest('tr').clone()
        const id = $(this_row).attr('data-val')
        let reim = 0;

        if (type == 'reimbursement') {
            reim = 1
        }
        const row = `<tr>
             <td>
             <div class='input-group date entry_reporting_period reporting_period'>
                    <input type='text' class='form-control entry_reporting_period'  name='entry_reporting_period[${items_row_number}]'/>
                    <span class='input-group-addon'>
                        <span class='glyphicon glyphicon-calendar'></span>
                    </span>
                </div>
             </td>
             <td  style='max-width:300px'>
                <select  name="entry_object_code[${items_row_number}]" required class="chart-of-accounts" style="width: 100%">
                    <option></option>
                </select>
            </td>
            <td>
                <input type='text' onkeyup='unmaskAmount(this)'  class='form-control amount mask-amount' required>
                <input type='hidden'  class=' entry_main_amount' name='entry_amount[${items_row_number}]'>
            </td>
            <td>
                <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
                <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
            </td>
            <td style='display:none'><input name='is_reim[${items_row_number}]' type='hidden' value='${reim}'
        </tr>`

        // clone.find('#actions').remove()
        // const entry_amount = `<td>

        //     <input type='text' onkeyup='unmaskAmount(this)'  class='form-control amount mask-amount' required>
        //                     <input type='hidden'  class=' entry_main_amount' name='entry_amount[${items_row_number}]'>
        //     </td>`
        // const entry_reporting_period = `<td><input name='entry_reporting_period[${items_row_number}]' type='month'></td>`
        // const entry_object_code = `<td>
        //     <select  name="entry_object_code[${items_row_number}]" required class="chart-of-accounts" style="width: 200px">
        //                     <option></option>
        //                 </select>
        //     </td>`
        // clone.append(`<td style='display:none;'><input name='entry_cash_id[${items_row_number}]' class='entry_cash_id' value='${id}'></td>`)
        // clone.append(entry_object_code)
        // clone.append(entry_amount)
        // clone.children().eq(0).after(entry_reporting_period)
        // clone.find('.add_refund').parent().remove()
        // clone.find('.add_entry').parent().remove()
        // const action_buttons = `<td>

        //     <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
        //     <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
        //     </td>`
        // clone.append(action_buttons)




        $('.entry_table tbody').append(row)

        items_row_number++
        accountingCodesSelect()
        maskAmount()
        reportingPeriodDatePicker()
    }

    function addRefund(this_row) {
        const clone = $(this_row).closest('tr').clone()
        const id = $(this_row).attr('data-val')
        // const refund_amount = `<td>
        //     <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount' placeholder='Amount'>
        //                     <input type='hidden'  class=' entry_main_amount' name='refund_amount[${refunds_row_number}]'>
        //     </td>`
        // const refund_reporting_period = `<td><input name='refund_reporting_period[${refunds_row_number}]' type='month'></td>`
        // const refund_object_code = `
        //     <td><input name='refund_or_date[${refunds_row_number}]' class='or_date form-control' type='date'></td>
        //     <td><input name='refund_or_number[${refunds_row_number}]'  placeholder='OR Number' class='form-control'></td>
        //     `
        // clone.append(refund_object_code)
        // clone.append(refund_amount)
        // clone.children().eq(0).after(refund_reporting_period)
        // clone.children().eq(0).after(`<td style='display:none;'><input name='refund_cash_id[${refunds_row_number}]' class='refund_cash_id' value='${id}'></td>`)
        // clone.find('.add_refund').parent().remove()
        // clone.find('.add_entry').parent().remove()
        // const action_buttons = `<td>

        //     <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
        //     <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
        //     </td>`
        // clone.append(action_buttons)


        const refund_row = `<tr>
            <td>
                <div class='input-group date refund_reporting_period reporting_period'>
                    <input type='text' class='form-control refund_reporting_period'  name='refund_reporting_period[${refunds_row_number}]'/>
                    <span class='input-group-addon'>
                        <span class='glyphicon glyphicon-calendar'></span>
                    </span>
                </div>
            </td>
            <td>
            <div class="form-group">
                <div class='input-group date or_date' id=''>
                    <input type='text' class="form-control refund_or_date or_date" name='refund_or_date[${refunds_row_number}]' />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            </td>
            <td><input  name='refund_or_number[${refunds_row_number}]'  placeholder='OR Number' class='form-control refund_or_number'></td>
            <td>
                <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount' placeholder='Amount'>
                <input type='hidden'  class='refund_amount entry_main_amount' name='refund_amount[${refunds_row_number}]'>
            </td>
            <td>
                <button class='btn-xs btn-primary copy' type='button'><i class='fa fa-copy fa-fw'></i> </button>
                <button class='btn-xs btn-danger remove' type='button'><i class='fa fa-times fa-fw'></i> </button>
            </td>
        </tr>`
        $('.refund_table tbody').append(refund_row)

        refunds_row_number++
        accountingCodesSelect()
        maskAmount()
        bootstrapDate()
        reportingPeriodDatePicker()
    }

    function getDvDetails(id) {
        $.ajax({
            type: 'POST',
            url: window.location.pathname + "?r=ro-liquidation-report/dv-details",
            data: {
                id: id,
                '_csrf-frontend': '<?= $csrfToken ?>'
            },
            success: function(data) {
                const res = JSON.parse(data)
                $('#dv_details_table tbody').html('')
                const details_row = `<tr>
                        <td>${res.payee}</td>
                        <td>${res.check_number}</td>
                        <td>${res.ada_number}</td>
                        <td style='max-width:200px'>${res.particular}</td>
                        <td>${res.issuance_date}</td>
                        <td class='amount'>${thousands_separators(res.total_disbursed)}</td>
                        <td class='amount'>${thousands_separators(res.liquidated_amount)}</td>
                        <td class='amount'>${thousands_separators(res.balance)}</td>
                        <td id='actions' style='min-width:200px'>
                        <button type='button' class='btn-xs btn-success'  onclick= 'addEntry(this)'  data-val = '${res.cash_id}'> <i class='glyphicon glyphicon-plus'></i>Entry</button>
                        <button type='button' class='btn-xs btn-warning'  onclick= 'addEntry(this,"reimbursement")'  data-val = '${res.cash_id}'> <i class='glyphicon glyphicon-plus'></i>Reim</button>
                        <button type='button' class='btn-xs btn-primary' onclick= 'addRefund(this)'  data-val = '${res.cash_id}'> <i class='glyphicon glyphicon-plus' ></i>Refund</button>
                        </td>
                    </tr>`

                $('#dv_details_table tbody').append(details_row)
            }
        })
    }
    $(document).ready(function() {
        bootstrapDate()
        reportingPeriodDatePicker()
        accountingCodesSelect()
        maskAmount()
        // $('.add_entry').click(function(e) {
        //     e.preventDefault()
        //     addEntry($(this), )
        // })
        // $('.add_reim').click(function(e) {
        //     e.preventDefault()
        //     addEntry($(this), 'reimbursement')
        // })
        // ADD TABLE IN REFUND TABLE
        // $('.add_refund').click(function(e) {
        //     e.preventDefault()
        // })
        $('.refund_table , .entry_table').on('click', '.remove', function() {
            $(this).closest('tr').remove()
        })

        $('.refund_table').on('click', '.copy', function() {
            const clone = $(this).closest('tr').clone()
            clone.find('.refund_cash_id').attr('name', `refund_cash_id[${refunds_row_number}]`)
            clone.find('.refund_or_number').attr('name', `refund_or_number[${refunds_row_number}]`)
            clone.find('.refund_or_number').val('')
            clone.find('.refund_or_date').attr('name', `refund_or_date[${refunds_row_number}]`)
            clone.find('.refund_or_date').val('')
            clone.find('.refund_reporting_period').attr('name', `refund_reporting_period[${refunds_row_number}]`)
            clone.find('.refund_reporting_period').val('')
            clone.find('.refund_amount').attr('name', `refund_amount[${refunds_row_number}]`)
            clone.find('.refund_amount').val('')
            clone.find('.amount').val('')
            clone.find('.refund_ids').attr('name', '')
            clone.find('.refund_ids').val('')
            $('.refund_table tbody').append(clone)
            refunds_row_number++
            accountingCodesSelect()
            maskAmount()
            bootstrapDate()
            reportingPeriodDatePicker()
        })
        $('.entry_table').on('click', '.copy', function() {
            const clone = $(this).closest('tr').clone()
            clone.find('.entry_cash_id').attr('name', `entry_cash_id[${items_row_number}]`)
            clone.find('.entry_reporting_period').val('')
            clone.find('.entry_reporting_period').attr('name', `entry_reporting_period[${items_row_number}]`)
            clone.find('.entry_object_code').attr('name', `entry_object_code[${items_row_number}]`)
            clone.find('.entry_amount').val('')
            clone.find('.entry_amount').attr('name', `entry_amount[${items_row_number}]`)
            clone.find('.amount').val('')
            clone.find('.select2-container').remove()
            $('.entry_table tbody').append(clone)

            items_row_number++
            reportingPeriodDatePicker()
            maskAmount()
            accountingCodesSelect()

        })
        if ($('#roliquidationreport-fk_dv_aucs_id').val() != null && $('#roliquidationreport-fk_dv_aucs_id').val() != '') {
            getDvDetails($('#roliquidationreport-fk_dv_aucs_id').val())
        }

        $('#roliquidationreport-fk_dv_aucs_id').change(function() {
            getDvDetails($(this).val())
        })
    })
</script>