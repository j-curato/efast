<?php

use app\models\Books;
use app\models\DvTransactionType;
use app\models\MrdClassification;
use app\models\NatureOfTransaction;
use app\models\Payee;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */
/* @var $form yii\widgets\ActiveForm */

$payee = [];
$accounting_entry_row = 0;
$advancesItemRow = 1;
if (!empty($model->payee_id)) {
    $payee = ArrayHelper::map(Payee::find()->where('id = :id', ['id' => $model->payee_id])->asArray()->all(), 'id', 'account_name');
}
$dv_object_code = [];

if (!empty($model->object_code)) {

    $q = Yii::$app->db->createCommand("SELECT object_code,CONCAT(object_code,'-',account_title) as account_title FROM accounting_codes WHERE object_code =:object_code")
        ->bindValue(':object_code', $model->object_code)
        ->queryAll();
    $dv_object_code = ArrayHelper::map($q, 'object_code', 'account_title');
}
?>

<div class="dv-aucs-form panel panel-dedfault">

    <?php $form = ActiveForm::begin(); ?>



    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months',
                    'autoclose' => true
                ]
            ]) ?>

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'nature_of_transaction_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(NatureOfTransaction::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Nature  of Transaction'
                ]
            ]) ?>

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'mrd_classification_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(MrdClassification::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Nature  of MRD Classification'
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'object_code')->widget(Select2::class, [
                'data' => $dv_object_code,
                'value' => !empty($model->object_code) ?? '',
                'options' => ['placeholder' => 'Search for a UACS ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=chart-of-accounts/search-accounting-code',
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
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'payee_id')->widget(Select2::class, [
                'data' => $payee,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'placeholder' => 'Select Payee',
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=payee/search-payee',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,page: params.page||1 };}'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_dv_transaction_type_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(DvTransactionType::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Transaction Type'
                ]
            ]) ?>

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'book_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Book'
                ]
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'particular')->textarea(['rows' => 6]) ?>



    <table id="items_table" class="table table-striped">
        <thead>
            <th>Serial Number</th>
            <th>Particular</th>
            <th>Payee</th>
            <th>Total Obligated</th>
            <th>Amount Disbursed</th>
            <th>2306 (VAT/ Non-Vat)</th>
            <th>2307 (EWT Goods/Services)</th>
            <th>1601C (Compensation)</th>
            <th>Other Trust Liabilities</th>
        </thead>
        <tbody>
            <?php
            $itemRow = 0;
            $ttl_amount_disbursed = 0;
            $ttl_vat_nonvat = 0;
            $ttl_ewt_goods_services = 0;
            $ttl_compensation = 0;
            $ttl_other_trust_liabilities = 0;
            foreach ($items as $val) {
                $ttl_amount_disbursed += floatval($val['amount_disbursed']);
                $ttl_vat_nonvat += floatval($val['vat_nonvat']);
                $ttl_ewt_goods_services += floatval($val['ewt_goods_services']);
                $ttl_compensation += floatval($val['compensation']);
                $ttl_other_trust_liabilities += floatval($val['other_trust_liabilities']);
                echo "<tr>
                        <td style='display:none;'>
                            <input  name='items[$itemRow][process_ors_id]' type='hidden'  value='{$val['process_ors_id']}'/>
                            <input value='{$val['item_id']}' type='hidden' name='items[$itemRow][item_id]'/>
                        </td>
                        <td> {$val['serial_number']}</td>
                        <td> {$val['particular']}</td>
                        <td> {$val['payee_name']}</td>
                        <td> {$val['total']}</td>
                        <td>
                            <input type='text'  class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='" . number_format($val['amount_disbursed'], 2) . "'/>
                            <input  name='items[$itemRow][amount_disbursed]' type='hidden'  class='amount_disbursed main-amount' value='{$val['amount_disbursed']}'/>
                        </td>
                        <td> 
                                <input type='text' class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='{$val['vat_nonvat']}'/>
                                <input type='hidden' name='items[$itemRow][vat_nonvat]' class='vat main-amount' value='{$val['vat_nonvat']}'/>
                        </td>
                        <td> 
                            <input type='text'  class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='{$val['ewt_goods_services']}'/>
                            <input  type='hidden' name='items[$itemRow][ewt_goods_services]' class='ewt main-amount' value='{$val['ewt_goods_services']}'/>
                        </td>
                        <td>
                            <input type='text'  class='form-control mask-amount'onkeyup='UpdateMainAmount(this)'  value='{$val['compensation']}'/>
                            <input  type='hidden' name='items[$itemRow][compensation]' class='compensation main-amount' value='{$val['compensation']}'/>
                        </td>
                            <td> 
                                <input type='text'  class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='{$val['other_trust_liabilities']}'/>
                                <input  type='hidden' name='items[$itemRow][other_trust_liabilities]' class='liabilities main-amount' value='{$val['other_trust_liabilities']}'/>
                            </td>
                     
                        </tr>";
                $itemRow++;
            }
            ?>
        </tbody>
        <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th>Total</th>
            <th>
                <span id="total_disbursed"><?= number_format($ttl_amount_disbursed, 2) ?></span>
            </th>
            <th>
                <span id="total_vat"><?= number_format($ttl_vat_nonvat, 2) ?></span>
            </th>
            <th>
                <span id="total_ewt"><?= number_format($ttl_ewt_goods_services, 2) ?></span>
            </th>
            <th>
                <span id="total_compensation"><?= number_format($ttl_compensation, 2) ?></span>
            </th>
            <th>
                <span id="total_liabilities"><?= number_format($ttl_other_trust_liabilities, 2) ?></span>
            </th>

        </tfoot>

    </table>
    <table class="table" id="entries_table">
        <thead>
            <tr>
                <td colspan="4" style="padding: 3em;">
                    <a class="btn btn-primary insert_entry" onclick="addEntry()" type="button" style="float: right;">Insert Entry</a>
                </td>
            </tr>

            <th>Object Code / Account Title</th>
            <th>Debit</th>
            <th>Credit</th>
        </thead>
        <tbody>
            <?php

            foreach ($accItems as $itm) {



                echo "<tr>
                        <td>
                        <input type='text' name='dvAccItems[$accounting_entry_row][acc_entry_id]' class='' value='{$itm['acc_entry_id']}'>
                        <select required name='dvAccItems[$accounting_entry_row][object_code]' class='object-codes form-control' style='width: 100%'>
                            <option value='{$itm['object_code']}'>{$itm['object_code']}-{$itm['account_title']}</option>
                        </select>
                    </td>
                    <td>
                        <input type='text' class='mask-amount form-control' placeholder='Debit' onkeyup='UpdateMainAmount(this)' value='{$itm['debit']}'>
                        <input type='hidden' name='dvAccItems[$accounting_entry_row][debit]' class='debit main-amount' value='{$itm['debit']}'>
                    </td>
                    <td>
                        <input type='text' class='mask-amount form-control' placeholder='Credit' onkeyup='UpdateMainAmount(this)' value='{$itm['credit']}'>
                        <input type='hidden' name='dvAccItems[$accounting_entry_row][credit]' class='credit main-amount' value='{$itm['credit']}'>
                    </td>
                    <td style='float:right;'>
                        <a class='add_accounting_entry_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                        <a class='remove_this_accounting_entry_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                    </td>
                </tr>";
                $accounting_entry_row++;
            }
            ?>
        </tbody>
    </table>
    <table id="advances_table" style=" margin-top:3rem;" class="table">
        <thead>


            <tr>
                <th colspan="4">
                    <hr>
                </th>
            </tr>
            <tr class="info">

                <th colspan="4" style="text-align: center;">
                    <h4 style="font-weight:bold;">ADVANCES</h4>
                </th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    <div class="row">

                        <?php
                        $advances_province = '';
                        $advances_parent_reporting_period = '';
                        $advances_id = '';
                        $bank_account_id = '';

                        if (!empty($model->id)) {
                            $advances_data = Yii::$app->db->createCommand("SELECT advances.province,advances.id,advances.reporting_period,
                                    advances.bank_account_id
                                                   FROM advances
                                                   WHERE advances.dv_aucs_id = :dv_id")
                                ->bindValue(":dv_id", $model->id)
                                ->queryOne();
                            if (!empty($advances_data)) {
                                $advances_province = $advances_data['province'];
                                $advances_parent_reporting_period = $advances_data['reporting_period'];
                                $advances_id = $advances_data['id'];
                                $bank_account_id = $advances_data['bank_account_id'];
                            }
                        }
                        ?>
                        <input type="hidden" value='<?= $advances_id ?>' name="advances_id">

                        <div class="col-sm-3">
                            <label for="advances_bank_account">Bank Account</label>
                            <?php
                            $bank_accounts_query = (new \yii\db\Query())
                                ->select(['bank_account.id', "CONCAT(bank_account.account_number,'-',bank_account.province,'-',bank_account.account_name) as account_number"])
                                ->from('bank_account');

                            $bank_accounts = $bank_accounts_query->all();
                            echo Select2::widget([
                                'data' => ArrayHelper::map($bank_accounts, 'id', 'account_number'),
                                'name' => 'advances[bank_account_id]',
                                'value' => $advancesModel->bank_account_id,
                                'pluginOptions' => [
                                    'placeholder' => 'Select Bank Account'
                                ]

                            ]);
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <label for="advances_parent_reporting_period">Reporting Period</label>
                            <?php
                            echo DatePicker::widget([
                                'name' => 'advances[reporting_period]',
                                'value' => $advancesModel->reporting_period,
                                'pluginOptions' => [
                                    'startView' => 'months',
                                    'minViewMode' => 'months',
                                    'format' => 'yyyy-mm',
                                    'autoclose' => true
                                ]
                            ])
                            ?>
                        </div>
                        <div class="col-sm-1 col-sm-offset-5">

                            <button class="add-adv-item btn btn-success" type="button">Add Item</button>
                        </div>
                    </div>
                </td>

            </tr>
            <?php
            foreach ($advancesItems as $advItm) {
                echo "<tr>
                 <td>
                     <div class='row'>
                         <div class='col-sm-4'>
                             <label>Reporting Period
                                 <input  value='{$advItm['id']}' type='hidden' name='advancesItems[$advancesItemRow][item_id]' />
                                 <input  value='{$advItm['reporting_period']}' type='month' name='advancesItems[$advancesItemRow][reporting_period]' class='advances_reporting_period' style='width: 100%;min-width:100%; max-width:100%'  />
                             </label>
                         </div>
                         <div class='col-sm-4'>
                             <label>Report Type
                                 <select name='advancesItems[$advancesItemRow][report_type_id]' class='advances-report-type-select' style='width: 100%'>
                                     <option value='{$advItm['fk_advances_report_type_id']}'>{$advItm['report_type']}</option>
                                 </select>
                             </label>
                         </div>
                         <div class='col-sm-4'>
                             <label>Fund Source Type</label>
 
                             <select name='advancesItems[$advancesItemRow][fund_source_type_id]' class='fund-source-type-select' style='width: 100%;min-width:100%; max-width:100%'>
                                <option value='{$advItm['fk_fund_source_type_id']}'>{$advItm['fund_source_type_name']}</option>
                             </select>
                         </div>
                     </div>
                     <div class='row'>
                         <div class='col-sm-4'>
                             <label>Fund Source
                                 <textarea name='advancesItems[$advancesItemRow][fund_source]' class='advances_fund_source' cols='10' rows='2' style='width: 100%;min-width:100%; max-width:100%'>{$advItm['fund_source']}</textarea>
                             </label>
                         </div>
                         <div class='col-sm-4'>
                             <label> Sub Account
                                 <select name='advancesItems[$advancesItemRow][advances_object_code]' class='chart-of-accounts' style='width: 100%'>
                                    <option value='{$advItm['object_code']}'>{$advItm['object_code']}-{$advItm['account_title']}</option>
                                 </select>
                             </label>
                         </div>
                         <div class='col-sm-4'>
                             <label> Amount
                                 <input type='text' class='form-control mask-amount advances_amount'  value='{$advItm['amount']}'>
                                 <input type='hidden' name='advancesItems[$advancesItemRow][amount]' class='advances_unmask_amount main-amount' value='{$advItm['amount']}'>
                             </label>
                         </div>
                     </div>
                 </td>
                 <td style='  text-align: center;width:100px'>
                     <div class='row pull-center'>
                         <a class='add-adv-item btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                         <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                     </div>
 
                 </td>
             </tr>";
                $advancesItemRow++;
            }
            ?>

            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
        </tbody>

    </table>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    label {
        display: inherit;
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let accounting_entry_row = <?= $accounting_entry_row ?>;
    let advancesItemRow = <?= $advancesItemRow ?>;
    let reportTypes = []
    let fundSourceTypes = []

    function addEntry() {
        const new_row = `<tr>
             
                <td>
                    <select required name="dvAccItems[${accounting_entry_row}][object_code]" class="object-codes form-control" style="width: 100%">
                    </select>
                </td>
                <td>
                    <input type="text" class="mask-amount form-control" placeholder="Debit" onkeyup='UpdateMainAmount(this)' >
                    <input type="hidden" name="dvAccItems[${accounting_entry_row}][debit]" class="debit main-amount" >
                </td>
                <td>
                    <input type="text" class="mask-amount form-control" placeholder="Credit" onkeyup='UpdateMainAmount(this)'>
                    <input type="hidden" name="dvAccItems[${accounting_entry_row}][credit]" class="credit main-amount" >
                </td>
                <td style='float:right;'>
                    <a class='add_accounting_entry_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                    <a class='remove_this_accounting_entry_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                </td>
            </tr>`;
        $('#entries_table tbody').append(new_row)
        maskAmount()
        ObjectCodesSelect()
        accounting_entry_row++;
    }

    async function GetAdvancesReportTypes() {
        await $.getJSON(window.location.pathname + '?r=advances-report-types/get-report-types')
            .then(function(data) {
                reportTypes = data
            })
        AdvancesReportTypeSelect()
    }

    async function GetFundSourceTypes() {
        await $.getJSON(window.location.pathname + '?r=fund-source-type/get-fund-source-types')
            .then(function(data) {
                fundSourceTypes = data
            })
        FundSourceTypeSelect()

    }

    function FundSourceTypeSelect() {

        $('.fund-source-type-select').select2({
            data: fundSourceTypes,
            placeholder: 'Select Fund Source Type'

        });
    }

    function AdvancesReportTypeSelect() {

        $('.advances-report-type-select').select2({
            data: reportTypes,
            placeholder: 'Select Reporty Type'

        });
    }

    function addAdvancesItem() {

    }

    function updateMainAmt(ths) {
        const amt = $(ths).maskMoney("unmasked")[0];
        $(ths).closest('tr').find('.main-amount').val(amt)
    }
    $(document).ready(() => {

        GetAdvancesReportTypes()
        GetFundSourceTypes()
        accountingCodesSelect()
        ObjectCodesSelect()
        maskAmount()
        $('#dvaucs-nature_of_transaction_id ').change(function() {
            var nature_selected = $(this).children(':selected').text()
            if (nature_selected == 'CA to SDOs/OPEX') {
                $('#advances_table').show()
            } else if (nature_selected == 'CA to Employees') {
                $('#dv_object_code').show()
                $('#advances_table').hide()
            } else {
                $('#advances_table').hide()
                $('#dv_object_code').hide()
            }
        })
        $('.mask-amount').on('keyup', function() {
            const amt = $(this).maskMoney("unmasked")[0];
            $(this).closest('tr').find('.main-amount').val(amt)
        })
        $('.add-adv-item').on('click', function() {
            let r = `<tr>
                <td>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Reporting Period
                                <input type='month' name='advancesItems[${advancesItemRow}][reporting_period]' class="advances_reporting_period" style="width: 100%;min-width:100%; max-width:100%" />
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label>Report Type
                                <select name="advancesItems[${advancesItemRow}][report_type_id]" class="advances-report-type-select" style="width: 100%">
                                    <option></option>
                                </select>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label>Fund Source Type</label>

                            <select name="advancesItems[${advancesItemRow}][fund_source_type_id]" class="fund-source-type-select" style="width: 100%;min-width:100%; max-width:100%">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Fund Source
                                <textarea name="advancesItems[${advancesItemRow}][fund_source]" class="advances_fund_source" cols="10" rows="2" style="width: 100%;min-width:100%; max-width:100%"></textarea>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label> Sub Account
                                <select name="advancesItems[${advancesItemRow}][advances_object_code]" class="chart-of-accounts" style="width: 100%">
                                    <option></option>
                                </select>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label> Amount
                                <input type="text" class="form-control mask-amount advances_amount"  onkeyup='updateMainAmt(this)'>
                                <input type="hidden" name="advancesItems[${advancesItemRow}][amount]" class="advances_unmask_amount main-amount">
                            </label>
                        </div>
                    </div>
                </td>
                <td style='  text-align: center;width:100px'>
                    <div class="row pull-right">
                        <a class='add-adv-item btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                        <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                    </div>

                </td>
            </tr>`
            $('#advances_table tbody').append(r)
            GetAdvancesReportTypes()
            GetFundSourceTypes()
            accountingCodesSelect()
            maskAmount()
            advancesItemRow++
        })
        $('.remove_this_row').click(function() {
            $(this).closest('tr').remove()
        })
    })
</script>