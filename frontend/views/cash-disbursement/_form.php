<?php

use app\models\Books;
use app\models\DvAucs;
use app\models\DvAucsIndexSearch;
use app\models\ModeOfPayments;
use app\models\RoCheckRanges;
use app\models\VwUndisbursedDvsSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */
/* @var $form yii\widgets\ActiveForm */

$dv = [];
$check_ranges = [];
if (!empty($model->fk_ro_check_range_id)) {
    $check_ranges = ArrayHelper::map(Yii::$app->db->createCommand("SELECT id, CONCAT(ro_check_ranges.from,'-',ro_check_ranges.to) as rng FROM ro_check_ranges WHERE id = :id")
        ->bindValue(':id', $model->fk_ro_check_range_id)
        ->queryAll(), 'id', 'rng');
}
$rowNum = 1;
if (!empty($model->dv_aucs_id)) {



    $dv = ArrayHelper::map(
        Yii::$app->db->createCommand("SELECT dv_aucs.id,dv_aucs.dv_number FROM dv_aucs WHERE dv_aucs.id = :id")
            ->bindValue(':id', $model->dv_aucs_id)
            ->queryAll(),
        'id',
        'dv_number'
    );
}

?>

<div class="cash-disbursement-form ">

    <div class="card " style="padding:2rem;height:100%">
        <ul class="notes">
            <li>Notes</li>
            <li>Select Book And Mode of Payment first before selecting Check Range</li>
            <li>Check Range is automatically generated according to the check range selected</li>
        </ul>
        <?php $form = ActiveForm::begin([
            'id' => $model->formName(),
        ]); ?>

        <div class="row">

            <div class="col-sm-3">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'readonly' => true,
                    'options' => [
                        'style' => 'background-color:white'
                    ],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months',
                        'autoclose' => true
                    ]
                ]) ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'issuance_date')->widget(DatePicker::class, [
                    'readonly' => true,
                    'options' => [
                        'style' => 'background-color:white'
                    ],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'begin_time')->widget(TimePicker::class, []) ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'out_time')->widget(TimePicker::class, []) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-2">
                <?= $form->field($model, 'book_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ]) ?>
            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'fk_mode_of_payment_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(ModeOfPayments::find()->asArray()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => 'Select Mode of Payment',
                    ],
                ]) ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_ro_check_range_id')->widget(Select2::class, [
                    'data' => $check_ranges,
                    'options' => [
                        'placeholder' => 'Select Check Range',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['ro-check-ranges/search-check-range']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { 
                                return {
                                    q:params.term,
                                    page: params.page||1,
                                    book_id:$("#cashdisbursement-book_id").val(),
                                    mode_of_payment_id:$("#cashdisbursement-fk_mode_of_payment_id").val(),

                                    
                                }; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        'placeholder' => 'Select Mode of Payment',
                    ],
                ]) ?>

            </div>

        </div>

        <table class="table" id="itemsTbl">
            <thead>

                <th>Book</th>
                <th>DV No.</th>
                <th>Particular</th>
                <th>Payee</th>
                <th>Amount Disbursed</th>
                <th>Withholding Tax</th>
                <th>Gross Amount</th>
                <th>ORS</th>
                <th>UACS</th>
            </thead>
            <tbody>

                <?php
                foreach ($items as $itm) {


                    echo "<tr>
                            <td class='hidden'><input type='text' class='item_id' name='items[$rowNum][item_id]' value='{$itm['itemId']}'></td>
                            <td class='hidden'><input type='text' class='dv_id' name='items[$rowNum][dv_id]' value='{$itm['fk_dv_aucs_id']}'></td>
                            <td>{$itm['book_name']}</td>
                            <td>{$itm['dv_number']}</td>
                            <td>{$itm['particular']}</td>
                            <td>{$itm['payee']}</td>
                            <td>{$itm['ttlAmtDisbursed']}</td>
                            <td>{$itm['ttlTax']}</td>
                            <td>{$itm['grossAmt']}</td>
                            <td>{$itm['orsNums']}</td>
                            
                            
                            
                           <td style='min-width:200px'>
                            <select class='cash-chart-of-accounts' style='width:100%' required name='items[$rowNum][chart_of_acc_id]'>
                                <option value='{$itm['fk_chart_of_account_id']}'>{$itm['chart_of_acc']}</option>
                            </select>
                           </td>
                            <td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>
                        </tr>";
                    $rowNum++;
                }
                ?>
            </tbody>
        </table>

        <div class="row" style="margin-top: 5rem;">
            <div class="col-sm-3 col-sm-offset-5">
                <div class="form-group " style="width:10rem">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success submit_cash', 'style' => 'width:30rem']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <?php

    $cols =  [

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return  Html::input('text', 'items[dv_id]', $model->id, ['class' => 'dv_id']);
            },
            'hidden' => true
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add-action', 'onClick' => 'AddItem(this)']);
            },
        ],

        [
            'attribute' =>  'bookFilter',
            'hidden' => true
        ],
        'book_name',
        'dv_number',
        'particular',
        'payee',
        [
            'attribute' => "ttlAmtDisbursed",
            'format' => ['decimal', 2],

        ],

        [
            'attribute' => "ttlTax",
            'format' => ['decimal', 2],

        ],

        [
            'attribute' => "grossAmt",
            'format' => ['decimal', 2],

        ],
        'orsNums',

    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dvDataProvider,
        'filterModel' => $dvSearchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of DV',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],

        'pjax' => true,
        'export' => false,
        'columns' => $cols
    ]); ?>
</div>
<style>
    .disbursed {
        display: none;
    }

    .hidden {
        display: none;
    }

    .danger {
        color: red;
    }

    .notes>li {
        color: red;
    }

    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>
<?php

$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let rowNum = <?= $rowNum ?>;
    let cash_chart_of_accounts = []

    function AddItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.dv_id').attr('name', `items[${rowNum}][dv_id]`)
        clone.find('.add-action').parent().remove()
        clone.append(`<td style='min-width:200px'><select class='cash-chart-of-accounts' style='width:100%' required name='items[${rowNum}][chart_of_acc_id]'><option></option></select></td>`)
        clone.append("<td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>")
        $('#itemsTbl').append(clone)

        rowNum++
        CashChartOfAccountsSelect()
    }

    function remove(ths) {
        $(ths).closest('tr').remove()
    }

    function CashChartOfAccountsSelect() {
        $(".cash-chart-of-accounts").select2({
            data: cash_chart_of_accounts,
            placeholder: "Select a Chart of Account",
            allowClear: true
        })
    }
    async function getCharts() {
        await $.ajax({
            type: 'GET',
            url: window.location.pathname + '?r=cash-disbursement/cash-chart-of-accounts',
            data: {},
            success: function(data) {
                cash_chart_of_accounts = JSON.parse(data)

            }
        })
        CashChartOfAccountsSelect()
    }

    function bookFilter(book) {
        $('input[name^="VwUndisbursedDvsSearch[bookFilter]').val(book).trigger('change')
    }
    $(document).ready(function() {
        getCharts()
        window.onload = function() {
            bookFilter("<?= $model->book->name ?? '' ?>")
        };
        $('#cashdisbursement-fk_mode_of_payment_id').change(() => {
            $('#cashdisbursement-fk_ro_check_range_id').val(null).trigger('change');
        })
        $('#cashdisbursement-book_id').change(() => {
            $('#cashdisbursement-fk_ro_check_range_id').val(null).trigger('change');
            const book = $('#cashdisbursement-book_id :selected').text()

            bookFilter(book)
        })



    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#CashDisbursement").on("beforeSubmit", function (event) {
    event.preventDefault();
    $(".submit_cash").attr('disabled',true)
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            $(".submit_cash").attr('disabled',false)
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