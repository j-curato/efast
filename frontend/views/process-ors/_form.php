<?php

use app\models\Books;
use app\models\Transaction;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrs */
/* @var $form yii\widgets\ActiveForm */

$orsRowNum = 0;
$orsTxnRow = 0;
?>

<div class="process-ors-form" class='card' style="background-color: white;padding:2rem">

    <?php $form = ActiveForm::begin([
        'id' => 'orsForm',
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
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'name' => 'date',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'mm-dd-yyyy',
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
            <?= $form->field($model, 'transaction_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Transaction::find()->where('id =:id', ['id' => $model->transaction_id])->asArray()->all(), 'id', 'tracking_number'),
                'options' => ['placeholder' => 'Search Transaction Number ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=transaction/search-transaction-paginated',
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

    </div>
    <table id="txn_allotments" class="table">
        <thead>
            <tr>
                <th style="text-align:left">
                    <button type="button" class="btn btn-warning refresh"><i class='fa fa-refresh'></i> Refresh Transaction Allotments</button>
                </th>
            </tr>
            <tr class="info">

                <th colspan="11">
                    <h4>
                        <b> Transaction Allotments</b>
                    </h4>
                </th>
            </tr>
            <th>Responsible Center</th>
            <th>Particular</th>
            <th>Payee</th>
            <th>Allotment Number</th>
            <th>MFO/PAP</th>
            <th>Fund Source</th>
            <th>UACS</th>
            <th>Balance</th>
        </thead>
        <tbody>

            <?php
            $txnTtl = 0;
            if (!empty($orsTxnAllotments)) {
                foreach ($orsTxnAllotments as $item) {
                    $item_id = $item['item_id'];
                    $transactionItemId = $item['transactionItemId'];
                    $particular = $item['particular'];
                    $allotment_number = $item['allotment_number'];
                    $txnItemAmt = $item['txnItemAmt'];
                    $balance = $item['balance'];
                    $responsibilityCenter = $item['responsibilityCenter'];
                    $mfo_name = $item['mfo_name'];
                    $fund_source_name = $item['fund_source_name'];
                    $account_title = $item['account_title'];
                    $uacs = $item['uacs'];
                    $book_name = $item['book_name'];
                    $payee = $item['payee'];
                    $itemAmt = $item['itemAmt'];

                    echo "<tr>
                    <td style='display:none;'><input type='hidden' name='orsTxnItems[$orsTxnRow][item_id]' value='$item_id'></td>
                    <td style='display:none;'><input type='hidden' name='orsTxnItems[$orsTxnRow][txnItemId]' value='$transactionItemId'></td>
                    <td>$responsibilityCenter</td>
                    <td>$particular</td>
                    <td>$payee</td>
                    <td>$allotment_number</td>
                    <td>$mfo_name</td>
                    <td>$fund_source_name</td>
                    <td>$uacs - $account_title</td>
                    <td>$balance</td>
                    <td> 
                        <input type='text' class='mask-amount form-control txnAmt' onkeyup='UpdateMainAmount(this)' value='$itemAmt'>
                        <input type='hidden' name='orsTxnItems[$orsTxnRow][txnAmount]' class='txnAmount main-amount ' value='$itemAmt'>
                    </td>
                </tr>";
                    $txnTtl += floatval($itemAmt);
                    $orsTxnRow++;
                }
            }

            ?>
        </tbody>
        <tfoot>
            <tr class="warning">
                <th colspan="8">
                    Total
                </th>
                <th class="txnAllotTtl"><?= number_format($txnTtl, 2) ?></th>
            </tr>
        </tfoot>
    </table>
    <table class="table " id="orsEntriesTbl">
        <thead>
            <tr class="info">
                <th colspan="7">
                    <h4>
                        <b>Record Allotments</b>
                    </h4>
                </th>
            </tr>
            <th>Reporting Period</th>
            <th>MFO/PAP Code</th>
            <th>Fund Source</th>
            <th>Balance</th>

            <th>Object Code</th>
            <th>General Ledger</th>

            <th>Amount</th>
        </thead>
        <tbody>
            <?php
            $orsItmsTtl = 0;
            foreach ($GetOrsItems as $itm) {

                $reporting_period = $itm['reporting_period'];
                $orsItmMfoCode = $itm['mfo_code'];
                $orsItmMfoCame = $itm['mfo_name'];
                $orsItmFundSource = $itm['fund_source'];
                $orsItemGenLed = $itm['general_ledger'];
                $chart_of_account_id = $itm['chart_of_account_id'];
                $amount = $itm['amount'];
                $allotment_id = $itm['allotment_id'];
                $orsItemUacs =  $itm['uacs'];
                $balance =  $itm['balance'];
                echo "<tr>
                    <td>
                        <span class='reporting_period'>$reporting_period</span>
                    </td>
                    <td style='display:none'>
                        <span class='allotment_id'>$allotment_id</span>
                        <span class='chart-of-accounts'>$chart_of_account_id </span>
                    </td>
                    <td>
               <span class='mfo_name'>$orsItmMfoCame</span></td>
                    <td><span class='fund_source'>$orsItmFundSource</span></td>
                    <td>" . number_format($balance, 2) . "</td>

                    <td> 
                      <span class='uacs'>$orsItemUacs</span>-
                      <span class='general_ledger'>$orsItemGenLed</span>
                   
                      
                    </td>
                    <td> 
                      <span> " . number_format($amount, 2) . "</span>
                      <span class='orsItmAmt' style='display:none'> $amount</span>
                    </td>
                    <td>  
                        <a class='btn-xs btn-success copy' type='button' onclick='cpyOrs(this)'><i class='fa fa-copy '></i></a>
                        
                    </td>
                </tr>";
                $orsItmsTtl += floatval($amount);
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="warning">
                <th colspan="5">Total</th>
                <th colspan="1" class="orsItmTtl"><?= number_format($orsItmsTtl, 2) ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <div class="row justify-content-center">
        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
    <?php
    $dataProvider->pagination = ['pageSize' => 10];
    $col = [
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return  Html::input('text', 'orsItems[allotment_id]', $model->allotment_entry_id, ['class' => 'allotment_id']);
            },
            'hidden' => true
        ],

        'budget_year',
        'office_name',
        'division',
        'book_name',
        [
            'attribute' => 'reporting_period',
            'contentOptions' => ['class' => 'reporting_period'],
        ],
        [
            'attribute' => 'allotmentNumber',
            'contentOptions' => ['class' => 'serial_number'],
        ],
        [
            'attribute' => 'mfo_code',
            'contentOptions' => ['class' => 'mfo_code'],
            'hidden' => true
        ],
        [
            'attribute' => 'mfo_name',
            'contentOptions' => ['class' => 'mfo_name'],
        ],
        [
            'attribute' => 'fund_source_name',
            'contentOptions' => ['class' => 'fund_source'],
        ],
        [
            'attribute' => 'chart_of_account_id',
            'contentOptions' => ['class' => 'chart_of_account_id'],
            'hidden' => true
        ],
        [
            'attribute' => 'uacs',
            'contentOptions' => ['class' => 'uacs'],
        ],
        [
            'attribute' => 'account_title',
            'contentOptions' => ['class' => 'general_ledger'],
        ],
        [
            'attribute' => 'amount',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'attribute' => 'balance',
            'format' => ['decimal', 2],
            'hAlign' => 'right',
        ],
        [
            'attribute' => 'balAfterObligation',
            'format' => ['decimal', 2],
            'hAlign' => 'right',
            'contentOptions' => ['class' => 'balance'],

        ],
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'AddOrsItem(this)']);
            },
        ],


    ];

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
        ],
        'export' => false,
        'pjax' => true,


        'columns' => $col
    ]); ?>
</div>
<style>
    th,
    td {
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    .copy {
        padding: 5px;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let orsRowNum = <?= $orsRowNum ?>;
    let orsTxnRow = <?= $orsTxnRow ?>;
    let txnType = '<?= $txnType ?>';

    function ChartOfAccSelect() {
        $(".chart-of-accounts").select2({

            ajax: {
                url: window.location.pathname + "?r=chart-of-accounts/search-allotment-general-ledger",
                dataType: "json",
                data: function(params) {
                    return {
                        q: params.term,
                        base_uacs: $(this).attr('base-uacs'),
                        page: params.page
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

    function OrsDisAddOrCpyItm(
        mfo_code,
        mfo_name,
        fund_source,
        allotment_id,
        uacs,
        general_ledger,
        chart_of_account_id,
        balance,
        copy = false
    ) {
        let baseOption = '';
        let baseUacs = uacs
        if (uacs !== '5010000000' && uacs != '5020000000' && uacs != '5060000000') {
            baseOption = `<option value='${chart_of_account_id}'>${uacs}-${general_ledger}</option>`
        }
        let isDisable = ''
        if (txnType == 'create') {
            isDisable = 'disabled'
        }
        if (!copy) {
            if (uacs.startsWith('501')) {
                baseUacs = 5010000000
            } else if (uacs.startsWith('502')) {
                baseUacs = 5020000000
            } else if (uacs.startsWith('506')) {
                baseUacs = 5060000000
            }

        }

        let row = `<tr>
            <td><input type='month' name='orsItems[${orsRowNum}][reporting_period]' ${isDisable} class='reporting_period'></td>
            <td style='display:none'>
                <input type='text' name='orsItems[${orsRowNum}][allotment_id]' value='${allotment_id}'>
                <span class='allotment_id'>${allotment_id}</span>
                <span class='chart_of_account_id'>${chart_of_account_id}</span>
                <span class='uacs'>${uacs}</span>
                <span class='general_ledger'>${general_ledger}</span>
            </td>
            <td>
                <span class='mfo_name'>${mfo_name}</span>
              
           </td>
            <td>
                <span class='fund_source'>${fund_source}</span>
            </td>
            <td>${balance}</td>
            <td> 
                <select  required name="orsItems[${orsRowNum}][chart_of_account_id]" class="chart-of-accounts" style="width: 100%" base-uacs ='${baseUacs}'>
               ${baseOption}
                </select>
            </td>
            <td> 
                <input type='text' class='mask-amount form-control' onkeyup='UpdateMainAmount(this)'>
                <input type='hidden' name='orsItems[${orsRowNum}][gross_amount]' class='main-amount gross_amount orsItmAmt' >
            </td>
            <td>  
                <a class='btn-xs btn-success copy' type='button' onclick='cpyOrs(this)'><i class="fa fa-copy "></i></a>
                <button type="button" class="remove btn-xs btn-danger" onclick='rmvRow(this)'><i class="fa fa-times "></i></button>
             </td>
        </tr>`

        $('#orsEntriesTbl tbody').append(row)
        orsRowNum++
        ChartOfAccSelect()
        maskAmount()
    }

    function AddOrsItem(ths) {
        const source = $(ths).closest('tr')
        let mfo_code = source.find('.mfo_code').text()
        let mfo_name = source.find('.mfo_name').text()
        let fund_source = source.find('.fund_source').text()
        let allotment_id = source.find('.allotment_id').val()
        let uacs = source.find('.uacs').text()
        let general_ledger = source.find('.general_ledger').text()
        let chart_of_account_id = source.find('.chart_of_account_id').text()
        let balance = source.find('.balance').text()
        OrsDisAddOrCpyItm(
            mfo_code,
            mfo_name,
            fund_source,
            allotment_id,
            uacs,
            general_ledger,
            chart_of_account_id,
            balance
        )

    }

    function UpdateMainAmount(q) {
        $(q).parent().find('.main-amount').val($(q).maskMoney('unmasked')[0])
        $(q).parent().find('.main-amount').trigger('change')
    }

    function DisTxnAllotments(data) {
        $('#txn_allotments tbody').html('')
        $.each(data, (key, val) => {
            let bal = thousands_separators(val.balance)
            const row = `<tr>
                <td style='display:none;'><input type='hidden' name='orsTxnItems[${orsTxnRow}][txnItemId]' value='${val.transactionItemId}'></td>
                <td>${val.responsibilityCenter}</td>
                <td>${val.particular}</td>
                <td>${val.payee}</td>
                <td>${val.allotment_number}</td>
                <td>${val.mfo_name}</td>
                <td>${val.fund_source_name}</td>
                <td>${val.uacs} - ${val.account_title}</td>
                <td class='amount'>${bal}</td>
                <td> 
                    <input type='text' class='mask-amount form-control' onkeyup='UpdateMainAmount(this)'>
                    <input type='hidden' name='orsTxnItems[${orsTxnRow}][txnAmount]' class='txnAmount main-amount'>
                </td>
                <td>  
                    <button type="button" class="remove btn-xs btn-danger" onclick='rmvRow(this)'><i class="fa fa-times "></i></button>
                </td>
            </tr>`;
            $('#txn_allotments tbody').append(row)
            orsTxnRow++
        })
        maskAmount()
    }

    function rmvRow(ths) {
        $(ths).closest('tr').remove()
    }

    function cpyOrs(ths) {
        const source = $(ths).closest('tr')
        const sourceChart = source.find('.chart-of-accounts')
        let chart_of_account_id = ''
        let uacs = ''
        let general_ledger = ''
        if (sourceChart.is('select')) {
            let s = source.find('.chart-of-accounts :selected')
            chart_of_account_id = s.val()
            let sptTxt = s.text().split("-")
            uacs = sptTxt[0];
            general_ledger = sptTxt.slice(1).join("-")

        } else {
            uacs = source.find('.uacs').text()
            general_ledger = source.find('.general_ledger').text()
            chart_of_account_id = source.find('.chart-of-accounts').text()

        }
        let mfo_code = source.find('.mfo_code').text()
        let mfo_name = source.find('.mfo_name').text()
        let fund_source = source.find('.fund_source').text()
        let allotment_id = source.find('.allotment_id').text()

        OrsDisAddOrCpyItm(
            mfo_code,
            mfo_name,
            fund_source,
            allotment_id,
            uacs,
            general_ledger,
            chart_of_account_id,
            '',
            true
        )

    }

    function GetOrsTtl() {
        console.log("qweqwe")
    }

    function GetTxnAllot() {
        const txnId = $('#processors-transaction_id').val()

        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=process-ors/get-txn-allotments',
            data: {
                id: txnId
            },
            success: function(data) {
                const res = JSON.parse(data)
                DisTxnAllotments(res)
            }
        })
    }
    $(document).ready(() => {

        maskAmount()
        ChartOfAccSelect()
        $(".refresh").click((e) => {
            e.preventDefault()
            GetTxnAllot()
        })
        $('#processors-transaction_id').change(() => {
            GetTxnAllot()

        })

        $('#txn_allotments').on('change', '.txnAmount ', function(event) {
            event.preventDefault();
            let ttl = 0
            $('.txnAmount ').each((key, val) => {
                let value = 0
                if (val.value) {
                    value = parseFloat(val.value)
                }
                ttl += value
            })
            $('.txnAllotTtl').text(thousands_separators(ttl))
        });
        $('#orsEntriesTbl').on('change', '.orsItmAmt ', function(event) {
            event.preventDefault();
            let ttl = 0
            $('#orsEntriesTbl .orsItmAmt ').each((key, val) => {
                let value = 0
                if (val.value) {

                    value = parseFloat(val.value)
                }
                if ($(val).is('span')) {
                    value = parseFloat($(val).text())

                }
                ttl += value

            })
            $('.orsItmTtl').text(thousands_separators(ttl))
        });
    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#orsForm").on("beforeSubmit", function (event) {
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