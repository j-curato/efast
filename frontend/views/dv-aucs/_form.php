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
if (!empty($model->payee_id)) {
    $payee = ArrayHelper::map(Payee::find()->where('id = :id', ['id' => $model->payee_id])->asArray()->all(), 'id', 'account_name');
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
            <?= $form->field($model, 'object_code')->textInput(['maxlength' => true]) ?>

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
                            <input type='text'  class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='{$val['amount_disbursed']}'/>
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
                            <td>
                                <button  type='button' class='btn-xs btn-danger' onclick='RemoveItem(this)'>
                                    <i class='glyphicon glyphicon-minus'></i>
                                </button>
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
            <th>Current/Non-Current</th>
            <th>Object Code / Account Title</th>
            <th>Debit</th>
            <th>Credit</th>
        </thead>
        <tbody>

        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let accounting_entry_row = <?= $accounting_entry_row ?>;

    function addEntry() {
        const new_row = `<tr>
                <td>
                    <input type="text" name="dvAccItems[${accounting_entry_row}][isCurrent]" placeholder="Current/NonCurrent" class='form-control'/>
                </td>
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
    $(document).ready(() => {



    })
</script>