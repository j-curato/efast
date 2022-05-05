<?php

use app\models\Books;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Remittance */
/* @var $form yii\widgets\ActiveForm */

$payroll_number = '';
$row_number  = 1;
if (!empty($model->payroll_id)) {

    $payroll_query = Yii::$app->db->createCommand("SELECT id,payroll_number FROM payroll WHERE id = :id")
        ->bindValue(':id', $model->payroll_id)
        ->queryAll();
    $payroll_number = ArrayHelper::map($payroll_query, 'id', 'payroll_number');
}
$items = [];
$payee = [];

if (!empty($model->id)) {
    $items =  Yii::$app->db->createCommand("SELECT 
    payroll.payroll_number,
    process_ors.serial_number as ors_number,
    dv_aucs.dv_number,
    payee.account_name as payee,
    accounting_codes.object_code,
    accounting_codes.account_title,
    remittance_items.amount,
    dv_accounting_entries.id as dv_accounting_entries_id,
    remittance_items.id as remittance_items_id,
    IFNULL(dv_accounting_entries.credit,0) + IFNULL(dv_accounting_entries.debit,0) as to_remit_amount,
        remitted.remitted_amount,
        (IFNULL(dv_accounting_entries.credit,0) + IFNULL(dv_accounting_entries.debit,0)) - IFNULL(  remitted.remitted_amount,0) unremited_amount

    
     FROM `remittance_items`
    INNER JOIN dv_accounting_entries ON remittance_items.fk_dv_acounting_entries_id = dv_accounting_entries.id
    INNER JOIN payroll ON dv_accounting_entries.payroll_id = payroll.id
    INNER JOIN process_ors ON payroll.process_ors_id = process_ors.id
    INNER JOIN dv_aucs ON payroll.id = dv_aucs.payroll_id
    INNER JOIN remittance_payee ON dv_accounting_entries.remittance_payee_id = remittance_payee.id
    INNER JOIN payee ON remittance_payee.payee_id = payee.id
    INNER JOIN accounting_codes ON dv_accounting_entries.object_code  = accounting_codes.object_code
    INNER JOIN dv_aucs_entries ON dv_accounting_entries.dv_aucs_id = dv_aucs_entries.dv_aucs_id
    LEFT JOIN (SELECT 
        remittance_items.fk_dv_acounting_entries_id,
        SUM(remittance_items.amount) as remitted_amount
        FROM remittance_items
        GROUP BY fk_dv_acounting_entries_id) as remitted ON dv_accounting_entries.id = remitted.fk_dv_acounting_entries_id 
    WHERE remittance_items.fk_remittance_id=:id

    AND remittance_items.is_removed= 0
    ")
        ->bindValue(':id', $model->id)
        ->queryAll();
    $payee  = Yii::$app->db->createCommand("SELECT payee.id , payee.account_name FROM payee WHERE payee.id = :id")
        ->bindValue(':id', $model->payee_id)
        ->queryOne();
}

?>

<div class="remittance-form">
    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
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
            <div class="col-sm-3">

                <?= $form->field($model, 'type')->widget(Select2::class, [

                    'data' => ['remittance_to_payee' => 'Remittance to Payee', 'adjustment' => 'Adjustment'],
                    'pluginOptions' => [
                        'placeholder' => 'Select Type'
                    ]

                ]) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6">
                <label for="remittance_payee">Payee</label>
                <select class='remittance_payee' name='remittance_payee' style="width: 100%;">
                    <?php
                    echo $model->payee_id;
                    if (!empty($payee)) {
                        // var_dump($payee);
                        echo "  <option value='{$payee['id']}'>{$payee['account_name']}</option>";
                    } else {
                        echo "  <option></option>";
                    }
                    ?>
                </select>

            </div>
        </div>






        <table id="items_table" style="margin-top: 3rem;">
            <thead>
                <th>Payroll No.</th>
                <th>ORS No.</th>
                <th>DV No.</th>
                <th>Payee</th>
                <th>Object Code</th>
                <th>Account Title</th>
                <th> To Be Remitted </th>
                <th> Remitted</th>
                <th> Unremitted</th>
                <th> Amount</th>
            </thead>
            <tbody>

                <?php

                foreach ($items as $val) {
                    echo "<tr>
                        <td style='display:none'><input type='hidden' value='{$val['remittance_items_id']}' class='checkbox' name='remittance_items_id[$row_number]'></td>
                        <td style='display:none'><input type='hidden' value='{$val['dv_accounting_entries_id']}' class='checkbox' name='dv_accounting_entry_id[$row_number]'></td>
                        <td>{$val['payroll_number']}</td>
                        <td>{$val['ors_number']}</td>
                        <td>{$val['dv_number']}</td>
                        <td>{$val['payee']}</td>
                        <td>{$val['object_code']}</td>
                        <td>{$val['account_title']}</td>
                        <td>{$val['to_remit_amount']}</td>
                        <td>{$val['remitted_amount']}</td>
                        <td>{$val['unremited_amount']}</td>
                        <td>
                        <input type='text' class='form-control mask-amount' value='{$val['amount']}'>
                        <input type='hidden' class='form-control main-amount' name='amount[$row_number]' value='{$val['amount']}'>
                        </td>
                        <td><button  class='remove btn btn-danger '><i class='glyphicon glyphicon-minus'></i></button></td>
                    </tr>";
                    $row_number++;
                }
                ?>
            </tbody>

        </table>
        <div class="row">
            <div class="col-sm-5"></div>

            <div class="form-group col-sm-2" style="padding:1rem">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
            <div class="col-sm-5"></div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <?php
    $dataProvider->pagination = ['pageSize' => 10];

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'export' => false,
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                'id' => 'pjax_advances'

            ]
        ],
        'showPageSummary' => true,
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'checkboxOptions' => function ($model) {
                    return ['value' => $model->payroll_item_id, 'style' => 'width:20px;', 'class' => 'checkbox'];
                }
            ],
            'payroll_number',
            'ors_number',
            'dv_number',
            'payee',
            'object_code',
            'account_title',
            [
                'label' => 'To Be Remitted',
                'attribute' => 'amount',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'label' => 'Remitted',
                'attribute' => 'remitted_amount',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'label' => 'UnRemitted',
                'attribute' => 'unremitted_amount',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],

            [
                'attribute' => 'newProperty',
                'hidden' => true
            ],
            [
                'attribute' => 'payee_id',
                'hidden' => true
            ],
        ],
    ]); ?>

    <div class="row">
        <div class="col-sm-5"></div>
        <div class="col-sm-2">
            <button type="button" id="add" class="btn btn-primary" style="margin-left: auto;">Add</button>

        </div>
        <div class="col-sm-5"></div>
    </div>


</div>
<style>
    table {
        width: 100%;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 1rem;

    }

    .container {
        background-color: white;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    function displayPayrollItems(data) {
        const table = $('.payee-table tbody')
        table.html('')
        $.each(data, function(key, val) {

            const row = `<tr>
                <td><input type='checkbox' value='${val.dv_accounting_entries_id}' class='checkbox'></td>
                <td>${val.payroll_number}</td>
                <td>${val.ors_number}</td>
                <td>${val.dv_number}</td>
                <td>${val.payee}</td>
                <td>${val.object_code}</td>
                <td>${val.account_title}</td>
                <td>${val.amount}</td>
            </tr>`;
            table.append(row)
        })
    }

    function getPayrollData() {
        $.ajax({
            type: 'POST',
            url: window.location.pathname + "?r=payroll/payroll-items",
            data: {
                id: $('#remittance-payroll_id').val(),
                '_csrf-frontend': '<?= $csrfToken ?>'
            },
            success: function(data) {
                const res = JSON.parse(data)
                displayPayrollItems(res)
            }
        })
    }
    let row_number = 0;

    $(document).ready(function() {


        maskAmount()
        changeHiddenPayeeId()
        $(".remittance_payee").select2({})
        row_number = <?= $row_number ?>;
        if ($('#remittance-payroll_id').val() != null) {
            $('.payroll').show()

            getPayrollData()
        }
        if ($('#remittance-payee_id').val() != null) {
            $('.payee').show()
        }
        $(".remittance_payee").select2({
            ajax: {
                url: window.location.pathname + "?r=remittance/search-payee",
                dataType: "json",
                data: function(params) {
                    return {
                        q: params.term,
                        type: $('#remittance-type').val()
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
        $('#remittance-type').change(function() {
            $("input[name='WithholdingAndRemittanceSummarySearch[newProperty]']").val($('#remittance-type').val())
            $("input[name='WithholdingAndRemittanceSummarySearch[newProperty]']").trigger('change')
            $("input[name='WithholdingAndRemittanceSummarySearch[payee_id]']").val($(".remittance_payee").val())
            $("input[name='WithholdingAndRemittanceSummarySearch[payee_id]']").trigger('change')
            if ($(this).val() == 'adjustment') {

                // $("input[name='WithholdingAndRemittanceSummarySearch[payee]']").attr('', false)
                $('#remittance-payroll_id').val('').trigger('change')
            } else if ($(this).val() == 'remittance_to_payee') {
                // $("input[name='WithholdingAndRemittanceSummarySearch[payee]']").attr('', true)
                $('#remittance-payee_id').val('').trigger('change')
            }
            $(".remittance_payee").select2({
                ajax: {
                    url: window.location.pathname + "?r=remittance/search-payee",
                    dataType: "json",
                    data: function(params) {
                        return {
                            q: params.term,
                            type: $('#remittance-type').val()
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
        })
        $('#remittance-payroll_id').change(function() {

            getPayrollData()
        })

        $('#add').click(function() {

            const items_table_row_count = $("#items_table tbody tr").length;

            $(".checkbox:checked").each(function() {
                const checkedValue = $(this).closest('tr');
                // checkedValue.closest('.checkbox').removeAttr('checked')


                const buttons = `<td><button  class='remove btn btn-danger '><i class="glyphicon glyphicon-minus"></i></button></td>`
                const amount_input = `<td>
                                    <input type='text' class='form-control mask-amount' >
                                    <input type='hidden' class='main-amount' name='amount[${row_number}]'>
                                    </td>`
                const clone = checkedValue.clone();
                // // console.log(clone.children('td').eq(0).find('.checkbox').val())
                clone.find('.checkbox').attr('type', 'text');
                clone.find('.checkbox').attr('name', `dv_accounting_entry_id[${row_number}]`);
                clone.find('.checkbox').closest('td').css('display', 'none');
                clone.append(amount_input)
                clone.append(buttons)

                $('#items_table tbody').append(clone);
                row_number++;

            });
            maskAmount()

        })

        // $("#items_table").click('.remove',function(e){
        //     e.preventDefault()
        //     $(this).closest('tr').remove()
        // })
        $('#items_table tbody').on('click', '.remove', function(event) {
            event.preventDefault();

            $(this).closest('tr').remove();
        });
        $('#items_table tbody').on('keyup change', '.mask-amount', function(event) {
            event.preventDefault();
            $(this).closest('td').find('.main-amount').val($(this).maskMoney('unmasked')[0]);
        });
        $(".remittance_payee").change(function() {

            changeHiddenPayeeId($(this).val())


        })
    })

    function changeHiddenPayeeId(id) {
        $("input[name='WithholdingAndRemittanceSummarySearch[newProperty]']").val($('#remittance-type').val())
        if ($('#remittance-type').val() == 'adjustment') {
            console.log($('#remittance-type').val())

            $("input[name='WithholdingAndRemittanceSummarySearch[newProperty]']").trigger('change')
        }
        $("input[name='WithholdingAndRemittanceSummarySearch[payee_id]']").val(id)
        $("input[name='WithholdingAndRemittanceSummarySearch[payee_id]']").trigger('change')
    }
</script>