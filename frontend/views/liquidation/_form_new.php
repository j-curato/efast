<?php

use yii\helpers\Url;
use common\models\User;
use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Button;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */
/* @var $form yii\widgets\ActiveForm */

$transaction = [];
$user_data = User::getUserDetails();
$user_province = strtolower($user_data->employee->office->office_name);
$entries_row = 0;
$check_range_query = (new yii\db\Query())
    ->select(['id', "CONCAT(check_range.`from`,' to ',check_range.`to`) as range"])
    ->from('check_range');

if (!Yii::$app->user->can('ro_accounting_admin')) {
    $check_range_query->andWhere('province = :province', ['province' => $user_province]);
}
$check_range = $check_range_query->all();

$reporting_period = '';
$check_date = '';
$check_number = '';
$check_range_id = '';
$transaction_id = '';
$id = '';
if (!empty($model->id)) {
    $id = $model->id;
    $reporting_period = $model->reporting_period;
    $check_date = $model->check_date;
    $check_number = $model->check_number;
    $check_range_id = $model->check_range_id;

    if (!empty($model->po_transaction_id)) {
        $transaction_query = Yii::$app->db->createCommand("SELECT id,tracking_number FROM po_transaction WHERE id =:id")
            ->bindValue(':id', $model->po_transaction_id)
            ->queryAll();
        $transaction = ArrayHelper::map($transaction_query, 'id', 'tracking_number');
        $transaction_id =  [$model->po_transaction_id];
    }
}


?>
<div class="liquidation-form card">
    <form id='liquidation_form'>
        <input type="hidden" id="model_id" value='<?= $id ?>' />

        <div class="row">
            <div class="col-sm-2">
                <label for="reporting_period"> Reporting Period</label>
                <?= DatePicker::widget([
                    'name' => 'reporting_period',
                    'value' => $reporting_period,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'autoclose' => true,
                        'startView' => 'months',
                        'minViewMode' => 'months'
                    ],
                    'options' => [
                        'required' => true,
                        'readOnly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
                <div class="reporting_period_error error-block"></div>

            </div>
            <div class="col-sm-2">
                <label for="date">Date</label>
                <?= DatePicker::widget([
                    'value' => $check_date,
                    'name' => 'check_date',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true
                    ],
                    'options' => [
                        'required' => true, 'readOnly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
                <div class="check_date_error error-block"></div>
            </div>
            <div class="col-sm-3">
                <label for="check_range">Check Range</label>
                <?= Select2::widget([
                    'value' => $check_range_id,
                    'name' => 'check_range_id',

                    'id' => 'check_range_id',
                    'data' => ArrayHelper::map($check_range, 'id', 'range'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Check Range'
                    ]
                ]) ?>
                <div class="check_range_id_error error-block"></div>

            </div>
            <div class="col-sm-2">
                <label for="check_number">Check Number</label>
                <input type="text" class="form-control" name="check_number" value='<?= $check_number ?>'>
                <div class="check_number_error error-block"></div>

            </div>
            <div class="col-sm-3">
                <label for="po_transaction">Transaction</label>
                <?= Select2::widget([
                    'data' => $transaction,
                    'name' => 'po_transaction_id',
                    'id' => 'po_transaction_id',
                    'value' => $transaction_id,
                    'options' => ['placeholder' => 'Search Transaction'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=po-transaction/search-po-transaction',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page || 1}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
                <div class="po_transaction_id_error error-block"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="po_transaction">Certified By</label>
                <?= Select2::widget([
                    'name' => 'fk_certified_by',
                    'data' => ArrayHelper::map($certified_by, 'employee_id', 'fullName'),
                    'value' => $model->fk_certified_by,
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['employee/search-employee']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
                <div class="fk_certified_by_error error-block"></div>
            </div>
            <div class="col-sm-6">
                <label for="po_transaction">Approved By</label>
                <?= Select2::widget([
                    'name' => 'fk_approved_by',
                    'data' => ArrayHelper::map($approved_by, 'employee_id', 'fullName'),
                    'value' => $model->fk_approved_by,
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['employee/search-employee']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],
                ]) ?>
                <div class="fk_approved_by_error error-block"></div>
            </div>
        </div>
        <table id="transaction_details" class="table">
            <thead>

                <th>Payee</th>
                <th>Particular</th>
                <th>Responsibility Center</th>
                <th>Gross Amount</th>
            </thead>
            <tbody>
                <tr>
                    <td id="transaction_payee"></td>
                    <td id="transaction_particular"></td>
                    <td id="transaction_r_center"></td>
                    <td id="transaction_amount"></td>

                </tr>
            </tbody>
        </table>
        <table class="table " id="entries_table">

            <thead>
                <th>Reporting Period</th>
                <th>Province</th>
                <th>Fund Source</th>
                <th>Chart of Account</th>
                <th>Miscellaneous Income</th>
                <th style="padding-left:30px">Withdrawals (Net Amount)</th>
                <th>Sales Tax(Vat/Non-Vat)</th>
                <th>Income Tax (Expanded Tax)</th>
            </thead>
            <tbody>
                <?php

                if (!empty($model->liquidationEntries)) {
                    $query = Yii::$app->db->createCommand("SELECT 
                liquidation_entries.reporting_period,
                IFNULL(liquidation_entries.withdrawals,0) as withdrawals,
                IFNULL(liquidation_entries.expanded_tax,0) as expanded_tax,
                IFNULL(liquidation_entries.vat_nonvat,0) as vat_nonvat,
                IFNULL(liquidation_entries.liquidation_damage,0) as liquidation_damage,
                advances_entries.fund_source,
                advances_entries.id,
                UPPER(liquidation.province) as province,
                (CASE
                WHEN liquidation_entries.new_object_code IS NOT NULL THEN CONCAT(accounting_codes.object_code,'-',accounting_codes.account_title)
                WHEN liquidation_entries.new_chart_of_account_id IS NOT NULL THEN CONCAT(new_chart.uacs,'-',new_chart.general_ledger)
                ELSE CONCAT(orig_chart.uacs,'-',orig_chart.general_ledger) 
                END)as chart_of_account,
                (CASE
                WHEN liquidation_entries.new_object_code IS NOT NULL THEN accounting_codes.object_code
                WHEN liquidation_entries.new_chart_of_account_id IS NOT NULL THEN new_chart.uacs
                ELSE orig_chart.uacs 
                END)as object_code
                FROM liquidation_entries 
                LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
                LEFT JOIN chart_of_accounts as orig_chart ON liquidation_entries.chart_of_account_id = orig_chart.id
                LEFT JOIN chart_of_accounts as new_chart ON liquidation_entries.new_chart_of_account_id = new_chart.id
                LEFT JOIN accounting_codes ON liquidation_entries.new_object_code = accounting_codes.object_code
                WHERE 
                liquidation_entries.liquidation_id =:id
                ORDER BY liquidation_entries.reporting_period")
                        ->bindValue(':id', $model->id)
                        ->queryAll();
                    foreach ($query as $val) {

                        echo "<tr>
        
                        <td style='display:none;'>
                        <label for='advances_entries_id'></label>
                        <input disabled value='{$val['id']}'  class='advances_entries_id' type='hidden' name='advances_entries_id[{$entries_row}]'/>
                        </td>
                        <td > <input disabled type='month'data-date=''  value='{$val['reporting_period']}' data-date-format='yyyy-mm' name='new_reporting_period[{$entries_row}]' required class='new_reporting_period'  /></td>
                        <td > {$val['province']}</td>
                        <td > {$val['fund_source']}</td>
                        <td> 
        
                            <label for='liquidation-chart-of-accounts'></label>
                                <select disabled  name='object_codes[{$entries_row}]' required class='liquidation-chart-of-accounts' style='width: 200px'>
                                    <option value='{$val['object_code']}'>{$val['chart_of_account']} </option>
                                </select>
                            </td>
        
                            <td> 
                                <input disabled type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control liq_damages_mask amount mask-amount' value='{$val['liquidation_damage']}'>
                                <input disabled type='hidden'  class='liq_damages_main main_amount' name='liq_damages[{$entries_row}]' value='{$val['liquidation_damage']}'>
                            </td>
                            <td> 
                                <input disabled type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)' class='form-control withdrawal_mask amount mask-amount'  value='{$val['withdrawals']}'>
                                <input disabled type='hidden'  class='withdrawal_main main_amount' name='withdrawal[{$entries_row}]' value='{$val['withdrawals']}'>
                            </td>
                            <td> 
        
                                    <input disabled type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control vat_nonvat_mask amount mask-amount' value='{$val['vat_nonvat']}'>
                                    <input disabled type='hidden'  class='vat_nonvat_main main_amount' name='vat_nonvat[{$entries_row}]' value='{$val['vat_nonvat']}'>
        
                            </td>
                   
                            <td> 
                                <input disabled type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control  expanded_tax_mask amount mask-amount  expanded_tax' value='{$val['expanded_tax']}'>
                                <input disabled type='hidden'  class='expanded_tax_main main_amount' name='expanded_tax[{$entries_row}]' value='{$val['expanded_tax']}'>
    
                            </td>
                            <td>
                            <a class='add_new_row btn btn-primary btn-xs' onclick='copyRow(this)' type='button'><i class='fa fa-copy fa-fw'></i> </a>
                            </td>
                    </tr>";
                        $entries_row++;
                    }
                }

                ?>
            </tbody>
            <tfoot>
                <tr>

                    <td class="total_row" colspan="4">Total</td>
                    <td class="total_row" id="total_liquidation"></td>
                    <td class="total_row" id="total_withdrawal"></td>
                    <td class="total_row" id="total_vat"></td>
                    <td class="total_row" id="total_expanded"></td>

                </tr>
                <tr>
                    <td class="total_row" colspan="4">Grand Total</td>
                    <td class="total_row" id="grand_total"></td>
                    <td colspan="3"></td>

                </tr>

            </tfoot>
        </table>

        <div class="row">
            <div class="col-sm-5"></div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-success" style="width:100%" id="save_btn"> Save</button>

            </div>
            <div class="col-sm-5"></div>
        </div>
    </form>
    <!-- <form id="add_data"> -->

    <?php

    $gridColumn = [
        [
            'label' => 'Action',
            'format' => 'raw',
            'value' => function ($model) {
                return "<button 
                            class = 'add_row btn-xs btn-primary'
                            type = 'button'
                            onclick = 'addEntryRow(this)'
                            data-value = '{$model->id}'
                    ><i class='fa fa-plus'></i></button>";
                // return Button::widget([
                //     'label' => '<i class="bi bi-plus"></i>',
                //     'options' => [
                //         'class' => ' add_row btn-xs btn-primary',
                //         'type' => 'button',
                //         'onclick' => 'qwe(this)',
                //         'data-value' => $model->id
                //     ]
                // ]);
            }
        ],
        [
            'format' => 'raw',
            'attribute' => 'province',
            'value' => function ($model) {
                return "<span class='province'> {$model->province}</span>";
            }
        ],
        [
            'format' => 'raw',
            'attribute' => 'book_name',
            'value' => function ($model) {
                // $q = "<span>{$model->book_name}</span>";
                return "<span class='book_name'> {$model->book_name}</span>";
            }
        ],
        [
            'format' => 'raw',
            'attribute' => 'fund_source',
            'value' => function ($model) {
                // $q = "<span>{$model->book_name}</span>";
                return "<span class='fund_source'> {$model->fund_source}</span>";
            }
        ],

        [
            'label' => 'Gross Amount',
            'attribute' => 'amount',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'total_liquidation',
            'format' => ['decimal', 2]
        ],
        [
            'label' => 'Balance',
            'attribute' => 'balance',
            'format' => ['decimal', 2]
        ],
        'particular'



    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Advances'
        ],
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                'id' => 'pjax_advances'

            ]
        ],

        'columns' => $gridColumn
    ]); ?>

    <!-- <button class="btn btn-primary" id="add" type="text">Add</button>
    </form> -->



</div>

<style>
    .error-block {
        color: red;
    }

    #liquidation_form {
        margin-bottom: 3rem;
    }

    table,
    tr {
        max-width: 100%;
    }

    td {
        padding: 5px;
    }

    .total_row {
        text-align: center;
        font-weight: bold;
    }

    .liquidation-form {
        padding: 2rem;
        background-color: white;
    }

    .grid-view td {
        white-space: normal;
        width: 10rem;
        padding: 0;
    }

    #add {
        width: 100%;
    }

    .form-control {
        border-radius: 5px;
    }

    #save {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        border-left: none;
    }

    .select2-container .select2-selection--single {

        height: 34px;
    }

    .amount {
        margin-top: 15px;


    }

    #w1-kvdate {
        color: red;
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);

?>

<script>
    let entries_row = <?= $entries_row ?>;
    const update_type = '<?php echo $update_type ?>';
    let disable_reporting_period = '';


    function getTotalAmounts() {
        var total_liquidation = 0;
        var total_withdrawal = 0;
        var total_vat = 0;
        var total_expanded = 0;
        var grand_total = 0;




        $('.liq_damages').maskMoney('unmasked');

        $(".liq_damages_main").each(function() {
            // console.log($(this).val().split(",").join(""))
            total_liquidation += parseFloat($(this).val().split(",").join("")) || 0;
        });
        $(".withdrawal_main").each(function() {
            total_withdrawal += parseFloat($(this).val().split(",").join("")) || 0;
        });
        $(".vat_nonvat_main").each(function() {
            total_vat += parseFloat($(this).val().split(",").join("")) || 0;
        });
        $(".expanded_tax_main").each(function() {
            // console.log($(this).val())
            total_expanded += !isNaN(parseFloat($(this).val())) ? parseFloat($(this).val()) : 0
        });
        total_liquidation = !isNaN(total_liquidation) ? total_liquidation.toFixed(2) : 0
        total_withdrawal = !isNaN(total_withdrawal) ? total_withdrawal.toFixed(2) : 0
        total_vat = !isNaN(total_vat) ? total_vat.toFixed(2) : 0
        total_expanded = !isNaN(total_expanded) ? total_expanded.toFixed(2) : 0 || 0
        grand_total = !isNaN(grand_total) ? grand_total.toFixed(2) : 0

        $("#total_liquidation").text(thousands_separators(total_liquidation))
        $("#total_withdrawal").text(thousands_separators(total_withdrawal))
        $("#total_vat").text(thousands_separators(total_vat))
        $("#total_expanded").text(thousands_separators(total_expanded))
        grand_total = parseFloat(total_liquidation) + parseFloat(total_withdrawal) + parseFloat(total_vat) + parseFloat(total_expanded)
        $("#grand_total").text(thousands_separators(grand_total))

    }

    function insertEntries(data) {
        grand_total

        $.each(data, function(key, val) {
            let disabled_input = ''


            if (val.book_name.toLowerCase() == 'rapid lp' && <?php echo Yii::$app->user->can('ro_accounting_admin') ? 1 : 0 ?> != 1) {
                console.log(val.book_name.toLowerCase())
                disabled_input = 'disabled'
            }
            const row = `<tr>
        
                <td style='display:none;'>
                <label for='advances_entries_id'></label>
                <input value='${val.id}'  class='advances_entries_id' type='hidden' name='advances_entries_id[${entries_row}]'/>
                </td>
                <td > <input type='month'data-date='' ${disable_reporting_period} data-date-format='yyyy-mm' name='new_reporting_period[${entries_row}]' class='new_reporting_period' required /></td>
                <td > ${val.province}</td>
                <td > ${val.fund_source}</td>
                <td> 

                    <label for='liquidation-chart-of-accounts'></label>
                        <select  name="object_codes[${entries_row}]" required class="liquidation-chart-of-accounts" style="width: 200px">
                            <option></option>
                        </select>
                    </td>

                    <td> 
                        <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control liq_damages amount mask-amount' >
                        <input type='hidden'  class='liq_damages_main main_amount' name='liq_damages[${entries_row}]'>
                    </td>
                    <td> 
                        <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)' class='form-control withdrawal amount mask-amount' >
                        <input type='hidden'  class='withdrawal_main main_amount' name='withdrawal[${entries_row}]'>
                    </td>
                    <td> 

                            <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount' ${disabled_input}>
                            <input type='hidden'  class='vat_nonvat_main main_amount' name='vat_nonvat[${entries_row}]'>

                    </td>
           
                    <td> 
                        <input type='text'  onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control expanded_tax amount mask-amount' ${disabled_input}>
                        <input type='hidden'  class='expanded_tax_main main_amount' name='expanded_tax[${entries_row}]'>

                    </td>
                    <td>
                    <a class='add_new_row btn btn-primary btn-xs' onclick='copyRow(this)' type='button'><i class='fa fa-copy fa-fw'></i> </a>
                    <a class='remove_this_row btn btn-danger btn-xs ' onclick='removeRow(this)' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                    </td>
            </tr>`

            $('#entries_table tbody').append(row);
            entries_row++;
            liquidationAccountingCodesSelect()
            maskAmount()
        })

    }

    function removeRow(row) {
        $(row).closest('tr').remove();

    }

    function displayCopyRow(clone, disable) {
        const chart_of_account = clone.find('.liquidation-chart-of-accounts')
        const liq_damage = clone.find('.liq_damages_main')
        const advances_etries_id = clone.find('.advances_etries_id')
        const withdrawal = clone.find('.withdrawal_main')
        const non_vat = clone.find('.vat_nonvat_main')
        const expanded = clone.find('.expanded_tax_main')
        const new_reporting_period = clone.find('.new_reporting_period')
        const liq_damages_mask = clone.find('.liq_damages_mask')
        const withdrawal_mask = clone.find('.withdrawal_mask')
        const vat_nonvat_mask = clone.find('.vat_nonvat_mask')
        const expanded_mask = clone.find('.expanded_tax')
        const advances_entries_id = clone.find('.advances_entries_id')

        clone.find('.remove_this_row').remove()
        clone.find('.add_new_row').parent().append("<a class='remove_this_row btn btn-danger btn-xs ' onclick='removeRow(this)' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>")

        liq_damages_mask.prop('disabled', false)
        withdrawal_mask.prop('disabled', false)
        vat_nonvat_mask.prop('disabled', disable)
        expanded_mask.prop('disabled', disable)
        chart_of_account.attr('class', `form-control`)
        chart_of_account.css('height:12px')


        advances_entries_id.attr('name', `advances_entries_id[${entries_row}]`)
        chart_of_account.attr('name', `object_codes[${entries_row}]`)

        liq_damage.attr('name', `liq_damages[${entries_row}]`)
        withdrawal.attr('name', `withdrawal[${entries_row}]`)
        non_vat.attr('name', `vat_nonvat[${entries_row}]`)
        expanded.attr('name', `expanded_tax[${entries_row}]`)
        new_reporting_period.attr('name', `new_reporting_period[${entries_row}]`)
        const object_code = chart_of_account.val();
        const account_title = chart_of_account.text();
        // REMOVE DISABLE

        chart_of_account.prop('disabled', false)
        advances_entries_id.prop('disabled', false)
        liq_damage.prop('disabled', false)
        advances_etries_id.prop('disabled', false)
        withdrawal.prop('disabled', false)
        non_vat.prop('disabled', false)
        expanded.prop('disabled', false)

        if (update_type == 'update') {

            new_reporting_period.prop('disabled', false)
        }


        // chart_of_account.html('').select2({
        //     data: [{
        //         id: '',
        //         text: ''
        //     }]
        // });;
        // chart_of_account.val(null).trigger('change');
        liq_damage.val('')
        withdrawal.val('')
        non_vat.val('')
        expanded.val('')
        new_reporting_period.val('')
        liq_damages_mask.val('')
        withdrawal_mask.val('')
        vat_nonvat_mask.val('')
        expanded_mask.val('')

        $('#entries_table tbody').append(clone);
        entries_row++;


        // var option = new Option([account_title], [object_code], true, true);
        // chart_of_account.append(option).trigger('change');

        // chart_of_account.val(object_code).trigger('change')


        // manually trigger the `select2:select` event

        // chart_of_account.val().trigger('change');
        liquidationAccountingCodesSelect()
        maskAmount()


    }

    function copyRow(row) {

        $('.liquidation-chart-of-accounts').select2('destroy');
        //     $('.unit_of_measure').select2('destroy');
        //     $('.unit_cost').maskMoney('destroy');
        var source = $(row).closest('tr');
        let disable = false;
        var clone = source.clone(true);
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=liquidation/check-advances-book',
            data: {
                id: source.find('.advances_entries_id').val()
            },
            success: function(data) {
                const res = JSON.parse(data)
                if (res.toLowerCase() == 'rapid lp' && <?php echo Yii::$app->user->can('ro_accounting_admin') ? 1 : 0 ?> != 1) {
                    disable = true

                }
                displayCopyRow(clone, disable)
            }
        })


    }


    function unmaskAmount(amount) {

        const unmaskAmount = $(amount).maskMoney('unmasked')[0]
        $(amount).closest('td').find('.main_amount').val(unmaskAmount)
        getTotalAmounts()
    }

    function addEntryRow(row) {
        // console.log($(q).closest('tr').children().find('.book_name').text())
        const this_row = $(row)
        let disabled_input = ''

        const book_name = this_row.closest('tr').children().find('.book_name').text()
        const fund_source = this_row.closest('tr').children().find('.fund_source').text()
        const province = this_row.closest('tr').children().find('.province').text()
        const advances_entry_id = this_row.attr('data-value')
        if (book_name.trim().toLowerCase() === 'rapid lp') {
            disabled_input = 'disabled'
            console.log(disabled_input)
        }
        const add_row = `<tr>
            <td style='display:none;'>
            <label for='advances_entries_id'></label>
            <input value='${advances_entry_id}'  class='advances_entries_id' type='hidden' name='advances_entries_id[${entries_row}]'/>
            </td>
            <td > <input type='month'data-date='' ${disable_reporting_period} data-date-format='yyyy-mm' name='new_reporting_period[${entries_row}]' class='new_reporting_period' required /></td>
            <td > ${province}</td>
            <td > ${fund_source}</td>
            <td> 

                <label for='liquidation-chart-of-accounts'></label>
                    <select  name="object_codes[${entries_row}]" required class="liquidation-chart-of-accounts" style="width: 200px">
                        <option></option>
                    </select>
                </td>

                <td> 
                    <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control liq_damages amount mask-amount' >
                    <input type='hidden'  class='liq_damages_main main_amount' name='liq_damages[${entries_row}]'>
                </td>
                <td> 
                    <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)' class='form-control withdrawal amount mask-amount' >
                    <input type='hidden'  class='withdrawal_main main_amount' name='withdrawal[${entries_row}]'>
                </td>
                <td> 

                        <input type='text' onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control amount mask-amount' ${disabled_input}>
                        <input type='hidden'  class='vat_nonvat_main main_amount' name='vat_nonvat[${entries_row}]'>

                </td>

                <td> 
                    <input type='text'  onkeyup='unmaskAmount(this)' onchange='unmaskAmount(this)'  class='form-control expanded_tax amount mask-amount' ${disabled_input}>
                    <input type='hidden'  class='expanded_tax_main main_amount' name='expanded_tax[${entries_row}]'>

                </td>
                <td>
                <a class='add_new_row btn btn-primary btn-xs' onclick='copyRow(this)' type='button'><i class='fa fa-copy fa-fw'></i> </a>
                <a class='remove_this_row btn btn-danger btn-xs ' onclick='removeRow(this)' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                </td>
        </tr>`

        $('#entries_table tbody').append(add_row);
        entries_row++;
        liquidationAccountingCodesSelect()
        maskAmount()
    }

    $(document).ready(function() {

        if (update_type == 'create') {
            disable_reporting_period = 'disabled';
            $("#po_transaction_id").trigger('change')
        }

        $
        liquidationAccountingCodesSelect()
        maskAmount()
        getTotalAmounts()



        $("#po_transaction_id").change(function(e) {
            e.preventDefault();
            console.log('qqqqq')
            $.ajax({
                type: "POST",
                url: window.location.pathname + '?r=po-transaction/get-transaction',
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res)
                    $("#transaction_payee").text(res.payee)
                    $("#transaction_particular").text(res.particular)
                    $("#transaction_amount").text(res.amount)
                    $("#transaction_r_center").text(res.r_center_name)


                }
            })
        })
        // window.onload = function() {
        //     $('input[name^="AdvancesEntriesForLiquidationSearch[bank_account_id]').val('').trigger('change')
        // };
        $("#check_range_id").on('change', function(e) {
            e.preventDefault()
            // console.log($(this).val())
            // console.log(window.location.href)
            // $('input[name^="AdvancesEntriesForLiquidationSearch[bank_account_id]"]').val(1).trigger('change')
            $.pjax({
                container: "#pjax_advances",
                url: window.location.href,
                type: 'POST',
                data: {
                    check_range_id: $(this).val(),
                    filter_advances: 1
                }
            });
        })
        async function addEntry(e) {
            await $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=liquidation/add-advances',
                data: $('#add_data').serialize(),
                success: function(data) {
                    var res = JSON.parse(data)
                    insertEntries(res)

                }
            })
        }
        $('#add').on('click', function(e) {
            e.preventDefault();
            addEntry()

        })

        $('#liquidation_form').on('submit', function(event) {
            event.stopPropagation()
            event.preventDefault()
            $('#save_btn').attr('disabled', true)
            $('.error-block').html('')
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $('#liquidation_form').serialize(),
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res)
                    if (!jQuery.isEmptyObject(res.form_error)) {
                        $('#save_btn').attr('disabled', false)
                        $.each(res.form_error, function(key, val) {
                            $('.' + key + '_error').text(val[0])
                            console.log('#' + key + '_error')
                        })
                    }
                    if (!jQuery.isEmptyObject(res.check_error)) {
                        $('#save_btn').attr('disabled', false)
                        swal({
                            icon: 'error',
                            title: res.check_error,
                            type: "error",
                            timer: 5000,
                            closeOnConfirm: false,
                            closeOnCancel: false
                        })
                    }

                }
            })
        })


    })




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
 
    $(document).ready(function(){
       
    })
JS;
$this->registerJs($script);
?>