<?php

use app\models\Books;
use app\models\DvTransactionType;
use app\models\Payee;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\datetime\DateTimePicker;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\JsExpression;

$itemRow = 0;
$payee = [];
if (!empty($model->payee_id)) {
    $payee = ArrayHelper::map(Payee::find()->where('id = :id', ['id' => $model->payee_id])->asArray()->all(), 'id', 'account_name');
}
?>
<div class="test panel-panel-default" style="background-color: white;padding:2rem">




    <div id="container" class="">
        <div class="row">
            <div class="col-sm-12" style="color:red;text-align:center">
                <h4 id="link">
                </h4>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'RoutingForm',
        ]); ?>


        <div class="row">

            <div class="col-sm-3">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'name' => 'reporting_period',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'startView' => "year",
                        'minViewMode' => "months",
                    ]
                ]) ?>
            </div>
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

            <div class="col-sm-3" style="height:60x">
                <?= $form->field($model, 'fk_dv_transaction_type_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(DvTransactionType::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Tranasction Type',
                    ]
                ]) ?>
            </div>

            <div class="col-sm-3">

                <?= $form->field($model, 'book_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Book',

                    ]
                ]) ?>
            </div>


        </div>
        <div class="row">
            <div class="col-sm-3">

                <?= $form->field($model, 'recieved_at')->widget(DateTimePicker::class, [
                    'options' => [
                        'style' => 'background-color:white',
                    ],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd HH:ii P',
                        'autoclose' => true
                    ]
                ]);


                ?>
            </div>
            <div class="col-sm-3" id="payroll_display" style="display: none;">
                <?= $form->field($model, 'payroll_id')->widget(Select2::class, [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=payroll/search-payroll',
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
            <div class="col-sm-3" id="remittance_display" style="display: none;">
                <?= $form->field($model, 'fk_remittance_id')->widget(Select2::class, [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=remittance/search-remittance',
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

            <div class="col-sm-3"></div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'particular')->textarea(['row' => 4]) ?>
            </div>
        </div>

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

                $ttl_amount_disbursed = 0;
                $ttl_vat_nonvat = 0;
                $ttl_ewt_goods_services = 0;
                $ttl_compensation = 0;
                $ttl_other_trust_liabilities = 0;
                foreach ($items as $val) {


                    // echo "<tr>
                    //             <td style='display:none' </td>
                    //             <td style='display:none' ><input value='{$val['process_ors_id']}' type='hidden' name='process_ors_id[$itemRow]'/></td>
                    //             <td> {$val['serial_number']}</td>
                    //             <td> 
                    //             {$val['particular']}
                    //             </td>
                    //             <td> {$val['payee_name']}</td>
                    //             <td> {$val['total']}</td>
                    //             <td>
                    //              <input value='{$val['amount_disbursed']}' name='amount_disbursed[$itemRow]' type='text'  class='amount_disbursed'/>
                    //             </td>
                    //             <td> <input value='{$val['vat_nonvat']}' type='text' name='vat_nonvat[$itemRow]' class='vat'/></td>
                    //             <td> <input value='{$val['ewt_goods_services']}' type='text' name='ewt_goods_services[$itemRow]' class='ewt'/></td>
                    //             <td> <input value='{$val['compensation']}' type='text' name='compensation[$itemRow]' class='compensation'/></td>
                    //             <td> <input value='{$val['other_trust_liabilities']}' type='text' name='other_trust_liabilities[$itemRow]' class='liabilities'/></td>
                    //             <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class='glyphicon glyphicon-minus'></i></button></td></tr>
                    //         ";

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



        <div class="row">
            <div class="col-sm-3 col-sm-offset-5">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:30rem']) ?>
                </div>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

        <div style="display: none;">
            <input type="text" id="transaction_type" name="transaction_type">
            <input type="text" id="dv_count" name="dv_count">
        </div>

        <!-- PROCESS ORS ANG MODEL -->
        <!-- NAA SA CREATE CONTROLLER NAKO GE CHANGE -->
        <?php
        $dataProvider->pagination = ['pageSize' => 10];

        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => 'List of ORS',
            ],
            'floatHeaderOptions' => [
                'top' => 50,
                'position' => 'absolute',
            ],
            'export' => false,
            'pjax' => true,
            'showPageSummary' => true,
            'columns' => [

                [
                    'attribute' => 'serial_number',
                    'contentOptions' => ['class' => 'serial_number'],
                ],
                [
                    'label' => 'Particular',
                    'value' => 'transaction.particular',
                    'contentOptions' => ['class' => 'particular'],
                ],
                [
                    'label' => 'Payee',
                    'attribute' => 'transaction.payee.account_name',
                    'contentOptions' => ['class' => 'payee'],
                ],

                [
                    'label' => 'Book',
                    'attribute' => 'book_id',
                    'value' => 'book.name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => [
                            'allowClear' => true,
                            'placeholder' => 'Select Book'
                        ]
                    ],
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'book'],
                ],

                [
                    'label' => 'Total Obligated',
                    'value' => function ($model) {
                        $query = Yii::$app->db->createCommand("SELECT SUM(process_ors_entries.amount)as total
                            FROM process_ors_entries
                            where process_ors_entries.process_ors_id = :ors_id
                          ")
                            ->bindValue(":ors_id", $model->id)
                            ->queryOne();
                        return $query['total'];
                    },
                    'format' => ['decimal', 2],
                    'pageSummary' => true
                ],
                [
                    'label' => 'Actions',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'AddOrs(this)']) . ' ' .
                            Html::input('hidden', null, $model->id, ['class' => 'ors_id']);
                    },
                ],

            ],
        ]); ?>







    </div>
</div>

<style>
    textarea {
        max-width: 100%;
        width: 100%;
    }

    #items_table td {
        padding: 1rem;
    }

    .grid-view td {
        white-space: normal;
    }




    tfoot th {
        text-align: center;
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
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
?>

<script>
    var vacant = 0;
    var i = 1;
    var x = [0];
    var update_id = undefined;
    var cashflow = [];
    var accounts = [];


    function RemoveItem(q) {

        $(q).closest('tr').remove()
    }






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

    function AddOrs(ths) {
        const src = $(ths).closest('tr')
        const serial_number = src.find('.serial_number').text()
        const particular = src.find('.particular').text()
        const payee = src.find('.payee').text()
        const book = src.find('.book').text()
        const ors_id = src.find('.ors_id').val()


        AddItem(
            ors_id,
            serial_number,
            particular,
            payee,
        )
    }

    function UpdateMainAmount(q) {
        const amt = $(q).maskMoney('unmasked')[0]
        $(q).parent().find('.main-amount').val(amt)
        $(q).parent().find('.main-amount').trigger('change')
    }
    let itemRow = <?= $itemRow ?>;

    function AddItem(
        process_ors_id = null,
        serial_number = '',
        transaction_particular = '',
        transaction_payee = '',
        total = '',
        amt_disbursed = 0,
        vat = 0,
        ewt = 0,
        compensation = 0,
        liabilities = 0
    ) {
        let row = `<tr>`
        if (process_ors_id) {
            row += `<td style='display:none;'>
                        <input  name='items[${itemRow}][process_ors_id]' type='hidden'  value='${process_ors_id}'/>
                    </td>`
        }
        row += `<td> ${[serial_number]}</td>
                            <td> ${[transaction_particular]}</td>
                            <td> ${[transaction_payee]}</td>
                            <td> ${[total]}</td>
                            <td>
                                <input type='text'  class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='${amt_disbursed}'/>
                                <input  name='items[${itemRow}][amount_disbursed]' type='hidden'  class='amount_disbursed main-amount' value='${amt_disbursed}'/>
                            </td>
                            <td> 
                                <input type='text' class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='${vat}'/>
                                <input type='hidden' name='items[${itemRow}][vat_nonvat]' class='vat main-amount' value='${vat}'/>
                            </td>
                            <td> 
                                <input type='text'  class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='${ewt}'/>
                                <input  type='hidden' name='items[${itemRow}][ewt_goods_services]' class='ewt main-amount' value='${ewt}'/>
                            </td>
                            <td>
                                <input type='text'  class='form-control mask-amount'onkeyup='UpdateMainAmount(this)'  value='${compensation}'/>
                                <input  type='hidden' name='items[${itemRow}][compensation]' class='compensation main-amount' value='${compensation}'/>
                            </td>
                            <td> 
                                <input type='text'  class='form-control mask-amount' onkeyup='UpdateMainAmount(this)' value='${liabilities}'/>
                                <input  type='hidden' name='items[${itemRow}][other_trust_liabilities]' class='liabilities main-amount' value='${liabilities}'/>
                            </td>
                            <td>
                                <button  type='button' class='btn-xs btn-danger' onclick='RemoveItem(this)'>
                                    <i class="glyphicon glyphicon-minus"></i>
                                </button>
                            </td>
                        </tr>`
        $('#items_table tbody').append(row);
        itemRow++
        maskAmount()
    }
    $(document).ready(() => {
        maskAmount()
        $('#items_table').on('change', '.main-amount', () => {
            getTotal()
        })
        $('#dvaucs-fk_dv_transaction_type_id').change(() => {
            const type = $('#dvaucs-fk_dv_transaction_type_id :selected').text().toLowerCase()
            $('#items_table tbody').html('');
            if (type == 'payroll') {
                $('#payroll_display').show()
                $('#remittance_display').hide()
            } else if (type == 'remittance') {
                $('#payroll_display').hide()
                $('#remittance_display').show()
            } else if (type != 'single' && type !== 'multiple') {
                AddItem()
            }
            if (type != 'payroll' && type != 'remittance') {
                $('#payroll_display').hide()
                $('#remittance_display').hide()
            }
        })
        $('#dvaucs-book_id').change(function(e) {
            e.preventDefault()
            $('#processorssearch-book_id').val($(this).val()).trigger('change');

        })
        $('#dvaucs-payroll_id').change(function(e) {
            e.preventDefault()
            const id = $('#dvaucs-payroll_id').val()
            $.ajax({
                type: "POST",
                url: window.location.pathname + "?r=payroll/payroll-data",
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    $('#items_table tbody').html('');
                    console.log(res)
                    // amount_disbursed
                    // book_id

                    // payee_id
                    // payroll_number
                    // reporting_period
                    // total_due_to_bir
                    // total_trust_liab
                    // type
                    var data = {
                        id: res.payee_id,
                        text: res.payee
                    };

                    var newOption = new Option(data.text, data.id, true, true);
                    $('#dvaucs-payee_id').prepend(newOption).trigger('change');
                    let ewt_goods_services = 0;
                    let compensation = 0;
                    if (res.type == "2307") {
                        ewt_goods_services = res.total_due_to_bir;
                    } else if (res.type == "1601c") {
                        compensation = res.total_due_to_bir;
                    }
                    AddItem(res.ors_id,
                        res.ors_number,
                        res.particular,
                        res.payee,
                        '',
                        res.amount_disbursed,
                        0,
                        ewt_goods_services,
                        compensation,
                        res.total_trust_liab

                    )

                }
            })


        })





    })
</script>


<?php
SweetAlertAsset::register($this);
$js = <<< JS
    $("#RoutingForm").on("beforeSubmit", function (event) {
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