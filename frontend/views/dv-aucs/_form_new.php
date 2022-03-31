<?php

use app\models\Books;
use app\models\MrdClassification;
use app\models\NatureOfTransaction;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\money\MaskMoney;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

$row = 1;
$advances_entries_row = 1;
$accounting_entry_row  = 1;
$dv_items_row = 1;
?>
<div class="test">
    <div id="container" style="background-color: white;width:90%;margin-left:auto;margin-right:auto">
        <div class="row">
            <div class="col-sm-12" style="color:red;text-align:center">
                <h4 id="link">
                </h4>
            </div>
        </div>
        <form id='save_data' method='POST'>

            <div class="row">

                <div class="col-sm-3">
                    <label for="reporting_period">Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'id' => 'reporting_period',
                        'value' => $reporting_period,
                        'options' => ['required' => true],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView' => "year",
                            'minViewMode' => "months",
                        ]
                    ]);
                    ?>
                </div>



                <div class="col-sm-3" style="height:60x">
                    <label for="nature_of_transaction">Nature of Transaction</label>
                    <?php
                    echo Select2::widget([
                        'id' => 'nature_of_transaction',
                        'name' => 'nature_of_transaction',
                        'data' => ArrayHelper::map(NatureOfTransaction::find()->asArray()->all(), 'id', 'name'),
                        'value' => $nature_of_transaction,
                        'pluginOptions' => [
                            'placeholder' => 'Select Nature of Transaction',

                        ]
                    ])

                    ?>
                    <span class="nature_of_transaction form-error"></span>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="mrd_classification">MRD Classification</label>

                    <?php
                    echo Select2::widget([
                        'name' => 'mrd_classification',
                        'data' => ArrayHelper::map(MrdClassification::find()->asArray()->all(), 'id', 'name'),
                        'value' => $mrd_classification,
                        'pluginOptions' => [
                            'placeholder' => 'Select Nature of Transaction'
                        ]
                    ])

                    ?>
                    <span class="mrd_classification_error form-error"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <?php
                    echo Select2::widget([
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

                    ])

                    ?>
                    <span class="payee_id_error form-error"></span>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="transaction">Transaction Type</label>
                    <?php
                    echo Select2::widget([
                        'id' => 'transaction_type',
                        'name' => 'transaction_type',
                        'data' => [
                            "Single"  => "Single",
                            "Multiple" => "Multiple",
                            "Accounts Payable"  => "Accounts Payable",
                            "Replacement to Stale Checks" =>  "Replacement to Stale Checks",
                            'Replacement of Check Issued' =>  'Replacement of Check Issued',
                        ],
                        'value' => $transaction_type,
                        'pluginOptions' => [
                            'placeholder' => 'Select Transaction Type'
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3" id='bok'>
                    <label for="book">Book</label>
                    <?php
                    echo Select2::widget([
                        'name' => 'book',
                        'id' => 'book',
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
                <label for="particular">Particular</label>
                <textarea name="particular" id="particular" placeholder="PARTICULAR" required rows="3"><?php echo $particular ?></textarea>
            </div>

            <table id="dv_items_table" class="table table-striped">
                <thead>
                    <th>Ors ID</th>
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
                    $total_disbursed = 0;
                    $total_vat = 0;
                    $total_ewt = 0;
                    $total_compensation = 0;
                    $total_liabilities = 0;
                    if (!empty($dv_items)) {

                        foreach ($dv_items  as $val) {
                            echo "<tr>
                            <td > <input style='display:none' value='{$val['process_ors_id']}' type='text' name='process_ors_id[{$dv_items_row}]'/></td>
 
                            <td> {$val['serial_number']}</td>
                            <td> 
                            {$val['particular']}
                            </td>
                            <td> {$val['payee']}</td>
                            <td> </td>
                            <td>
                             <input value='{$val['amount_disbursed']}' type='text' class='mask-amount mask_amount_disbursed'/>
                             <input value='{$val['amount_disbursed']}' type='text' name='amount_disbursed[{$dv_items_row}]' class='amount_disbursed'/>
                            </td>
                            <td>
                            
                            <input value='{$val['vat_nonvat']}' type='text'  class='mask-amount mask_vat'/>
                            <input value='{$val['vat_nonvat']}' type='text' name='vat_nonvat[{$dv_items_row}]' class='vat due_to_bir'/>
                            </td>
                            <td> 
                            <input value='{$val['ewt_goods_services']}' type='text' class='mask-amount mask_ewt'/>
                            <input value='{$val['ewt_goods_services']}' type='text' name='ewt_goods_services[{$dv_items_row}]' class='ewt due_to_bir'/>
                            </td>
                            <td>
                             <input value='{$val['compensation']}' type='text'  class='mask-amount mask_compensation'/>
                             <input value='{$val['compensation']}' type='text' name='compensation[{$dv_items_row}]' class='compensation due_to_bir'/>
                            </td>
                            <td> 
                            <input value='{$val['other_trust_liabilities']}' type='text'  class='mask-amount mask_liabilities'/>
                            <input value='{$val['other_trust_liabilities']}' type='text' name='other_trust_liabilities[{$dv_items_row}]' class='liabilities '/>
                            </td>
                            <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class='glyphicon glyphicon-minus'></i></button></td>
                            </tr>";
                            $dv_items_row++;
                            $total_disbursed += floatval($val['amount_disbursed']);
                            $total_vat += floatval($val['vat_nonvat']);
                            $total_ewt = floatval($val['ewt_goods_services']);
                            $total_compensation += floatval($val['compensation']);
                            $total_liabilities += floatval($val['other_trust_liabilities']);
                        }
                    }

                    ?>
                </tbody>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th>
                        <div id="total_disbursed">
                            <?php echo  number_format($total_disbursed, 2) ?>
                        </div>

                    </th>
                    <th>
                        <div id="total_vat">
                            <?php echo  number_format($total_vat, 2) ?>
                        </div>
                    </th>
                    <th>
                        <div id="total_ewt">
                            <?php echo  number_format($total_ewt, 2) ?>
                        </div>
                    </th>
                    <th>
                        <div id="total_compensation">
                            <?php echo number_format($total_compensation, 2) ?>
                        </div>
                    </th>
                    <th>
                        <div id="total_liabilities">
                            <?php echo number_format($total_liabilities, 2) ?>
                        </div>
                    </th>

                </tfoot>
            </table>
            <hr>
            <table id="accountng_entry_table">

                <thead>
                    <tr>
                        <td colspan="4" style="padding: 3em;"><a class="btn btn-primary insert_entry" type="button" style="float: right;">Insert Entry</a></td>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    if (!empty($accounting_entries)) {

                        foreach ($accounting_entries as $val) {

                            echo "<tr>
                            <td>
                                <div class='row'>
                                    <div class='col-sm-12'>
                                        <label for='isCurrent'>Current/NonCurrent </label>
                                        <input type='text' name='isCurrent[{$accounting_entry_row}]' placeholder='Current/NonCurrent' />
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-12'>
                                        <label for='chart_of_account'> Chart of Account</label>
                                        <select required name='object_code[{$accounting_entry_row}]' class='chart-of-accounts  accounting_entry_object_code' style='width: 100%'>
                                            <option value='{$val['object_code']}'>{$val['account_title']}</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <label for='debit'>Debit</label>
                                <input type='text' class='mask-amount form-control mask-debit' placeholder='Debit' value='{$val['debit']}'>
                                <input type='text' name='debit[{$accounting_entry_row}]' class='debit ' placeholder='Debit' value='{$val['debit']}'>
                            </td>
                            <td>
                                <label for='credit'>Credit</label>
                                <input type='text' class='mask-amount form-control mask-credit' placeholder='Credit' value='{$val['credit']}'>
                                <input type='text' name='credit[{$accounting_entry_row}]' class='credit ' value='{$val['credit']}'>
                            </td>
                            <td style='float:right;' >
                                <a class='add_accounting_entry_row btn btn-primary btn-xs' type='button' ><i class='fa fa-plus fa-fw'></i> </a>
                                <a class='remove_this_accounting_entry_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                            </td>
                        </tr>";
                            $accounting_entry_row++;
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="text-align: center;padding:3rem">Total</th>
                        <th>
                            <span>Debit:</span>
                            <br>
                            <span id="d_total"></span>
                        </th>
                        <th>
                            <span>
                                Credit:
                            </span>
                            <br>
                            <span id="c_total"></span>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <?php
            $advances_entries = [];
            if (!empty($model->id)) {
                $advances_entries = Yii::$app->db->createCommand("SELECT advances_entries.*,accounting_codes.account_title
                 FROM advances
                 LEFT JOIN advances_entries ON advances.id  = advances_entries.advances_id
                 LEFT JOIN accounting_codes ON advances_entries.object_code = accounting_codes.object_code
                 WHERE advances.dv_aucs_id = :dv_id
                 AND advances_entries.is_deleted !=1
                 ")
                    ->bindValue(':dv_id', $model->id)
                    ->queryAll();
            }
            $advances_visible = 'display:none;';
            if (!empty($advances_entries)) {
                $advances_visible = '';
            }
            ?>
            <table id="advances_table" style=" margin-top:3rem; <?= $advances_visible ?>">
                <thead>


                    <tr>
                        <th colspan="4">
                            <hr>
                        </th>
                    </tr>
                    <tr>

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

                                $province = [
                                    'ADN' => 'ADN',
                                    'ADS' => 'ADS',
                                    'SDN' => 'SDN',
                                    'SDS' => 'SDS',
                                    'PDI' => 'PDI'
                                ];
                                ?>
                                <input type="hidden" value='<?= $advances_id ?>' name="advances_id">

                                <div class="col-sm-3">
                                    <label for="report"> Province</label>
                                    <?php

                                    echo Select2::widget([
                                        'data' => $province,
                                        'name' => 'advances_province',
                                        'id' => 'province',
                                        'value' => $advances_province,
                                        'pluginOptions' => [
                                            'placeholder' => 'Select Province'
                                        ],
                                        'options' => []
                                    ])
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label for="advances_parent_reporting_period">Reporting Period</label>
                                    <?php
                                    echo DatePicker::widget([
                                        'name' => 'advances_parent_reporting_period',
                                        'value' => $advances_parent_reporting_period,
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
                                        'name' => 'advances_bank_account_id',
                                        'value' => $bank_account_id,
                                        'pluginOptions' => [
                                            'placeholder' => 'Select Bank Account'
                                        ]

                                    ]);
                                    ?>
                                </div>
                            </div>
                        </td>

                    </tr>
                    <?php

                    if (!empty($model->id) && !empty($advances_entries)) {


                        foreach ($advances_entries as $val) {
                            $maskAmount = number_format($val['amount'], 2);
                            $amount = $val['amount'];
                            echo "<tr>
                            <td>
                                <input type='hidden' name='advances_entries_id[$advances_entries_row]' value='{$val['id']}' />
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <label for='advances_reporting_period'>Reporting Period</label>
                                        <input type='month' name='advances_reporting_period[$advances_entries_row]' value='{$val['reporting_period']}' class='advances_reporting_period'  />
                                    </div>
                                    <div class='col-sm-4'>
                                        <label for='advances_report_type'>Report Type</label>
    
                                        <select name='advances_report_type[$advances_entries_row]' class='advances_report_type' style='width: 100%'>
                                            <option value='{$val['report_type']}'>{$val['report_type']}</option>
                                        </select>
                                    </div>
                                    <div class='col-sm-4'>
                                        <label for='advances_fund_source_type'>Fund Source Type</label>
    
                                        <select name='advances_fund_source_type[$advances_entries_row]' class='advances_fund_source_type' style='width: 100%'>
                                            <option value='{$val['fund_source_type']}'>{$val['fund_source_type']}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <label for='advances_fund_source'>Fund Source</label>
                                        <textarea name='advances_fund_source[$advances_entries_row]' class='advances_fund_source' cols='10' rows='2'>{$val['fund_source']}</textarea>
                                    </div>
                                    <div class='col-sm-4'>
                                        <label for='advances_object-code'>Sub Account</label>
                                        <select name='advances_object_code[$advances_entries_row]' class='chart-of-accounts' style='width: 100%'>
                                            <option value='{$val['object_code']}'>{$val['object_code']}-{$val['account_title']}</option>
                                        </select>
                                    </div>
                                    <div class='col-sm-4'>
                                        <label for='advances_amount'>Amount</label>
                                        <input type='text' class='form-control mask-amount advances_amount' value='{$maskAmount}'>
                                        <input type='hidden' name='advances_amount[$advances_entries_row]' class='advances_unmask_amount' value='{$amount}'>
                                    </div>
                                </div>
                            </td>
                            <td style='  text-align: center;width:100px'>
                                <div class='row pull-right'>
                                    <a class='add_new_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                                    <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
    
                            </td>
                        </tr>";
                            $advances_entries_row++;
                        }
                    } else {

                    ?>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="advances_reporting_period">Reporting Period</label>
                                        <input type='month' name='advances_reporting_period[0]' class="advances_reporting_period" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="advances_report_type">Report Type</label>

                                        <select name="advances_report_type[0]" class="advances_report_type" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="advances_fund_source_type">Fund Source Type</label>

                                        <select name="advances_fund_source_type[0]" class="advances_fund_source_type" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="advances_fund_source">Fund Source</label>
                                        <textarea name="advances_fund_source[0]" class="advances_fund_source" cols="10" rows="2"></textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="advances_object-code">Sub Account</label>
                                        <select name="advances_object_code[0]" class="chart-of-accounts" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="advances_amount">Amount</label>
                                        <input type="text" class="form-control mask-amount advances_amount">
                                        <input type="hidden" name="advances_amount[0]" class="advances_unmask_amount">
                                    </div>
                                </div>
                            </td>
                            <td style='  text-align: center;width:100px'>
                                <div class="row pull-right">
                                    <a class='add_new_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                                    <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>

                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                </tbody>

            </table>
            <button type="submit" class="btn btn-success" style="width: 100%;" id="save" name="save"> SAVE</button>
        </form>

    </div>
</div>

<style>
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

// $csrfTokenName = Yii::$app->request->csrfTokenName;
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
SweetAlertAsset::register($this);
?>

<!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
<script>
    function getTotal() {
        var total_disbursed = 0;
        var total_vat = 0;
        var total_ewt = 0;
        var total_compensation = 0;
        var total_liabilities = 0;
        $(".amount_disbursed").each(function() {
            total_disbursed += parseFloat($(this).val()) || 0;
        });
        $(".vat").each(function() {
            total_vat += parseFloat($(this).val()) || 0;
        });
        $(".ewt").each(function() {
            total_ewt += parseFloat($(this).val()) || 0;
        });
        $(".compensation").each(function() {
            total_compensation += parseFloat($(this).val()) || 0;
        });
        $(".liabilities").each(function() {
            total_liabilities += parseFloat($(this).val()) || 0;
        });
        $("#total_disbursed").text(thousands_separators(total_disbursed))
        $("#total_vat").text(thousands_separators(total_vat))
        $("#total_ewt").text(thousands_separators(total_ewt))
        $("#total_compensation").text(thousands_separators(total_compensation))
        $("#total_liabilities").text(thousands_separators(total_liabilities))

    }

    function remove(i) {
        i.closest("tr").remove()
        dv_count--
        getTotal()
    }

    function addDvToTable(result) {
        if ($("#transaction").val() == 'Single') {
            // $('#particular').val(result[0]['transaction_particular'])
            // $('#payee').val(result[0]['transaction_payee_id']).trigger('change')
        }
        for (var i = 0; i < result.length; i++) {
            if ($('#transaction').val() == 'Single' && i == 1) {
                break;
            }
            $('#book_id').val(result[0]['book_id'])
            var amount_disbursed = result[i]['amount_disbursed'] ? result[i]['amount_disbursed'] : 0;
            var vat_nonvat = result[i]['vat_nonvat'] ? result[i]['vat_nonvat'] : 0;
            var ewt_goods_services = result[i]['ewt_goods_services'] ? result[i]['ewt_goods_services'] : 0;
            var compensation = result[i]['compensation'] ? result[i]['compensation'] : 0;
            var other_trust_liabilities = result[i]['other_trust_liabilities'] ? result[i]['other_trust_liabilities'] : 0;
            var row = `<tr>
                            
 
                            <td > <input style='display:none' value='${result[i]['ors_id']}' type='text' name='process_ors_id[]'/></td>
 
                            <td> ${result[i]['serial_number']}</td>
                            <td> 
                            ${result[i]['transaction_particular']}
                            </td>
                            <td> ${result[i]['transaction_payee']}</td>
                            <td> ${result[i]['total']}</td>
                            <td> <input value='${amount_disbursed}' type='text' name='amount_disbursed[]' class='amount_disbursed'/></td>
                            <td> <input value='${vat_nonvat}' type='text' name='vat_nonvat[]' class='vat'/></td>
                            <td> <input value='${ewt_goods_services}' type='text' name='ewt_goods_services[]' class='ewt'/></td>
                            <td> <input value='${compensation}' type='text' name='compensation[]' class='compensation'/></td>
                            <td> <input value='${other_trust_liabilities}' type='text' name='other_trust_liabilities[]' class='liabilities'/></td>
                            <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
                        `
            $('#transaction_table tbody').append(row);
            // total += amount_disbursed
            select_id++;
            dv_count++;

        }


        getTotal()
        $("#dv_count").val(dv_count)

    }

    function insertEntry(object_code = '', account_title = '', credit = 0, debit = 0, object_code_class = 'chart-of-accounts') {
        const new_row = `<tr>
                        <td>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="isCurrent">Current/NonCurrent </label>
                                    <input type="text" name="isCurrent[${accounting_entry_row}]" placeholder="Current/NonCurrent" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="chart_of_account"> Chart of Account</label>
                                    <select required name="object_code[${accounting_entry_row}]" class="${object_code_class} accounting_entry_object_code" style="width: 100%">
                                        <option  selected="selected" value='${object_code}'>${object_code} - ${account_title}</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <label for="debit">Debit</label>
                            <input type="text" class="mask-amount form-control mask-debit" placeholder="Debit" value='${debit}'>
                            <input type="text" name="debit[${accounting_entry_row}]" class="debit" placeholder="Debit" value='${debit}'>
                        </td>
                        <td>
                            <label for="credit">Credit</label>
                            <input type="text" class="mask-amount form-control mask-credit" placeholder="Credit" value='${credit}'>
                            <input type="text" name="credit[${accounting_entry_row}]" class="credit" value='${credit}'>
                        </td>
                        <td style='float:right;'>
                            <a class='add_accounting_entry_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                            <a class='remove_this_accounting_entry_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </td>
                    </tr>`;
        $('#accountng_entry_table tbody').append(new_row)
        maskAmount()
        accountingCodesSelect()
        $('.chart-of-accounts').trigger('change')
        accounting_entry_row++;
    }





    function advancesReportTypeSelect() {
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
        console.log(object_code)
        let return_data = '';
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code&id=' + object_code,
            data: {},
            success: function(data) {
                const res = JSON.parse(data)
                return_data = data
                // console.log(data)
                return data
            }
        })
        // console.log(return_data)
        // return return_data
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
                reporting_period: $('#reporting_period').val(),
                '_csrf-frontend': '<?= $csrfToken ?>'
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
                        '_csrf-frontend': '<?= $csrfToken ?>'
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
        // $.ajax({
        //     type: 'POST',
        //     url: window.location.pathname + '?r=dv-aucs/q',
        //     data: {
        //         '_csrf-frontend': '<?= $csrfToken ?>'
        //     },
        //     success: function(data) {
        //         console.log(data)
        //     }
        // })


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
        getFundSourceType().then((data) => {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.name,
                    text: val.name
                })
            })
            fund_source_type = array
            advancesFundSourceTypeSelect()
        })

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
            console.log($(this).maskMoney('unmasked')[0])
            $(this).parent().find('.advances_unmask_amount').val($(this).maskMoney('unmasked')[0])
        });

        $('#nature_of_transaction ').change(function() {
            var nature_selected = $(this).children(':selected').text()
            if (nature_selected == 'CA to SDOs/OPEX') {
                $('#advances_table').show()
            } else {
                $('#advances_table').hide()
            }
        })
        $('#nature_of_transaction').trigger('change')
        // SAVE FORM
        $('#save_data').submit(function(e) {
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $("#save_data").serialize(),
                success: function(data) {
                    console.log(JSON.parse(data))
                    const res = JSON.parse(data)

                    if (!jQuery.isEmptyObject(res.form_error)) {

                        $.each(res.form_error, function(key, val) {
                            $('.' + key + '_error').text(val)
                            console.log('#' + key + '_error')
                        })
                    } else if (!jQuery.isEmptyObject(res.check_error)) {
                        swal({
                            icon: 'error',
                            title: 'Error',
                            text: res.check_error,
                            type: "error",
                            timer: 10000,
                            closeOnConfirm: false,
                            closeOnCancel: false
                        })
                    }

                }
            })
        })


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

SweetAlertAsset::register($this); ?>
<?php


?>