<?php

use app\models\Books;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MrdClassification;
use app\models\NatureOfTransaction;
use app\models\Office;
use aryelds\sweetalert\SweetAlertAsset;

$row = 1;
$advances_entries_row = 1;
$accounting_entry_row  = 1;
$dv_items_row = 1;
$dv_object_code = [];

if (!empty($model->object_code)) {

    $q = Yii::$app->db->createCommand("SELECT object_code,CONCAT(object_code,'-',account_title) as account_title FROM accounting_codes WHERE object_code =:object_code")
        ->bindValue(':object_code', $model->object_code)
        ->queryAll();
    $dv_object_code = ArrayHelper::map($q, 'object_code', 'account_title');
}

?>
<div class="test d-none" id="mainVue">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="card p-2">

        <div class="row">

            <div class="col-sm-3">
                <?= $form->field($model, 'reporting_period')->widget(
                    DatePicker::class,
                    [
                        'options' => ['required' => true],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView' => "year",
                            'minViewMode' => "months",
                        ]
                    ]
                );
                ?>
            </div>



            <div class="col-sm-3">
                <?= $form->field($model, 'nature_of_transaction_id')->dropDownList(
                    ArrayHelper::map(NatureOfTransaction::find()->asArray()->all(), 'id', 'name'),
                    [
                        'prompt' => 'Select Nature of Transaction',
                    ]
                )
                ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'mrd_classification_id')->dropDownList(
                    ArrayHelper::map(MrdClassification::find()->asArray()->all(), 'id', 'name'),
                    [
                        'prompt' => 'Select Nature of Transaction',
                    ]
                )
                ?>

            </div>

            <div class="col-sm-3" id="dv_object_code">

                <?= $form->field($model, 'object_code')->widget(Select2::class, [
                    'data' => $dv_object_code,
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

                ])

                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'payee_id')->widget(
                    Select2::class,
                    [
                        'name' => 'payee',
                        'data' => $payee,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=payee/search-payee',
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],

                    ]
                )

                ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'transaction_type')->widget(Select2::class, [
                    'data' => [
                        "single"  => "Single",
                        "multiple" => "Multiple",
                        "accounts payable"  => "Accounts Payable",
                        "replacement to stale checks" =>  "Replacement to Stale Checks",
                        'replacement of check issued' =>  'Replacement of Check Issued',
                        'payroll' =>  'Payroll',
                        'remittance' =>  'Remittance',
                    ],
                    'pluginOptions' => [
                        'placeholder' => 'Select Transaction Type'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'book_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'value' => $book,
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'particular')->textarea(['rows' => 3]) ?>
            </div>
        </div>

    </div>
    <div class="card p-3">

        <table id="dv_items_table" class="table table-hover">
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
                <th>Liquidation Damages</th>
                <th>Tax Portion of Pos</th>
            </thead>
            <tbody>

                <?php
                $total_disbursed = 0;
                $total_vat = 0;
                $total_ewt = 0;
                $total_compensation = 0;
                $total_liabilities = 0;


                ?>
                <tr v-for="(item,idx) in dvItems">
                    <td class="d-none"> <input type="hidden" :name="'dvItems['+idx+'][id]'" v-model="item.id"></td>
                    <td> {{item.serial_number}}</td>
                    <td>
                        {{item.particular}}
                    </td>
                    <td> {{item.payee}}</td>

                    <td> </td>
                    <td style="width: 300px;">
                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.amount_disbursed' @keyup="changeMainAmount($event,item,'amount_disbursed')" />
                        <input type="hidden" :name="'dvItems['+idx+'][amount_disbursed]'" class="main-amount" v-model="item.amount_disbursed">
                    </td>
                    <td style="width: 300px;">
                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.vat_nonvat' @keyup="changeMainAmount($event,item,'vat_nonvat')" />
                        <input type="hidden" :name="'dvItems['+idx+'][vat_nonvat]'" class="main-amount" v-model="item.vat_nonvat">
                    </td>
                    <td style="width: 300px;">
                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.ewt_goods_services' @keyup="changeMainAmount($event,item,'ewt_goods_services')" />
                        <input type="hidden" :name="'dvItems['+idx+'][ewt_goods_services]'" class="main-amount" v-model="item.ewt_goods_services">
                    </td>
                    <td style="width: 300px;">
                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.compensation' @keyup="changeMainAmount($event,item,'compensation')" />
                        <input type="hidden" :name="'dvItems['+idx+'][compensation]'" class="main-amount" v-model="item.compensation">
                    </td>
                    <td style="width: 300px;">
                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.other_trust_liabilities' @keyup="changeMainAmount($event,item,'other_trust_liabilities')" />
                        <input type="hidden" :name="'dvItems['+idx+'][other_trust_liabilities]'" class="main-amount" v-model="item.other_trust_liabilities">
                    </td>
                    <td style="width: 300px;">
                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.liquidation_damage' @keyup="changeMainAmount($event,item,'liquidation_damage')" />
                        <input type="hidden" :name="'dvItems['+idx+'][liquidation_damage]'" class="main-amount" v-model="item.liquidation_damage">
                    </td>
                    <td style="width: 300px;">
                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.tax_portion_of_post' @keyup="changeMainAmount($event,item,'tax_portion_of_post')" />
                        <input type="hidden" :name="'dvItems['+idx+'][tax_portion_of_post]'" class="main-amount" v-model="item.tax_portion_of_post">
                    </td>

                    <!-- <td><button class='btn-xs btn-danger ' onclick='remove(this)'><i class='fa fa-times'></i></button></td> -->

                </tr>
            </tbody>
            <tfoot>
                <th colspan="4" class="text-center">Total</th>
                <th class="text-center">{{dvItemsTotal('amount_disbursed')}}</th>
                <th class="text-center">{{dvItemsTotal('vat_nonvat')}}</th>
                <th class="text-center">{{dvItemsTotal('ewt_goods_services')}}</th>
                <th class="text-center">{{dvItemsTotal('compensation')}}</th>
                <th class="text-center">{{dvItemsTotal('other_trust_liabilities')}}</th>
                <th class="text-center">{{dvItemsTotal('liquidation_damage')}}</th>
                <th class="text-center">{{dvItemsTotal('tax_portion_of_post')}}</th>


            </tfoot>
        </table>
    </div>
    <div class="card p-2">

        <table class="table table-hover">
            <thead>

                <tr class="table-info">
                    <th class="text-center" colspan="12">ORS Breakdown</th>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-success" type="button" @click="generateOrsBreakdown">Generate</button>
                    </td>
                </tr>
                <tr>
                    <th class="text-center">Ors Number</th>
                    <th class="text-center">Object Code</th>
                    <th class="text-center">Account Title</th>
                    <th class="text-center">Balance ORS Amount</th>
                    <th class="text-center">Amount Disbursed</th>
                    <th class="text-center">2306 (VAT / Non-Vat)</th>
                    <th class="text-center">2307 (EWT Goods / Services)</th>
                    <th class="text-center">1601C (Compensation)</th>
                    <th class="text-center">Tax Withheld</th>
                    <th class="text-center">Other Trust Liabilities</th>
                    <th class="text-center">Liquidation Damage</th>
                    <th class="text-center">Tax Portion of Pos</th>
                </tr>
            </thead>

            <tr v-for="(orsBreakdown,idx) in orsBreakdowns">
                <td class="d-none">
                    <input type="hidden" v-if="orsBreakdown.id" v-model="orsBreakdown.id" :name="'orsBreakdownItems['+idx+'][id]'">
                    <input type="hidden" v-if="orsBreakdown.ors_entry_id" v-model="orsBreakdown.ors_entry_id" :name="'orsBreakdownItems['+idx+'][fk_process_ors_entry_id]'">
                </td>
                <td>{{orsBreakdown.ors_number}}</td>
                <td>{{orsBreakdown.uacs}}</td>
                <td>{{orsBreakdown.general_ledger}}</td>
                <td>{{formatAmount(orsBreakdown.balance)}}</td>
                <td class="text-right">
                    <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='orsBreakdown.amount_disbursed' @keyup="changeMainAmount($event,orsBreakdown,'amount_disbursed')" />
                    <input type="hidden" :name="'orsBreakdownItems['+idx+'][amount_disbursed]'" class="main-amount" v-model="orsBreakdown.amount_disbursed">
                </td>
                <td class="text-right">
                    <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='orsBreakdown.vat_nonvat' @keyup="changeMainAmount($event,orsBreakdown,'vat_nonvat')" />
                    <input type="hidden" :name="'orsBreakdownItems['+idx+'][vat_nonvat]'" class="main-amount" v-model="orsBreakdown.vat_nonvat">
                </td>
                <td class="text-right">
                    <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='orsBreakdown.ewt_goods_services' @keyup="changeMainAmount($event,orsBreakdown,'ewt_goods_services')" />
                    <input type="hidden" :name="'orsBreakdownItems['+idx+'][ewt_goods_services]'" class="main-amount" v-model="orsBreakdown.ewt_goods_services">

                </td>
                <td class="text-right">
                    <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='orsBreakdown.compensation' @keyup="changeMainAmount($event,orsBreakdown,'compensation')" />
                    <input type="hidden" :name="'orsBreakdownItems['+idx+'][compensation]'" class="main-amount" v-model="orsBreakdown.compensation">
                </td>
                <td class="text-center">{{formatAmount(parseFloat(orsBreakdown.vat_nonvat) +parseFloat(orsBreakdown.ewt_goods_services)+parseFloat(orsBreakdown.compensation)) }}</td>
                <td class="text-right">
                    <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='orsBreakdown.other_trust_liabilities' @keyup="changeMainAmount($event,orsBreakdown,'other_trust_liabilities')" />
                    <input type="hidden" :name="'orsBreakdownItems['+idx+'][other_trust_liabilities]'" class="main-amount" v-model="orsBreakdown.other_trust_liabilities">
                </td>
                <td class="text-right">
                    <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='orsBreakdown.liquidation_damage' @keyup="changeMainAmount($event,orsBreakdown,'liquidation_damage')" />
                    <input type="hidden" :name="'orsBreakdownItems['+idx+'][liquidation_damage]'" class="main-amount" v-model="orsBreakdown.liquidation_damage">
                </td>
                <td class="text-right">
                    <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='orsBreakdown.tax_portion_of_post' @keyup="changeMainAmount($event,orsBreakdown,'tax_portion_of_post')" />
                    <input type="hidden" :name="'orsBreakdownItems['+idx+'][tax_portion_of_post]'" class="main-amount" v-model="orsBreakdown.tax_portion_of_post">
                </td>
            </tr>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-center">Total</th>
                    <th class="text-center">{{orsBreakdownTotal('amount_disbursed')}}</th>
                    <th class="text-center">{{orsBreakdownTotal('vat_nonvat')}}</th>
                    <th class="text-center">{{orsBreakdownTotal('ewt_goods_services')}}</th>
                    <th class="text-center">{{orsBreakdownTotal('compensation')}}</th>
                    <th class="text-center">{{orsBreakdownTotal('total_tax_withheld')}}</th>

                    <th class="text-center">{{orsBreakdownTotal('other_trust_liabilities')}}</th>
                    <th class="text-center">{{orsBreakdownTotal('liquidation_damage')}}</th>
                    <th class="text-center">{{orsBreakdownTotal('tax_portion_of_post')}}</th>
                </tr>
            </tfoot>
        </table>

    </div>
    <div class="card p-2">
        <table class="table table-hover">
            <thead>

                <tr class="table-info">
                    <th colspan="5" class="text-center">Accounting Entry</th>
                </tr>
                <tr>
                    <th colspan="5">
                        <button class="btn btn-primary " type="button" style="float: right;" @click="addEntry">
                            <i class="fa fa-plus"></i> Insert Entry</button>
                    </th>
                </tr>
                <tr>
                    <th for='chart_of_account'> Chart of Account</th>
                    <th for='isCurrent'>Current/NonCurrent </th>
                    <th for='chart_of_account'> Debit</th>
                    <th for='chart_of_account'> Credit</th>
                    <th for='chart_of_account'> </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(item,idx) in accountingEntries">
                    <td class="d-none">
                        <input type='text' v-model='item.id' :name="'dvAccountingEntries['+idx+'][id]'" v-if="item.id">
                    </td>
                    <td class="w-25">

                        <select :name="'dvAccountingEntries[' + idx + '][object_code]'" class="form-control chart-of-accounts" style="width: 100%;">
                            <option disabled selected v-if="!item.object_code">Select Chart of Account</option>
                            <option selected v-if="item.object_code" :value='item.object_code'>{{item.account_title}}</option>
                        </select>
                    </td>
                    <td>
                        <input type='text' :name="'dvAccountingEntries['+idx+'][current_noncurrent]'" placeholder='Current/NonCurrent' />
                    </td>
                    <td style="width: 300px;">

                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.debit' @keyup="changeMainAmount($event,item,'debit')" />
                        <input type="hidden" :name="'dvAccountingEntries['+idx+'][debit]'" class="main-amount" v-model="item.debit">
                    </td>
                    <td style="width: 300px;">

                        <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.credit' @keyup="changeMainAmount($event,item,'credit')" />
                        <input type="hidden" :name="'dvAccountingEntries['+idx+'][credit]'" class="main-amount" v-model="item.credit">
                    </td>
                    <td style='float:right;'>
                        <a class='  btn-primary btn-xs' type='button' @click="addEntry"><i class='fa fa-plus fa-fw'></i> </a>
                        <a class=' btn-danger btn-xs ' type='button' @click="removeAccountingEntry(idx)"><i class='fa fa-times fa-fw'></i> </a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center" colspan="2">Total</th>
                    <th>
                        <span>Debit:</span>
                        <br>
                        <span>{{formatAmount(totalDebit)}}</span>
                    </th>
                    <th>
                        <span>
                            Credit:
                        </span>
                        <br>
                        <span>{{formatAmount(totalCredit)}}</span>
                    </th>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php

    ?>
    <div class="card p-2" id="advances_table">
        <table class="table">
            <thead>
                <tr class="table-info">

                    <th colspan="4" class="text-center">
                        Advances
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td class="card p-2">
                        <div class="row">
                            <?php
                            $advances_province = '';
                            $advances_parent_reporting_period = '';
                            $advances_id = '';
                            $bank_account_id = '';

                            if (!empty($model->id)) {
                                $advances_data = Yii::$app->db->createCommand("SELECT 
                                advances.province,
                                advances.id,
                                advances.reporting_period,
                                    advances.bank_account_id,
                                    fk_office_id
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
                            $advances = $model->advances;
                            ?>
                            <input type="hidden" value='<?= $advances['advances']['id'] ?? '' ?>' name="advances[id]">
                            <div class="col-sm-3">
                                <label for="report"> Province</label>
                                <?= Select2::widget([
                                    'data' => ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                                    'name' => 'advances[fk_office_id]',
                                    'value' => $advances['advances']['fk_office_id'] ?? null,
                                    'pluginOptions' => [
                                        'placeholder' => 'Select Province'
                                    ],
                                    'options' => []
                                ])
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <label for="advances_parent_reporting_period">Reporting Period</label>
                                <?= DatePicker::widget([
                                    'name' => 'advances[reporting_period]',
                                    'value' => $advances['advances']['reporting_period'] ?? null,
                                    'pluginOptions' => [
                                        'startView' => 'months',
                                        'minViewMode' => 'months',
                                        'format' => 'yyyy-mm',
                                        'autoclose' => true
                                    ]
                                ])
                                ?>
                            </div>
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
                                    'value' =>  $advances['advances']['bank_account_id'] ?? null,
                                    'pluginOptions' => [
                                        'placeholder' => 'Select Bank Account'
                                    ]

                                ]);
                                ?>
                            </div>
                            <div class="col-sm-3 text-right">
                                <button class='btn-primary btn-xs' type='button' @click="addAdvancesItem"><i class='fa fa-plus '></i> Add</button>
                            </div>
                        </div>
                    </td>


                </tr>
                <tr v-for="(item,idx) in advancesItems">
                    <td class="card p-1">
                        <input type='hidden' :name="'advancesItems['+idx+'][id]'" v-if="item.id" v-model="item.id" />

                        <div class="row">
                            <div class="col-sm-3">
                                <label for="advances_reporting_period">Reporting Period</label>
                                <input type='month' :name="'advancesItems['+idx+'][reporting_period]'" v-model="item.reporting_period" />
                            </div>
                            <div class="col-sm-4">
                                <label for="advances_report_type">Report Type</label>
                                <select :name="'advancesItems['+idx+'][fk_advances_report_type_id]'" v-model="item.fk_advances_report_type_id" class="form-control">
                                    <option value=""> Select Report Type</option>
                                    <option v-for=" reportType in advancesReportTypes" :value="reportType.id">{{reportType.name}}</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="advances_fund_source_type">Fund Source Type</label>
                                <select :name="'advancesItems['+idx+'][fk_fund_source_type_id]'" class="advances_fund_source_type form-control">
                                    <option disabled selected v-if="!item.fk_fund_source_type_id">Select Fund Source Type</option>
                                </select>
                            </div>
                            <div class="col-sm-1 text-right">
                                <p>

                                    <a class='  btn-primary btn-xs' type='button' @click="addAdvancesItem"><i class='fa fa-plus fa-fw'></i> </a>
                                    <a class=' btn-danger btn-xs ' type='button' @click="removeAdvancesItem(idx)"><i class='fa fa-times fa-fw'></i> </a>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="fund_source">Fund Source</label>
                                <textarea v-model="item.fund_source" :name="'advancesItems['+idx+'][fund_source]'" class="fund_source" cols="10" rows="2"></textarea>
                            </div>
                            <div class="col-sm-4">
                                <label for="advances_object-code">Sub Account</label>
                                <select :name="'advancesItems['+idx+'][object_code]'" class="chart-of-accounts" style="width: 100%">
                                    <option v-if="item.object_code" :value="item.object_code">{{item.account_title}}</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="advances_amount">Amount</label>

                                <input type="text" class="amt mask-money form-control" v-money="moneyConfig" :value='item.amount' @keyup="changeMainAmount($event,item,'amount')" />
                                <input type="hidden" :name="'advancesItems['+idx+'][amount]'" class="main-amount" v-model="item.amount">

                            </div>
                        </div>
                    </td>

                </tr>
            </tbody>

        </table>
    </div>
    <div class="row justify-content-center">
        <div class="form-group col-sm-2">
            <button type="submit" class="btn btn-success" style="width: 100%;" id="save" name="save"> SAVE</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<style>
    #advances_table {
        display: none;
    }

    .debit,
    .credit,
    .amount_disbursed,
    .vat,
    .ewt,
    .compensation,
    .liabilities {
        display: none;
    }

    .form-error {
        color: red;
    }

    #accountng_entry_table {
        width: 100%;
    }

    #advances_table td {
        padding: 1rem;
    }

    #advances_table {
        width: 100%;
    }

    textarea {
        max-width: 100%;
        width: 100%;
    }

    .grid-view td {
        white-space: normal;
    }

    .select {
        width: 500px;
        height: 2rem;
    }

    #submit {
        margin: 10px;
    }

    input {
        width: 100%;
        font-size: 15px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid black;

    }

    .row {
        margin: 5px;
    }

    .container {
        background-color: white;
        height: auto;
        padding: 10px;
        border-radius: 2px;
    }

    .accounting_entries {
        background-color: white;
        padding: 2rem;
        border: 1px solid black;
        border-radius: 5px;
    }

    .swal-text {
        background-color: #FEFAE3;
        padding: 17px;
        border: 1px solid #F0E1A1;
        display: block;
        margin: 22px;
        text-align: center;
        color: #61534e;
    }
</style>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => \yii\web\JqueryAsset::class]);
$this->registerJsFile("@web/js/select2.min.js", ['depends' => \yii\web\JqueryAsset::class]);
$this->registerJsFile("@web/js/v-money.min.js");
$csrfToken = Yii::$app->request->csrfToken;
SweetAlertAsset::register($this);

// !empty($advances['advancesItems']) ? json_encode($advances['advancesItems']) : 
?>

<script>
    let fund_source_type = []
    $(document).ready(function() {
        getFundSourceType().then((data) => {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.id,
                    text: val.name
                })
            })
            fund_source_type = array
            advancesFundSourceTypeSelect()
        })
        $('#mainVue').removeClass('d-none')
        new Vue({
            el: "#mainVue",
            data: {
                dvItems: <?= json_encode($model->dvItems) ?>,
                moneyConfig: {
                    precision: 2, // Number of decimal places
                    prefix: '₱ ', // Currency symbol
                    thousands: ',', // Thousands separator
                    decimal: '.', // Decimal separator,
                },
                accountingEntries: <?= !empty($model->accountingEntries) ? json_encode($model->accountingEntries) : json_encode([]) ?>,
                advancesItems: <?= !empty($advances['advancesItems']) ? json_encode($advances['advancesItems']) : json_encode([]) ?>,
                advancesReportTypes: [],
                fundSourceTypes: [],
                orsBreakdowns: <?= !empty($model->getBreakdownItems()) ? json_encode($model->getBreakdownItems()) : json_encode([]) ?>
            },
            mounted() {
                this.getAdvancesReportTypes()
            },
            updated() {
                accountingCodesSelect()
                maskAmount()
                advancesFundSourceTypeSelect()
            },
            methods: {

                getAdvancesReportTypes() {
                    axios.get(window.location.pathname + '?r=report-type/get-report-type')
                        .then(res => {
                            this.advancesReportTypes = res.data
                            console.log(this.advancesReportTypes)
                        })
                },
                changeMainAmount(event, item, attribute) {
                    item[attribute] = parseFloat($(event.target).maskMoney('unmasked')[0]).toFixed(2)
                },
                addEntry() {
                    this.accountingEntries.push({
                        current_noncurrent: '',
                        object_code: '',
                        debit: 0,
                        credit: 0
                    })
                },
                removeAccountingEntry(index) {
                    this.accountingEntries.splice(index, 1)
                },
                formatAmount(unitCost) {
                    unitCost = parseFloat(unitCost)
                    if (typeof unitCost === 'number' && !isNaN(unitCost)) {
                        return unitCost.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If unitCost is not a number, return it as is
                },
                addAdvancesItem() {
                    this.advancesItems.push({
                        reporting_period: '',
                        fk_advances_report_type_id: '',
                        fk_fund_source_type_id: '',
                        fund_source: '',
                        object_code: '',
                        amount: 0,

                    })
                },
                removeAdvancesItem(index) {
                    this.advancesItems.splice(index, 1)
                },
                generateOrsBreakdown() {

                    let url = "?r=dv-aucs/generate-ors-breakdown"
                    let data = {
                        _csrf: '<?= YIi::$app->request->getCsrfToken() ?>',
                        id: <?= $model->id ?>
                    }
                    axios.post(url, data)
                        .then(res => {
                            this.orsBreakdowns = res.data
                        })
                        .catch(error => {

                            console.log(error)
                        });
                },
                dvItemsTotal(attrib) {
                    const total = this.dvItems.reduce((total, item) => total + parseFloat(item[attrib]), 0);
                    return this.formatAmount(total)
                },
                orsBreakdownTotal(attrib) {
                    const total = attrib == 'total_tax_withheld' ? this.orsBreakdowns.reduce((total, item) => (total + parseFloat(item.vat_nonvat) + parseFloat(item.ewt_goods_services) + parseFloat(item.compensation)), 0) : this.orsBreakdowns.reduce((total, item) => total + parseFloat(item[attrib]), 0);
                    return this.formatAmount(total)
                }

            },
            computed: {

                totalDebit() {
                    return this.accountingEntries.reduce((total, item) => total + parseFloat(item.debit), 0);
                },
                totalCredit() {
                    return this.accountingEntries.reduce((total, item) => total + parseFloat(item.credit), 0);
                }
            }
        })
    })



    function advancesReportTypeSelect() {
        console.log('qwe')
        $(`.advances_report_type`).select2({
            data: report_types,
            placeholder: "Select Report Type ",

        });
    }

    function advancesFundSourceTypeSelect() {
        $(`.advances_fund_source_type`).select2({
            data: fund_source_type,
            placeholder: "Select Fund Source Type",

        });
    }

    function getAccountingCode(object_code) {
        let return_data = '';
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code&id=' + object_code,
            data: {
                _csrf: "<? Yii::$app->request->csrfToken; ?>"
            },
            success: function(data) {
                const res = JSON.parse(data)
                return_data = data
                // console.log(data)
                return data
            }
        })

    }


    const transaction = [
        "Single",
        "Multiple",
        "Accounts Payable",
        "Replacement to Stale Checks",
        'Replacement of Check Issued'
    ]
    let accounting_entry_row = 0
    let advances_table_counter = 0

    function checkObjectCode(object_code) {
        let obj = ''
        let name = ''
        $('.accounting_entry_object_code').each(function() {

            if ($(this).val() == object_code) {
                name = $(this).attr('name')
                obj = $(this).val()

            }
        })
        return name
    }

    function checkCreditName(object_code = '', account_title = '', name = '') {
        let total_amount = 0;
        $('.due_to_bir').each(function(key, val) {
            total_amount += parseFloat($(this).val())
        })
        if (name == '') {
            insertEntry(object_code, account_title, total_amount, 0, 'form-control')
        } else {
            let index_number = parseInt(name.replace(/[^0-9.]/g, ""));
            $(`[name='credit[${index_number}]']`).val(total_amount.toFixed(2))
            $(`[name='credit[${index_number}]']`).parent().find('.mask-amount').val(total_amount.toFixed(2))
        }
    }

    function getObjectCodeForTheMonth() {

        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=dv-aucs/get-object-code',
            data: {
                reporting_period: $('#dvaucs-reporting_period').val(),
                '_csrf': '<?= $csrfToken ?>'
            },
            success: function(data) {
                const res = JSON.parse(data)
                if (jQuery.isEmptyObject(res)) {
                    swal({
                        icon: 'error',
                        title: 'Error',
                        text: 'No Sub Account for the Reporting Period',
                        type: "error",
                        timer: 10000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                } else {
                    let name = checkObjectCode(res.object_code)
                    checkCreditName(res.object_code, res.account_title, name)
                }

            }
        })
    }

    function addEntryForAmountDisbursed(amount_disbursed) {
        let obj = '';
        let name = '';
        const book = $("#book :selected").text().toLowerCase();
        let base_uacs = '';

        if (book == 'fund 01' || book == 'rapid gop') {
            base_uacs = 1010404000
        } else if (
            book == 'rapid lp' ||
            book == 'rapid gpa'
        ) {
            base_uacs = 1010202024
        } else if (book == 'fund 07') {
            base_uacs = 1010406000
        }
        $('.accounting_entry_object_code').each(function() {

            if ($(this).val() == base_uacs) {
                name = $(this).attr('name')
                obj = $(this).val()
                return;
            }
        })
        let index_number = parseInt(name.replace(/[^0-9.]/g, ""));
        if (obj != '') {
            $(`[name='credit[${index_number}]']`).val(amount_disbursed)
            $(`[name='credit[${index_number}]']`).parent().find('.mask-amount').val(amount_disbursed)
        } else {
            if (base_uacs != '') {

                $.ajax({
                    type: 'POST',
                    url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code&id=' + base_uacs,
                    data: {
                        _csrf: '<?= $csrfToken ?>'
                    },
                    success: function(data) {
                        const res = JSON.parse(data)
                        insertEntry(res.object_code, res.account_title, amount_disbursed, )

                    }
                })
            }



        }
    }
    $(document).ready(function() {
        advances_table_counter = <?= $advances_entries_row ?>;
        accounting_entry_row = <?= $accounting_entry_row ?>;


        $('#accountng_entry_table').on('keyup change', '.mask-credit', function() {
            const debit = $(this)
            const amount = debit.maskMoney('unmasked')[0]
            debit.parent().find('.credit').val(amount)
            getDebitCreditTotal()
        })
        $('#accountng_entry_table').on('keyup change', '.mask-debit', function() {
            const debit = $(this)
            const amount = debit.maskMoney('unmasked')[0]
            debit.parent().find('.debit').val(amount)
            getDebitCreditTotal()
        })
        $('#dv_items_table').trigger('keyup', '.mask_amount_disbursed')
        // add accounting entry on keyup or change sa amount disbursed

        $('#dv_items_table').on('keyup change', '.mask_amount_disbursed', function() {
            const amount_disbursed = $(this).val()
            addEntryForAmountDisbursed(parseFloat(amount_disbursed).toFixed(2))

        })
        let obj_code = '';

        // add accounting entry on keyup or change
        $('#dv_items_table').on('keyup change', '.mask_vat, .mask_ewt, .mask_compensation', function() {
            getObjectCodeForTheMonth()

        })


        $('#dv_items_table').on('keyup change', '.mask_amount_disbursed', function() {
            const debit = $(this)
            const amount = debit.maskMoney('unmasked')[0]
            debit.parent().find('.amount_disbursed').val(amount)
        })
        $('#dv_items_table').on('keyup change', '.mask_vat', function() {
            const debit = $(this)
            const amount = debit.maskMoney('unmasked')[0]
            debit.parent().find('.vat').val(amount)
        })
        $('#dv_items_table').on('keyup change', '.mask_ewt', function() {
            const debit = $(this)
            const amount = debit.maskMoney('unmasked')[0]
            debit.parent().find('.ewt').val(amount)
        })

        $('#dv_items_table').on('keyup change', '.mask_compensation', function() {
            const debit = $(this)
            const amount = debit.maskMoney('unmasked')[0]
            debit.parent().find('.compensation').val(amount)
        })

        $('#dv_items_table').on('keyup change', '.mask_liabilities', function() {
            const debit = $(this)
            const amount = debit.maskMoney('unmasked')[0]
            debit.parent().find('.liabilities').val(amount)
        })
        payeeSelect()
        maskAmount()
        accountingCodesSelect()


        $('.insert_entry').on('click', function(e) {
            e.preventDefault()

            insertEntry()
        })
        $.getJSON(window.location.pathname + '?r=report-type/get-report-type')
            .then(function(data) {
                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.name
                    })
                })
                report_types = array
                advancesReportTypeSelect()
            })




        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();

            $(this).closest('tr').remove();
        });
        $('#accountng_entry_table').on('click', '.remove_this_accounting_entry_row', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
            getDebitCreditTotal()

        });


        $('#accountng_entry_table').on('click', '.add_accounting_entry_row', function(event) {
            event.preventDefault();
            const source = $(this).closest('tr');
            source.find('.chart-of-accounts').select2('destroy')
            const clone = source.clone(true);
            const debit = clone.find('.debit')
            const credit = clone.find('.credit')
            const chart_of_account = clone.find('.chart-of-accounts')
            clone.find('.mask-debit').val('')
            clone.find('.mask-credit').val('')
            chart_of_account.attr('name', `object_code[${accounting_entry_row}]`)
            debit.attr('name', `debit[${accounting_entry_row}]`)
            credit.attr('name', `credit[${accounting_entry_row}]`)
            chart_of_account.val('')
            debit.val('')
            credit.val('')
            $('#accountng_entry_table tbody').append(clone)
            maskAmount()
            accountingCodesSelect()
            accounting_entry_row++;
        });
        // add advances entries row
        $('#advances_table').on('click', '.add_new_row', function(event) {

            source = $(this).closest('tr');
            source.find('.advances_fund_source_type').select2('destroy');
            source.find('.advances_report_type').select2('destroy');
            source.find('.chart-of-accounts').select2('destroy')
            var clone = source.clone(true);
            clone.find('.advances_unmask_amount').val(0)
            clone.find('.advances_amount').val(0)
            clone.find('.advances_unmask_amount').attr('name', `advances_amount[${advances_table_counter}]`)
            clone.find('.debit_amount').val(0)
            clone.find('.advances_reporting_period').val(0)
            clone.find('.advances_reporting_period').attr('name', 'advances_reporting_period[' + advances_table_counter + ']')
            clone.find('.advances_report_type').val(0)
            clone.find('.advances_report_type').attr('name', 'advances_report_type[' + advances_table_counter + ']')
            clone.find('.advances_fund_source_type').val('')
            clone.find('.advances_fund_source_type').attr('name', 'advances_fund_source_type[' + advances_table_counter + ']')
            clone.find('.advances_fund_source').val('')
            clone.find('.advances_fund_source').attr('name', 'advances_fund_source[' + advances_table_counter + ']')
            clone.find('.chart-of-accounts').val('')
            clone.find('.chart-of-accounts').attr('name', 'advances_object_code[' + advances_table_counter + ']')
            $('#advances_table tbody').append(clone);
            var spacer = `<tr><td colspan="2"><hr></td></tr>`;
            $('#advances_table tbody').append(spacer);
            clone.find('.remove_this_row').removeClass('disabled');
            advancesReportTypeSelect()
            // advancesFundSourceTypeSelect()
            maskAmount()
            accountingCodesSelect()
            advancesFundSourceTypeSelect()
            advances_table_counter++



        });

        $('#advances_table').on('change keyup', '.advances_amount', function(event) {
            $(this).parent().find('.advances_unmask_amount').val($(this).maskMoney('unmasked')[0])
        });

        $('#dvaucs-nature_of_transaction_id ').change(function() {

            var nature_selected = $(this).children(':selected').text()
            if (nature_selected == 'CA to SDOs/OPEX') {

                document.getElementById('advances_table').style.display = 'block';
                // $('#advances_table').show()
            } else if (nature_selected == 'CA to Employees') {
                document.getElementById('advances_table').style.display = 'none';
                document.getElementById('dv_object_code').style.display = 'block';
                // $('#dv_object_code').show()
                // $('#advances_table').hide()
            } else {
                document.getElementById('advances_table').style.display = 'none';
                document.getElementById('dv_object_code').style.display = 'none';
                // $('#advances_table').hide()
                // $('#dv_object_code').hide()
            }
        })
        $('#dvaucs-nature_of_transaction_id').trigger('change')



        checkDueToBir()
        $('#reporting_period').change(function() {
            checkDueToBir()
            checkAmountDisbursed()
        })
        $('#book').change(function() {
            checkDueToBir()
            checkAmountDisbursed()
        })
        checkAmountDisbursed()
        getDebitCreditTotal()


    })

    function checkDueToBir() {
        let total_amount = 0;
        $('.due_to_bir').each(function(key, val) {
            total_amount += parseFloat($(this).val())
        })
        if (total_amount != 0) {
            getObjectCodeForTheMonth()
        }
        getDebitCreditTotal()
    }

    function checkAmountDisbursed() {
        let total_amount = 0;
        $('.amount_disbursed').each(function(key, val) {
            total_amount += parseFloat($(this).val())
        })
        if (total_amount != 0) {

            addEntryForAmountDisbursed(parseFloat(total_amount).toFixed(2))
        }
        getDebitCreditTotal()
    }


    function getDebitCreditTotal() {
        var total_credit = 0.00;
        var total_debit = 0.00;
        $(".credit").each(function() {
            total_credit += Number($(this).val());
        })
        $(".debit").each(function() {
            total_debit += Number($(this).val());
        })
        $("#d_total").text(thousands_separators(total_debit))
        $("#c_total").text(thousands_separators(total_credit))
    }
</script>


<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#DvAucs").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            swal({
                icon: 'error',
                title: res.error,
                type: "error",
                timer: 3000,
                closeOnConfirm: false,
                closeOnCancel: false
            })
        },
        error: function (data) {
     
        }
    });
    return false;
});
JS;
$this->registerJs($js);
?>