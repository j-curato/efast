<?php

use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payroll */
/* @var $form yii\widgets\ActiveForm */

$process_ors = [];
$row_number = 1;
if (!empty($model->id)) {
    $process_ors_query = Yii::$app->db->createCommand("SELECT id ,serial_number FROM process_ors WHERE id =:id")
        ->bindValue(':id', $model->process_ors_id)
        ->queryAll();
    $process_ors = ArrayHelper::map($process_ors_query, 'id', 'serial_number');
}
?>

<div class="payroll-form">
    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months',
                        'autoclose' => true
                    ]
                ]) ?>

            </div>
            <div class="col-sm-4">

                <?= $form->field($model, 'process_ors_id')->widget(Select2::class, [
                    'data' => $process_ors,
                    'options' => ['placeholder' => 'Search for a ORS ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=process-ors/search-ors',
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
            <div class="col-sm-4">
                <?= $form->field($model, 'type')->widget(Select2::class, [
                    'data' => [
                        '2307' => '2307(for JO/COS)',
                        '1601c' => '1601c for Regular/Contractual/CTI/CTO',

                    ],
                    'pluginOptions' => [
                        'placeholder' => 'Select Due to BIR Classification'
                    ]
                ]) ?>
            </div>

        </div>
        <div class="row">

            <div class="col-sm-6">
                <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
                    'options' => [
                        'class' => 'amounts',
                    ],
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => true
                    ],
                ]) ?>

            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'due_to_bir_amount')->widget(MaskMoney::class, [
                    'options' => [
                        'class' => 'amounts',
                    ],
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => true
                    ],
                ]) ?>

            </div>
        </div>






        <table id="items-table" class="table table-striped">
            <thead>
                <tr>
                    <th colspan="3">
                        <button type="button" class="add btn btn-warning" style="float: right;"> Add</button>
                    </th>
                </tr>
            </thead>
            <tbody>

                <?php

                if (!empty($model->id)) {
                    $query = Yii::$app->db->createCommand("SELECT 
            payroll_items.remittance_payee_id,
            payee.account_name,
            payroll_items.amount,
            remittance_payee.object_code
            FROM `payroll_items`
            LEFT JOIN remittance_payee ON payroll_items.remittance_payee_id = remittance_payee.id
            LEFT JOIN payee ON remittance_payee.payee_id = payee.id
            WHERE payroll_items.payroll_id = :id
            ")
                        ->bindValue(':id', $model->id)
                        ->queryAll();
                    foreach ($query as $val) {


                        echo "<tr>
                    <td>
                    <label>Remittance Payee</label>
                    <select class='remittance-payee' name='remittance_payee[$row_number]' style='width:100%' data-index='$row_number' data-object-code='{$val['object_code']}'>
                    <option value='{$val['remittance_payee_id']}'>{$val['account_name']}</option>
                    </select>
                    </td>
                    <td>
                    <label for='payee_amount'>Amount</label>
                    <input type='text' class='mask-amount form-control payee_amount' value='{$val['amount']}'>
                    <input type='hidden' name='payee_amount[${row_number}]' class='main_payee_amount'  value='{$val['amount']}'>
                    </td>
                    <td>   <a class='remove_this_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a></td>
                </tr>";
                        $row_number++;
                    }
                }
                ?>
            </tbody>

        </table>



        <table id='total_table' class="table table-striped">
            <thead>
                <th>Amount Disbursed</th>
                <th>2307(EWT)</th>
                <th>1601c(Compensation)</th>
                <th>Other Trust Liabilities</th>
                <th>Total Obligations</th>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <span id="amount_disbursed"></span>
                    </td>
                    <td>
                        <span id="2307_ewt"></span>
                    </td>
                    <td>
                        <span id="1601c_compensation"></span>
                    </td>
                    <td>
                        <span id="other_trust_liab"></span>
                    </td>
                    <td>
                        <span id="total_obligation"></span>
                    </td>

                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-sm-5"></div>
            <div class="form-group col-sm-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
            </div>
            <div class="col-sm-5"></div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .container {
        background-color: white;
    }

    #total_table td,
    th {
        text-align: center;
    }
</style>
<?php

// $csrfTokenName = Yii::$app->request->csrfTokenName;
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
?>

<script>
    let row_number = 0;

    function addRow() {

        const row = `<tr>
                        <td>
                        <label for='remittance-payee'>Remittance Payee</label>
                        <select class='remittance-payee' name='remittance_payee[${row_number}]' style='width:100%' data-index='${row_number}'><option></option></select>
                        </td>
                        <td >
                        <label for='payee_amount'>Amount</label>
                        <input type='text'  class='mask-amount form-control payee_amount' >
                        <input type='hidden' name='payee_amount[${row_number}]' class='main_payee_amount' >
                        </td>
                        <td><a class='remove_this_row btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i></a></td>
                    </tr>`
        $('#items-table tbody').append(row);
        row_number++
        maskAmount()
        remittancePayee()
    }

    function remittancePayee() {
        $(".remittance-payee").select2({
            ajax: {
                url: base_url + "?r=remittance-payee/search-payee",
                dataType: "json",
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results,
                    };
                },
            },

        });

    }

    function getTotal() {
        let total_trust_liab = 0
        let total_due_to_bir = 0;
        let total_obligation = $('#payroll-amount').val() != '' ? parseFloat($('#payroll-amount').val()) : 0;
        $('input[name^="payee_amount"]').each(function(key, val) {
            let value = val.value != '' ? $(this).maskMoney('unmasked')[0] : 0
            const index_number = parseInt($(this).attr('name').replace(/[^0-9.]/g, ""));
            const object_code = $(`select[data-index=${index_number}]`).attr('data-object-code')

            if (object_code == '2020101000') {
                total_due_to_bir += parseFloat(value)
            } else {
                total_trust_liab += value

            }

            total_obligation += value


        })
        const due_to_bir_amount = $('#payroll-due_to_bir_amount').val()!=''?$('#payroll-due_to_bir_amount').val():0
        total_due_to_bir += parseFloat(due_to_bir_amount)
        console.log(total_due_to_bir)
        const payroll_type = $('#payroll-type').val()
        $("#2307_ewt").text('')
        $("#1601c_compensation").text('')
        if (payroll_type == '2307') {
            $("#2307_ewt").text(thousands_separators(total_due_to_bir))
        } else if (payroll_type == '1601c') {
            $("#1601c_compensation").text(thousands_separators(total_due_to_bir))
        }
        $("#other_trust_liab").text(thousands_separators(total_trust_liab))
        $("#total_obligation").text(thousands_separators(total_obligation))
    }

    function getAmountDisbursed() {
        const value = $('#payroll-amount').val() != '' ? $('#payroll-amount').val() : 0
        $('#amount_disbursed').text(thousands_separators(value))
    }
    $(document).ready(function() {
        remittancePayee()
        maskAmount()
        getTotal()
        getAmountDisbursed()
        $('#items-table').trigger('change', '.remittance_payee')
        row_number = <?= $row_number ?>;
        $(".add").click(function() {
            addRow()
        })
        $('#items-table').on('click', '.remove_this_row', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
            getTotal()
        });
        $('#payroll-amount').on('change keyup', function() {
            getAmountDisbursed()
        })
        $('#items-table').on('change keyup', '.payee_amount ', function() {
            getTotal()
        })
        $('#payroll-type').change(function() {
            getTotal()
        })
        // $('#payroll-due_to_bir_amount-disp').on('keyup change',function() {
        //     getTotal()
        // })

        $('#items-table').on('select2:select', '.remittance-payee', function(e) {
            const data = e.params.data;
            $(this).attr('data-object-code', data.object_code)
        });
        $("#items-table").on('keyup change', '.payee_amount', function() {

            $(this).parent().find('.main_payee_amount').val($(this).maskMoney('unmasked')[0])
        })
        $("#payroll-due_to_bir_amount").on('keyup change', function() {
            getTotal()
        })
    })
</script>