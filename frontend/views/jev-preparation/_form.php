<?php

use app\models\Books;
use app\models\DvAucs;
use app\models\ResponsibilityCenter;
use aryelds\sweetalert\SweetAlert;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$payee = [];
$entry_row = 1;
$dv_aucs = [];
if (!empty($model->payee_id)) {
    $payee_query = Yii::$app->db->createCommand("SELECT id,account_name FROM payee WHERE id = :id")->bindValue(':id', $model->payee->id)->queryAll();
    $payee = ArrayHelper::map($payee_query, 'id', 'account_name');
}
if (!empty($model->fk_dv_aucs_id)) {
    $dv_aucs = ArrayHelper::map(DvAucs::find()->where('id =:id', ['id' => $model->fk_dv_aucs_id])->asArray()->all(), 'id', 'dv_number');
}
?>
<div class="test" style="background-color:white;border:1px solid black;padding:20px">



    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_dv_aucs_id')->widget(Select2::class, [
                'data' => $dv_aucs,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['dv-aucs/search-disbursed-dvs']), // Correct route to the action
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,page: params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
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
        <!-- <div class="col-sm-3">
            <label for="total_disbursed"> Total Disbursed</label>
            <h4 id="total_disbursed"></h4>
        </div> -->
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

    </div>
    <div class="row">


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
        <div class="col-sm-3">
            <?= $form->field($model, 'check_ada_number')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'explaination')->textarea(['cols' => "151", 'rows' => "3"]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col  align-self-end">
            <button type='button' class='add btn btn-success btn-xs' style=' text-align: center; float:right;margin-right:5px'><i class='fa fa-plus'></i> Add</button>
        </div>
    </div>
    <table class="table" id='entry_table'>
        <tbody>
            <?php
            foreach ($model->getItems() as $val) {
                $debit = $val['debit'];
                $credit = $val['credit'];
                $object_code = $val['object_code'];
                $account_title = $val['account_title'];
                echo "<tr>
                        <td style='width: 300px;max-width:300px'>";
                echo !empty($val['item_id']) ? "  <input type='hidden' name='items[$entry_row][item_id]' value='{$val['item_id']}'>" : '';
                echo   "
                            <div>
                                <label for='chart-of-accounts'>UACS</label>
                                <select required name='items[$entry_row][object_code]' class='chart-of-accounts' style='width: 100%'>
                                    <option value='$object_code' selected>$object_code - $account_title</option>
                                </select>
                            </div>
                        </td>
                        <td style='width: 150px;'>
                             <label for='debit'>Debit</label>
                            <input type='text' class='  mask-amount' placeholder='Debit' value='" . number_format($debit, 2) . "'>
                            <input type='hidden' name='items[$entry_row][debit]' class='debit main_amount' placeholder='Debit' value='$debit'>
                        </td>
                        <td style='width: 150px;'>
                        <label for='credit'>Credit</label>
                            <input type='text' class='  mask-amount' placeholder='Credit' value='" . number_format($credit, 2) . "'>
                            <input type='hidden' name='items[$entry_row][credit]' class='credit main_amount' placeholder='Credit' value='$credit'>
                        </td>
                        <td style='width: 50px;'>
                            <button type='button' class='remove btn btn-danger btn-xs' style=' text-align: center; float:right;'><i class='fa fa-times'></i></button>
                            <button type='button' class='add btn btn-success btn-xs' style=' text-align: center; float:right;margin-right:5px'><i class='fa fa-plus'></i></button> 
                        </td>
                    </tr>";
                $entry_row++;
            }


            ?>

        </tbody>
        <tfoot>

            <tr>
                <th style="text-align: center;">Total</th>
                <th><span class="total_debit"></span></th>
                <th><span class="total_credit"></span></th>
            </tr>
        </tfoot>

    </table>
    <div class="row justify-content-center" style="margin-top: 4rem;">

        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%;']) ?>
        </div>

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

$this->registerJsFile("@web/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);
?>

<script>
    let row_number = <?= $entry_row ?>;

    function addItem() {
        let r = `<tr>
                        <td style='width: 300px;max-width:300px'>
                            <div>
                                <label for='chart-of-accounts'>UACS</label>
                                <select required name='items[${row_number}][object_code]' class='chart-of-accounts' style='width: 100%'>
                                    <option ></option>
                                </select>
                            </div>
                        </td>
                        <td style='width: 150px;'>
                        <label for='debit'>Debit</label>
                            <input type='text' class='  mask-amount' placeholder='Debit' >
                            <input type='hidden' name='items[${row_number}][debit]' class='debit main_amount' placeholder='Debit' >
                        </td>
                        <td style='width: 150px;'>
                        <label for='credit'>Credit</label>
                            <input type='text' class='  mask-amount' placeholder='Credit' >
                            <input type='hidden' name='items[${row_number}][credit]' class='credit main_amount' placeholder='Credit' >
                        </td>
                        <td style='width: 50px;'>
                            <button type='button' class='remove btn btn-danger btn-xs' style=' text-align: center; float:right;'><i class='fa fa-times'></i></button>
                            <button type='button' class='add btn btn-success btn-xs' style=' text-align: center; float:right;margin-right:5px'><i class='fa fa-plus'></i></button> 
                        </td>
                    </tr>`;
        $('#entry_table tbody').append(r)
        maskAmount()
        accountingCodesSelect()
        row_number++;
    }

    function getTotal() {
        let total_credit = 0;
        let total_debit = 0;
        $('.credit').each(function() {
            total_credit += Number($(this).val());
        });
        $('.debit').each(function() {
            total_debit += Number($(this).val());
        });
        $('.total_debit').text(thousands_separators(total_debit))
        $('.total_credit').text(thousands_separators(total_credit))
    }


    function DisplayDvDetails(data) {
        $('#jevpreparation-book_id').val(data.book_id).trigger('change')
        $('#jevpreparation-ref_number').prop('disabled', true)
        $("#jevpreparation-book_id option:not(:selected)").attr("disabled", true)
        let payeeSelect = $('#jevpreparation-payee_id');
        const option = new Option([data.payee_name], [data.payee_id], true, true);
        payeeSelect.append(option).trigger('change');
        $('#jevpreparation-explaination').val(data.particular)
        $('#jevpreparation-reporting_period').val(data.reporting_period)
        $('#jevpreparation-check_ada_number').val(data.check_number)

        // if (data.mode_of_payment.toLowerCase() == 'ada') {
        //     $("#jevpreparation-check_ada").val('ADA').trigger('change')
        //     $("#jevpreparation-ada_number").val(data.ada_number).trigger('change')

        // } else {

        //     $("#jevpreparation-check_ada").val('Check').trigger('change')
        //     $("#jevpreparation-ada_number").val(data.check_or_ada_no).trigger('change')
        // }
        // $("#jevpreparation-check_ada option:not(:selected)").attr("disabled", true)
    }

    function DisplayDvEntries(data) {

        $.each(data, function(key, val) {
            let r = `<tr>
                        <td style='width: 300px;max-width:300px'>
                            <div>
                                <label for='chart-of-accounts'>UACS</label>
                                <select required name='items[${row_number}][object_code]' class='chart-of-accounts' style='width: 100%'>
                                    <option value='${val.object_code}'>${val.object_code}-${val.account_title}</option>
                                </select>
                            </div>
                        </td>
                        <td style='width: 150px;'>
                        <label for='debit'>Debit</label>
                            <input type='text' class='  mask-amount' placeholder='Debit'  value='${thousands_separators(val.debit)}'>
                            <input type='hidden' name='items[${row_number}][debit]' class='debit main_amount'  value='${val.debit}'>
                        </td>
                        <td style='width: 150px;'>
                        <label for='credit'>Credit</label>
                            <input type='text' class='  mask-amount' placeholder='Credit' value='${thousands_separators(val.credit)}'>
                            <input type='hidden' name='items[${row_number}][credit]' class='credit main_amount'  value='${val.credit}'>
                        </td>
                        <td style='width: 50px;'>
                            <button type='button' class='remove btn btn-danger btn-xs' style=' text-align: center; float:right;'><i class='fa fa-times'></i></button>
                            <button type='button' class='add btn btn-success btn-xs' style=' text-align: center; float:right;margin-right:5px'><i class='fa fa-plus'></i></button> 
                        </td>
                    </tr>`;
            $('#entry_table tbody').append(r)
            row_number++;

        })

        maskAmount()
        accountingCodesSelect()
    }
    $(document).ready(function() {
        accountingCodesSelect()
        maskAmount()
        getTotal()


        $('#entry_table').on('change keyup', '.mask-amount', function() {
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('td');
            source.find('.main_amount').val(amount)
            getTotal()
        })

        $('#entry_table').on('click', '.remove', function() {
            event.preventDefault();
            $(this).closest('tr').remove();
        })

        $('#entry_table').on('click', '.add', function() {
            addItem()
        })
        $('.add').on('click', function(event) {
            addItem()
        });
        $("#jevpreparation-fk_dv_aucs_id").change(function() {
            $.ajax({
                type: "POST",
                url: window.location.pathname + "?r=jev-preparation/get-dv-details",
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    console.log(res)

                    DisplayDvDetails(res.dv_details)
                    DisplayDvEntries(res.dv_entries)
                    // getTotal()
                }
            })

        })

        $('#cdr_id').on('change', function(e) {
            e.preventDefault()
            const id = $(this).val()
            console.log('qwe')
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
    $(document).ready(function(){
        $("#JevPreparation").on("beforeSubmit", function (event) {
            event.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function (data) {
                    swal({
                        icon: 'error',
                        title: 'Save Failed',
                        text:data,
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
    })
   

JS;
$this->registerJs($script);
?>