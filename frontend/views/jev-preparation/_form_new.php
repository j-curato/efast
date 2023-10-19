<?php

use app\models\Books;
use app\models\ResponsibilityCenter;
use aryelds\sweetalert\SweetAlert;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$payee = [];
$entry_row = 1;
?>
<div class="test" style="background-color:white;border:1px solid black;padding:20px">


    <!-- <div id="container" class="container"> -->

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <?php

    $dv_number = '';
    $payee_id = '';
    $reporting_period = '';
    $book_id = '';
    $particular = '';
    $payee_name = '';
    $cash_dibursement_id = '';
    if (!empty($model->payee_id)) {
        $payee_query = Yii::$app->db->createCommand("SELECT id,account_name FROM payee WHERE id = :id")->bindValue(':id', $model->payee->id)->queryAll();
        $payee = ArrayHelper::map($payee_query, 'id', 'account_name');
    }
    if (!empty($model->cash_disbursement_id)) {

        $cash_disbursement_id = Yii::$app->db->createCommand("SELECT cash_disbursement.id, dv_aucs.dv_number FROM cash_disbursement
        INNER JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        WHERE cash_disbursement.id = :id")
            ->bindValue(':id', $model->cash_disbursement_id)
            ->queryAll();
        $cash_dibursement_id = ArrayHelper::map($cash_disbursement_id, 'id', 'dv_number');
    }
    if (!empty($model->id)) {
    }
    ?>

    <?= $form->field($model, 'form_token')->hiddenInput()->label(false) ?>
    <div class="row">
        <div class="col-sm-3">
            <label for="cdr_id">CDR Serial#</label>
            <?= Select2::widget([
                'name' => 'cdr_id',
                'id' => 'cdr_id',
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=cdr/search-cdr',
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
            <h4 id="have_jev" style='color:red'></h4>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'cash_disbursement_id')->widget(Select2::class, [
                'data' => $cash_dibursement_id,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=cash-disbursement/search-dv',
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
        <div class="col-sm-3">
            <label for="total_disbursed"> Total Disbursed</label>
            <h4 id="total_disbursed"></h4>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'entry_type')->widget(Select2::class, [
                'data' => [
                    'Closing' => 'Closing',
                    'Non-Closing' => 'Non-Closing'
                ],
                'pluginOptions' => [
                    'placeholder' => 'Select Entry Type'
                ]
            ]) ?>
        </div>

    </div>
    <div class="row">

        <div class="col-sm-3">



            <?= $form->field($model, 'check_ada_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ],
                'options' => []
            ]) ?>
        </div>
        <div class="col-sm-3">


            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ],
            ]) ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm',
                    'startView' => "year",
                    'minViewMode' => "months",
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'ref_number')->widget(Select2::class, [
                'data' => [
                    "CDJ" => "CDJ",
                    "CRJ" => "CRJ",
                    "GJ" => "GJ"
                ],
                'options' => [],

                'pluginOptions' => [
                    'placeholder' => 'Select Reference',

                ]
            ]) ?>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-3" style="height:60x">
            <?= $form->field($model, 'book_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Book'
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'responsibility_center_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(ResponsibilityCenter::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Responsibility Center'
                ]
            ]) ?>
        </div>

        <div class="col-sm-3">

            <?= $form->field($model, 'check_ada')->widget(Select2::class, [
                'data' => [
                    'Noncash' => 'Noncash',
                    'Check' => 'Check',
                    'ADA' => 'ADA'
                ],
                'pluginOptions' => [
                    'placeholder' => 'Check/ADA'
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'payee_id')->widget(Select2::class, [
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

            ]) ?>
        </div>
    </div>

    <div class=" row">


        <div class="col-sm-3">
            <?= $form->field($model, 'lddap_number')->textInput() ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'dv_number')->textInput() ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'cadadr_serial_number')->textInput() ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'check_ada_number')->textInput() ?>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'explaination')->textarea(['cols' => "151", 'rows' => "3"]) ?>
        </div>
    </div>
    <table class="table" id='entry_table'>




        <tfoot>

            <tr>
                <th style="text-align: center;">Total</th>
                <th><span class="total_debit"></span></th>
                <th><span class="total_credit"></span></th>
            </tr>
        </tfoot>

    </table>
    <div class="row" style="margin-top: 4rem;">
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

<style>
    textarea {
        max-width: 100%;
        width: 100%;
    }


    .accounting_entries {
        max-width: 98%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
        margin-bottom: 10px;
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

<script>
    let row_number = <?= $entry_row ?>;

    function getTotal() {
        let total_credit = 0;
        let total_debit = 0;

        $('input[name^="credit"]').each(function() {
            total_credit += Number($(this).val());
        });
        $('input[name^="debit"]').each(function() {
            total_debit += Number($(this).val());
        });



        $('.total_debit').text(thousands_separators(total_debit))
        $('.total_credit').text(thousands_separators(total_credit))

    }

    // INSERT ENTRIES ON CASH_DISBURSEMENT_ID_CHANGE
    function insertEntryFromDv(entries) {
        $("#entry_table tbody").html("")

        $.each(entries, function(key, val) {
            const credit = val.credit
            const debit = val.debit
            const account_title = val.account_title
            const object_code = val.object_code
            const row = ` <tr>
                    <td style="width: 300px;max-width:300px">
                        <div>
                            <label for="chart-of-accounts">UACS</label>
                            <select required name="object_code[${row_number}]" class="chart-of-accounts" style="width: 100%">
                                <option value='${object_code}' selected>${object_code} - ${account_title}</option>
                            </select>
                        </div>
                    </td>
                    <td style="width: 150px;">
                        <label for="debit">Debit</label>
                        <input type="text" class="  mask-amount" placeholder="Debit" value='${debit}'>
                        <input type="hidden" name="debit[${row_number}]" class="debit main_amount" placeholder="Debit" value='${debit}'>
                    </td>
                    <td style="width: 150px;">
                        <label for="credit">Credit</label>
                        <input type="text" class="  mask-amount" placeholder="Credit" value='${credit}'>
                        <input type="hidden" name="credit[${row_number}]" class="credit main_amount" placeholder="Credit" value='${credit}'>
                    </td>
                    <td style="width: 50px;">
                        <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; float:right;"><i class="fa fa-times"></i></button>
                        <button type="button" class='add btn btn-success btn-xs' style=" text-align: center; float:right;margin-right:5px"><i class="fa fa-pencil-alt"></i></button>
                    </td>
                </tr>`

            $("#entry_table tbody").append(row)
            maskAmount()
            accountingCodesSelect()
            row_number++
        })
    }

    function onCashDisbursementChange(data) {
        $('#jevpreparation-dv_number').val(data.dv_number).tri
        $('#jevpreparation-book_id').val(data.book_id).trigger('change')
        $('#jevpreparation-ref_number').prop('disabled', true)
        $("#jevpreparation-book_id option:not(:selected)").attr("disabled", true)
        let payeeSelect = $('#jevpreparation-payee_id');
        const option = new Option([data.payee_name], [data.payee_id], true, true);
        payeeSelect.append(option).trigger('change');
        $('#jevpreparation-responsibility_center_id').val(data.rc_id).trigger('change')
        $("#jevpreparation-responsibility_center_id option:not(:selected)").attr("disabled", true)
        $('#jevpreparation-explaination').val(data.particular)
        $('#jevpreparation-ada_number').val(data.check_or_ada_no)
        $('#jevpreparation-check_ada_date').val(data.issuance_date)
        $('#jevpreparation-date').val(data.issuance_date)
        $('#jevpreparation-check_ada_number').val(data.check_or_ada_no)
        $('#total_disbursed').text(thousands_separators(data.total_disbursed))
        $('#jevpreparation-reporting_period').val(data.reporting_period)

        if (data.mode_of_payment.toLowerCase() == 'ada') {
            $("#jevpreparation-check_ada").val('ADA').trigger('change')
            $("#jevpreparation-ada_number").val(data.ada_number).trigger('change')

        } else {

            $("#jevpreparation-check_ada").val('Check').trigger('change')
            $("#jevpreparation-ada_number").val(data.check_or_ada_no).trigger('change')
        }
        $("#jevpreparation-check_ada option:not(:selected)").attr("disabled", true)
    }
    $(document).ready(function() {
        accountingCodesSelect()
        maskAmount()
        getTotal()

        if ($("#jevpreparation-cash_disbursement_id").val() != null) {
            $.ajax({
                type: "POST",
                url: window.location.pathname + "?r=cash-disbursement/get-dv",
                data: {
                    cash_id: $("#jevpreparation-cash_disbursement_id").val(),
                    '_csrf-frontend': '<?= $csrfToken ?>'
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    onCashDisbursementChange(res.results)
                    insertEntryFromDv(res.dv_accounting_entries)
                }
            })
        }
        $('#entry_table').on('change', '.mask-amount', function() {
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('td');
            source.find('.main_amount').val(amount)
            getTotal()
        })
        $("#jevpreparation-cash_disbursement_id").change(function() {

            $.ajax({
                type: "POST",
                url: window.location.pathname + "?r=cash-disbursement/get-dv",
                data: {
                    cash_id: $(this).val()
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    console.log(res)
                    if (res.results.jev_id) {

                        $('#have_jev').text('This DV Naa nay JEV ')
                        eee = window.location.pathname + "?r=jev-preparation/view&id=" + res.results.jev_id

                        bbb = $(`<a type="button" href='` + eee + `' >link here</a>`);
                        bbb.appendTo($("#have_jev"));


                    }
                    onCashDisbursementChange(res.results)
                    insertEntryFromDv(res.dv_accounting_entries)
                    getTotal()
                }
            })

        })


        $('.remove').on('click', function(event) {
            event.preventDefault();

            $(this).closest('tr').remove();
        });
        $('#entry_table').on('click', '.add', function(event) {
            event.preventDefault();
            const source = $(this).closest('tr');
            source.find('.chart-of-accounts').select2('destroy')
            const clone = source.clone(true);
            const debit = clone.find('.debit')
            const credit = clone.find('.credit')
            const chart_of_account = clone.find('.chart-of-accounts')
            clone.find('.mask-amount').val('')
            clone.find('.chart-of-accounts').val('')
            clone.find('.debit').attr('name', `debit[${row_number}]`)
            clone.find('.credit').attr('name', `credit[${row_number}]`)
            clone.find('.debit').val(0)
            clone.find('.credit').val(0)
            clone.find('.chart-of-accounts').attr('name', `object_code[${row_number}]`)
            $('#entry_table tbody').append(clone)
            maskAmount()
            accountingCodesSelect()

            row_number++
        });
        $('#cdr_id').on('change', function(e) {
            e.preventDefault()
            const id = $(this).val()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + "?r=cdr/cdr-entries",
                data: {
                    id: id
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    insertEntryFromDv(res)
                    getTotal()
                }
            })
        })

    })
</script>






<?php
SweetAlertAsset::register($this);

$script = <<< JS

    // $('#JevPreparation').on('submit',function(e){
    //     e.preventDefault()
    //     var \$form = $(this);
    //     $.post(
    //         \$form.attr("action"),
    //         \$form.serialize()
    //     )
    //     .done(function(result){
    //         const res = JSON.parse(result)
    //         if (res.isSuccess){
    //             swal( {
    //                 icon: 'success',
    //                 title: "Successfuly Added",
    //                 type: "success",
    //                 timer:3000,
    //                 closeOnConfirm: false,
    //                 closeOnCancel: false
    //             },function(){
    //                 window.location.href = window.location.pathname + "?r=transaction"
    //             })
    //         }else{
    //             swal( {
    //                 icon: 'error',
    //                 title:'Error',
    //                 text: res.error,
    //                 type: "error",
    //                 timer:10000,
    //                 closeOnConfirm: false,
    //                 closeOnCancel: false
    //             })
    //         }
    //     })

      
    // })       

JS;
$this->registerJs($script);
?>