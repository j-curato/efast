<?php

use app\models\Books;
use app\models\Payee;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Remittance */
/* @var $form yii\widgets\ActiveForm */

$payroll_number = '';
$row_number  = 1;
// if (!empty($model->payroll_id)) {

//     $payroll_query = Yii::$app->db->createCommand("SELECT id,payroll_number FROM payroll WHERE id = :id")
//         ->bindValue(':id', $model->payroll_id)
//         ->queryAll();
//     $payroll_number = ArrayHelper::map($payroll_query, 'id', 'payroll_number');
// }
$payee = [];

if (!empty($model->id)) {
    $payee  = Yii::$app->db->createCommand("SELECT payee.id , payee.account_name FROM payee WHERE payee.id = :id")
        ->bindValue(':id', $model->payee_id)
        ->queryOne();
}

?>

<div class="remittance-form panel" style="padding: 2rem;">

    <?php $form = ActiveForm::begin([
        'id' => 'RemittanceForm'
    ]); ?>

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
        <!-- <div class="col-sm-3">
            <?= $form->field($model, 'payee_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Payee::find()->where('id =:id', ['id' => $model->payee_id])->asArray()->all(), 'id', 'account_name'),
                'options' => ['placeholder' => 'Search Payee ...'],
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
                        'data' => new JsExpression('function(params) { return {q:params.term,page: params.page||1}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]) ?>
        </div> -->
        <div class="col-sm-3">
            <label for="remittance_payee">Payee</label>
            <select class='remittance_payee' name='remittance_payee' style="width: 100%;" required>
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


            $total = 0;
            foreach ($items as $val) {
                $to_remit_amount = number_format($val['to_remit_amount'], 2);
                $remitted_amount = number_format($val['remitted_amount'], 2);
                $unremited_amount = number_format($val['unremited_amount'], 2);
                echo "<tr>
                        <td style='display:none'><input type='hidden' value='{$val['remittance_items_id']}' class='checkbox' name='items[$row_number][item_id]'></td>
                        <td style='display:none'><input type='hidden' value='{$val['dv_accounting_entries_id']}' class='checkbox' name='items[$row_number][payrol_entry_id]'></td>
                        <td>{$val['payroll_number']}</td>
                        <td>{$val['ors_number']}</td>
                        <td>{$val['dv_number']}</td>
                        <td>{$val['payee']}</td>
                        <td>{$val['object_code']}</td>
                        <td>{$val['account_title']}</td>
                        <td>{$to_remit_amount}</td>
                        <td>{$remitted_amount}</td>
                        <td>{$unremited_amount}</td>
                        <td>
                        <input type='text' class='form-control mask-amount' value='" . number_format($val['amount'], 2) . "'>
                        <input type='hidden' class='form-control main-amount' name='items[$row_number][amount]' value='{$val['amount']}'>
                        </td>
                        <td><button  class='remove btn-xs btn-danger '><i class='fa fa-times'></i></button></td>
                    </tr>";
                $row_number++;
                $total += floatval($val['amount']);
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="9" class="center">Total</th>
                <th id="totalAmt">
                    <?= number_format($total) ?>
                </th>
            </tr>
        </tfoot>

    </table>
    <div class="row justify-content-center">

        <div class="form-group col-sm-2 " style="padding:1rem">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?php
    $dataProvider->pagination = ['pageSize' => 10];

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Payroll',
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
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::input('text', 'item[allotment_id]', $model->payroll_item_id, ['class' => 'payrol_entry_id']);
                },
                'hidden' => true
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'addItem(this)']);
                },
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

    .center {
        text-align: center;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    let row_number = <?= $row_number ?>;

    function addItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.add').closest('td').remove()
        clone.find('.payrol_entry_id').attr('name', `items[${row_number}][payrol_entry_id]`)
        clone.append(`<td> <input type='text' class='mask-amount amount form-control' onkeyup='updateMainAmount(this)'><input type='hidden' name='items[${row_number}][amount]' class='gross_amount main-amount'></td>`)
        clone.append('<td class="right"><button type="button" class="remove btn-xs btn-danger"><i class="fa fa-times"></i></button></td>')
        $('#items_table tbody').append(clone);
        maskAmount()
        row_number++;
    }

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

    function getTtlAmt() {
        let total = 0

        $(".main-amount").each(function(key, val) {
            total += parseFloat(val.value)
        })
        if (isNaN(total)) {
            total = 0
        }
        $('#totalAmt').text(thousands_separators(total))
    }


    $(document).ready(function() {


        $('#items_table tbody').on('keyup', '.mask-amount', function(event) {
            getTtlAmt()
        });
        maskAmount()
        changeHiddenPayeeId()
        $(".remittance_payee").select2({})

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

        // $('#add').click(function() {

        //     const items_table_row_count = $("#items_table tbody tr").length;

        //     $(".checkbox:checked").each(function() {
        //         const checkedValue = $(this).closest('tr');
        //         // checkedValue.closest('.checkbox').removeAttr('checked')


        //         const buttons = `<td><button  class='remove btn btn-danger '><i class="fa fa-times"></i></button></td>`
        //         const amount_input = `<td>
        //                             <input type='text' class='form-control mask-amount' >
        //                             <input type='hidden' class='main-amount' name='amount[${row_number}]'>
        //                             </td>`
        //         const clone = checkedValue.clone();
        //         // // console.log(clone.children('td').eq(0).find('.checkbox').val())
        //         clone.find('.checkbox').attr('type', 'text');
        //         clone.find('.checkbox').attr('name', `dv_accounting_entry_id[${row_number}]`);
        //         clone.find('.checkbox').closest('td').css('display', 'none');
        //         clone.append(amount_input)
        //         clone.append(buttons)

        //         $('#items_table tbody').append(clone);
        //         row_number++;

        //     });
        //     maskAmount()

        // })

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
        $("#remittance-payee_id").change(function() {
            changeHiddenPayeeId($(this).val())
        })
    })

    function changeHiddenPayeeId(id) {
        $("input[name='WithholdingAndRemittanceSummarySearch[newProperty]']").val($('#remittance-type').val())
        if ($('#remittance-type').val() == 'adjustment') {
            $("input[name='WithholdingAndRemittanceSummarySearch[newProperty]']").trigger('change')
        }
        $("input[name='WithholdingAndRemittanceSummarySearch[payee_id]']").val(id)
        $("input[name='WithholdingAndRemittanceSummarySearch[payee_id]']").trigger('change')
    }
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#RemittanceForm").on("beforeSubmit", function (event) {
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
                title: res.error_message,
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