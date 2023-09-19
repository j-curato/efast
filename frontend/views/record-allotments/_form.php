<?php

use app\models\AllotmentType;
use app\models\AuthorizationCode;
use app\models\Divisions;
use app\models\DocumentRecieve;
use app\models\FinancingSourceCode;
use app\models\FundCategoryAndClassificationCode;
use app\models\FundClusterCode;
use app\models\FundSource;
use app\models\MfoPapCode;
use app\models\Office;
use aryelds\sweetalert\SweetAlertAsset;
use common\models\Books;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */
/* @var $form yii\widgets\ActiveForm */

$itmRow = 1;
$office = ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name');
$divisions = ArrayHelper::map(Divisions::find()->asArray()->all(), 'id', 'division');
$allotment_type = ArrayHelper::map(AllotmentType::find()->asArray()->all(), 'id', 'type');
$fund_cluster_codes = ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name');
$document_recieves = ArrayHelper::map(DocumentRecieve::find()->asArray()->all(), 'id', 'name');
$financing_source_codes = ArrayHelper::map(FinancingSourceCode::find()->select(["id", "CONCAT(name,'-',description) as `name`"])->asArray()->all(), 'id', 'name');
$authorization_codes = ArrayHelper::map(AuthorizationCode::find()->asArray()->all(), 'id', 'name');
$mfo_codes = ArrayHelper::map(MfoPapCode::find()->asArray()->all(), 'id', 'name');
$fund_source = ArrayHelper::map(FundSource::find()->asArray()->all(), 'id', 'name');
$books = ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name');
$fund_categories = ArrayHelper::map(FundCategoryAndClassificationCode::find()->asArray()->all(), 'id', 'name');
?>

<div class="record-allotments-form card " style="padding:3rem">

    <?php $form = ActiveForm::begin([
        'id' => 'RecordAllotmentForm',
    ]); ?>


    <div class="row">

        <div class="col-sm-3">
            <?= $form->field($model, 'date_issued')->widget(
                DatePicker::class,
                [

                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]
            );
            ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'valid_until')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ],
            ]);
            ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm',
                    'minViewMode' => "months",
                ]
            ]);
            ?>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'office_id')->widget(
                Select2::class,
                [
                    'data' => $office,
                    'pluginOptions' => [
                        'placeholder' => 'Select Office',
                    ]
                ]
            );
            ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'division_id')->widget(
                Select2::class,
                [
                    'data' => $divisions,
                    'pluginOptions' => [
                        'placeholder' => 'Select Division',
                    ]
                ]
            );
            ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'allotment_type_id')->widget(
                Select2::class,
                [
                    'data' => $allotment_type,
                    'pluginOptions' => [
                        'placeholder' => 'Select Allotment Type',
                    ]
                ]
            );
            ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'document_recieve_id')->widget(Select2::class, [
                'data' => $document_recieves,
                'pluginOptions' => [
                    'placeholder' => 'Select Document Recieve',

                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'book_id')->widget(Select2::class, [
                'data' => $books,
                'pluginOptions' => [
                    'placeholder' => 'Select Book',

                ]
            ]) ?>
        </div>
        <div class="col-sm-3" style="height:60x">
            <?= $form->field($model, 'fund_cluster_code_id')->widget(Select2::class, [
                'data' => $fund_cluster_codes,
                'pluginOptions' => [
                    'placeholder' => 'Select Fund Cluster Code',

                ]
            ]) ?>
        </div>


        <div class="col-sm-3">
            <?= $form->field($model, 'financing_source_code_id')->widget(Select2::class, [
                'data' => $financing_source_codes,
                'pluginOptions' => [
                    'placeholder' => 'Select Financing Source Code',

                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'authorization_code_id')->widget(Select2::class, [
                'data' => $authorization_codes,
                'pluginOptions' => [
                    'placeholder' => 'Select Financing Source Code',

                ]
            ]) ?>
        </div>
    </div>

    <div class=" row">

        <div class="col-sm-3">
            <?= $form->field($model, 'mfo_pap_code_id')->widget(Select2::class, [
                'data' => $mfo_codes,
                'pluginOptions' => [
                    'placeholder' => 'Select MFO/PAP Code',

                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fund_source_id')->widget(Select2::class, [
                'data' => $fund_source,
                'pluginOptions' => [
                    'placeholder' => 'Select Fund Source',

                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fund_classification')->textInput() ?>
        </div>

        <div class="col-sm-3">

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'particulars')->textarea(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="container">

        <table class="table " id="items">

            <thead>
                <th>Chart of Account</th>
                <th>Amount</th>
            </thead>
            <tbody>
                <?php
                if (!empty($items)) {

                    foreach ($items as $item) {
                        $general_ledger = $item['uacs'] . '-' . $item['general_ledger'];
                        $chart_of_account_id = $item['chart_of_account_id'];
                        echo "<tr>
                    <td> 
                        <input type='hidden' value='{$item['item_id']}' class='item_id' name='items[{$itmRow}][item_id]'>
                        <select name='items[$itmRow][chart_of_account_id]' class='chart-of-account-select form-control' style='width: 100%'>
                            <option value='{$chart_of_account_id}'>$general_ledger</option>
                        </select>
                    </td>
                    <td style='max-width: 5rem;'>
                        <input type='text' class='mask-amount form-control' placeholder='amount' onkeyup='UpdateMainAmt(this)' value='" . number_format($item['amount'], 2) . "'>
                        <input type='hidden' name='items[$itmRow][amount]' class=' main-amount' value='{$item['amount']}'>
                    </td>
                    <td style='max-width: 5rem; text-align:left'>
                        <button type='button' class='add-entry btn btn-success btn-xs'>
                            <i class='fa fa-plus'></i>
                        </button>
                        <button type='button' class='remove btn btn-danger btn-xs' style=' text-align: center; ' onClick='removeItem(this)'>
                            <i class=' fa fa-times'></i>
                        </button>

                    </td>
                </tr>";
                        $itmRow++;
                    }
                } else {


                ?>
                    <tr>
                        <td> <select name="items[0][chart_of_account_id]" class="chart-of-account-select form-control" style="width: 100%">
                                <option></option>
                            </select>
                        </td>
                        <td style="max-width: 5rem;">
                            <input type="text" class="mask-amount form-control" placeholder="amount" onkeyup='UpdateMainAmt(this)'>
                            <input type="hidden" name="items[0][amount]" class=" main-amount">
                        </td>
                        <td style="max-width: 2rem; text-align:left">
                            <button type="button" class='add-entry btn btn-success btn-xs'>
                                <i class="fa fa-plus"></i>
                            </button>
                            <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; " onClick="removeItem(this)">
                                <i class="fa fa-times"></i>
                            </button>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>
    <div class="row justify-content-center">
        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
<style>
    #items>td {
        max-width: 100rem;
    }
</style>
<?php

$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let itmRow = <?= $itmRow ?>;

    function ChartOfAccountSelect() {
        $(".chart-of-account-select").select2({
            ajax: {
                url: base_url + "?r=chart-of-accounts/search-chart-of-accounts",
                dataType: "json",
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
            },
        });
    }

    function UpdateMainAmt(ths) {
        $(ths).parent().find('.main-amount').val($(ths).maskMoney('unmasked')[0])
        $(ths).parent().find('.main-amount').trigger('change')
    }

    function removeItem(ths) {
        $(ths).closest('tr').remove()
    }

    function addEntry() {
        const row = `<tr>
            <td> <select required name="items[${itmRow}][chart_of_account_id]" class="chart-of-account-select form-control" style="width: 100%">
                    <option></option>
                </select>
            </td>
            <td style="max-width: 5rem;">
                <input type="text" class="mask-amount form-control" placeholder="amount" onkeyup='UpdateMainAmt(this)'>
                <input type="hidden" name="items[${itmRow}][amount]" class="main-amount">
            </td>
            <td style="max-width: 1rem; text-align:left"> 
                   <button type="button" class='add-entry btn btn-success btn-xs' >
                    <i class="fa fa-plus"></i>
                </button>
                <button type="button" class='remove btn btn-danger btn-xs' onClick="removeItem(this)"">
                    <i class="fa fa-times"></i>
                </button>
              
            </td>
        </tr>`
        $("#items tbody").append(row)
        itmRow++
        ChartOfAccountSelect()
        maskAmount()
    }
    $(document).ready(() => {
        ChartOfAccountSelect()
        maskAmount()
        $('#items').on('click', '.add-entry', () => {
            addEntry()
            const source = $(this).closest('tr')
            const clone = source.clone()

        })

    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#RecordAllotmentForm").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            console.log(res)
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