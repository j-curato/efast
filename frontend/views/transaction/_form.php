<?php

use app\components\helpers\MyHelper;
use app\models\Books;
use app\models\RecordAllotmentDetailedSearch;
use app\models\PurchaseRequestIndexSearch;
use app\models\RecordAllotmentsViewSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\money\MaskMoney;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */

$iar_data = [];
$iar_val = '';
$row_number =  0;
$prItemRow =  0;
if ($model->type == 'single' || $model->type == 'multiple') {
    $query = Yii::$app->db->createCommand("SELECT transaction_iars.fk_iar_id,iar.iar_number FROM transaction_iars
    LEFT JOIN iar ON transaction_iars.fk_iar_id = iar.id
     WHERE transaction_iars.fk_transaction_id = :id")
        ->bindValue(':id', $model->id)
        ->queryAll();
    $iar_data = ArrayHelper::map($query, 'fk_iar_id', 'iar_number');
    // var_dump();
    if ($model->type == 'multiple') {
        if (!empty($query)) {
            $iar_val = array_column($query, 'fk_iar_id');
        }
    } else if ($model->type == 'single') {
        if (!empty($query)) {
            $iar_val = key($iar_data);
        }
    }
}
$payee = !empty($model->payee_id) ? ArrayHelper::map(MyHelper::getPayee($model->payee_id), 'id', 'account_name') : [];
?>


<div class="transaction-form" style="background-color: white;padding:20px">

    <?php
    $r_center = (new \yii\db\Query())->select('*')
        ->from('responsibility_center');


    $user = strtolower(Yii::$app->user->identity->province);
    $division = strtolower(Yii::$app->user->identity->division);

    if (

        $user === 'ro' &&
        $division === 'sdd' ||
        $division === 'cpd' ||
        $division === 'idd' ||
        $division === 'ord'


    ) {
        $r_center->where('name LIKE :name', ['name' => $division]);
    }
    $respons_center = $r_center->all();

    ?>
    <div class="note">
        <h5>Notes</h5>
        <ul>
            <li>If the Transaction/DV has a Purchase Request, please select the corresponding PR# below to ensure accuracy in our Allotment/Fund Tracker</li>
            <li>The Amount in the Purchase Request table should be positive. </li>
        </ul>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'TransactionForm',
    ]); ?>


    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'type')->widget(Select2::class, [
                'data' => ['no-iar' => 'No IAR', 'single' => 'Single IAR', 'multiple' => 'Multiple Iar'],
                'options' => ['placeholder' => 'Select Transaction Type...'],
                'pluginOptions' => ['allowClear' => true],
            ]) ?>
        </div>

        <div class="col-sm-5" id="multiple">
            <label for="multiple_iar"> Iar's</label>
            <?php
            echo Select2::widget([
                'name' => 'multiple_iar',
                'data' => $iar_data,
                'value' => $iar_val,
                'options' => ['placeholder' => 'Select IARs...', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=iar/search-iar',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]);
            ?>
        </div>

        <div class="col-sm-3" id="single">
            <label for="single_iar"> Iar's</label>
            <?php
            echo Select2::widget([
                'name' => 'single_iar',
                'id' => 'single_iar',
                'data' => $iar_data,
                'value' => $iar_val,
                'options' => ['placeholder' => 'Select a IAR...', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=iar/search-iar',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <?php

        if (Yii::$app->user->can('super-user')) { ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'responsibility_center_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($respons_center, 'id', 'name'),
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => 'Select  Responsibility Center'
                    ],
                ]) ?>
            </div>
        <?php } ?>

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_book_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Select  Book'
                ],
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
                        'url' => Url::to(['payee/search-payee']),
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'transaction_date')->widget(DatePicker::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'mm-dd-yyyy'
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
            <?= $form->field($model, 'particular')->textarea([
                'style' => 'max-width:100%'
            ]) ?>
        </div>
    </div>
    <table class="table" id="entry_table">
        <thead>
            <tr class="info">
                <td colspan="12" class="center">
                    <h4>Allotment</h4>
                </td>
            </tr>
            <tr>
                <th>Budget Year</th>
                <th>Allotment No.</th>
                <th>Book</th>
                <th>Office</th>
                <th>Division</th>
                <th>Mfo Name</th>
                <th>Fund Source</th>
                <th> General Ledger</th>
                <th>Amount </th>
                <th>Balance </th>
                <th>Gross Amount</th>
            </tr>
        </thead>
        <tbody>

            <?php

            if (!empty($items)) {
                foreach ($items as $item) {
                    $formated_gross_amount = number_format($item['amount'], 2);
                    echo "<tr>
                        <td style='display:none;'><input type='text' class='entry_id' name='items[{$row_number}][item_id]' value='{$item['item_id']}'></td>
                        <td style='display:none;'><input type='text' class='entry_id' name='items[{$row_number}][allotment_id]' value='{$item['allotment_entry_id']}'></td>
                        <td>{$item['budget_year']}</td>
                        <td>{$item['allotmentNumber']}</td>
                        <td>{$item['book']}</td>
                        <td>{$item['office_name']}</td>
                        <td>{$item['division']}</td>
                        <td>{$item['mfo_code']}-{$item['mfo_name']}</td>
                        <td>{$item['fund_source_name']}</td>
                        <td>{$item['account_title']}</td>
                        <td >" . number_format($item['allotment_amt'], 2) . "</td>
                        <td >" . number_format($item['balance'], 2) . "</td>
                        <td> <input type='text' class='mask-amount form-control'value='{$formated_gross_amount}' ><input type='hidden' name='items[{$row_number}][gross_amount]' class='gross_amount' value='{$item['amount']}'></td></td>
                        <td><button type='button' class='remove btn-xs btn-danger'><i class='fa fa-times'></i></button></td>
                        </tr>";
                    $row_number++;
                }
            }
            ?>
        </tbody>
    </table>
    <table class="table" id="prTable">
        <thead>
            <tr class="info">
                <td colspan="8" class="center">
                    <h4>Purchase Requests</h4>
                </td>
            </tr>
            <tr>
                <th>PR Number</th>
                <th>Allotment Number</th>
                <th> Purpose</th>
                <th>MFO/PAP</th>
                <th>Fund Source</th>
                <th>Allotment Amount</th>
                <th>Balance</th>
                <th> Amount</th>


            </tr>
        </thead>
        <tbody>

            <?php

            if (!empty($transactionPrItems)) {
                foreach ($transactionPrItems as $prItem) {

                    $transactionPrItemId = $prItem['id'];
                    $prAllotmentId = $prItem['prAllotmentId'];
                    $pr_number = $prItem['pr_number'];

                    $purpose = $prItem['purpose'];
                    $formatedAmt = number_format($prItem['txnPrAmt'], 2);
                    $allotment_number = $prItem['allotment_number'];
                    $prAllotmenAmt = number_format($prItem['prAllotmenAmt'], 2);
                    $mfo_name = $prItem['mfo_name'];
                    $fund_source_name = $prItem['fund_source_name'];
                    $balance =  number_format($prItem['balance'], 2);



                    echo "<tr>
                    <td style='display:none;'><input type='text' class='entry_id' name='prItems[{$prItemRow}][item_id]' value='{$transactionPrItemId}'></td>
                    <td style='display:none'><input type='hidden' name='prItems[$prItemRow][prAllotmentId]' value='$prAllotmentId'></td>
                    <td>$pr_number</td>
                    <td>$allotment_number</td>

                    <td>$purpose</td>
                    <td>$mfo_name</td>
                    <td>$fund_source_name</td>
                    <td>$prAllotmenAmt</td>
                    <td>$balance</td>
                    <td> <input type='text' class='mask-amount form-control' value='{$formatedAmt}'>
                         <input type='hidden' name='prItems[$prItemRow][amount]' class='gross_amount' value='{$prItem['txnPrAmt']}'>
                    </td>
                    <td><button type='button' class='remove btn-xs btn-danger'><i class='fa fa-times'></i></button></td>
                </tr>";
                    // echo "<tr>
                    //     <td style='display:none;'><input type='text' class='entry_id' name='prItems[{$prItemRow}][item_id]' value='{$transactionPrItemId}'></td>
                    //     <td style='display:none;'><input type='text' class='entry_id' name='prItems[{$prItemRow}][pr_id]' value='{$pr_id}'></td>
                    //     <td>{$pr_number}</td>
                    //     <td>{$office_name}</td>
                    //     <td>{$division}</td>
                    //     <td>{$division_program_unit}</td>
                    //     <td >$purpose</td>
                    //     <td> 
                    //         <input type='text' class='mask-amount form-control'value='{$formatedAmt}' >
                    //         <input type='hidden' name='prItems[{$prItemRow}][amount]' class='gross_amount' value='{$prItem['amount']}'>
                    //     </td>
                    //     <td><button type='button' class='remove btn-xs btn-danger'><i class='fa fa-times'></i></button></td>
                    // </tr>";
                    $prItemRow++;
                }
            }
            ?>
        </tbody>
    </table>
    <div class="row justify-content-center">
        <div class="form-group ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:11rem']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php
    $division = Yii::$app->user->identity->division;
    $searchModel = new RecordAllotmentDetailedSearch();
    $searchModel->module = 'transaction';
    $searchModel->bookFilter = !empty($model->book->name) ? $model->book->name : '';

    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->pagination = ['pageSize' => 10];

    $col = [
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return  Html::input('text', 'item[allotment_id]', $model->allotment_entry_id, ['class' => 'allotment_id']);
            },
            'hidden' => true
        ],
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'addEntry(this)']);
            },
        ],

        [
            'attribute' => 'budget_year',
            // 'hidden' => true

        ],

        [
            'attribute' => 'book_name',
            'hidden' => false,
        ],
        [
            'attribute' => 'bookFilter',
            'hidden' => true,
        ],
        'allotmentNumber',
        'office_name',
        'division',

        [
            'attribute' => 'mfo_name',
            'value' => function ($model) {
                return $model->mfo_code . '-' . $model->mfo_name;
            }
        ],
        'fund_source_name',
        'account_title',
        [
            'attribute' => 'amount',
            'format' => ['decimal', 2],
            // 'hAlign' => 'right'
        ],
        [
            'attribute' => 'balance',
            'format' => ['decimal', 2],
            // 'hAlign' => 'right'
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
            'heading' => 'Allotments'
        ],
        'export' => false,
        'pjax' => true,


        'columns' => $col
    ]); ?>

    <?php
    $prsearchModel = new PurchaseRequestIndexSearch();
    $prsearchModel->module = 'transaction';
    $prsearchModel->bookFilter = !empty($model->book->name) ? $model->book->name : '';
    $prdataProvider = $prsearchModel->search(Yii::$app->request->queryParams);
    $prdataProvider->pagination = ['pageSize' => 10];
    ?>
    <?= GridView::widget([
        'dataProvider' => $prdataProvider,
        'filterModel' => $prsearchModel,

        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Purchase Requests'
        ],
        'export' => false,
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                'id' => 'qwe_qweq'
            ]
        ],
        'columns' => [
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::input('text', 'item[prItems]', $model->id, ['class' => 'pr_id']);
                },
                'hidden' => true
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'addPr(this)']);
                },
            ],
            [
                'attribute' => 'bookFilter',
                'hidden' => true,
            ],
            'pr_number',
            'office_name',
            'division',
            'division_program_unit',
            'purpose',
            [
                'label' => 'Balance',
                'attribute' => 'forTransactionBal',
                'format' => ['decimal', 2],
                'hAlign' => 'right',

            ]


        ],
    ]); ?>
    <?php
    ?>




</div>


<style>
    #multiple,
    #single {
        display: none;
    }

    .amount {
        text-align: right;
    }

    .error {
        color: red;
    }

    .center {
        text-align: center;
    }

    .note {
        color: red;
    }
</style>
<?php
$this->registerJsFile("@web/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/js/validate.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);

?>

<script>
    let row_number = <?= $row_number ?>;
    let prItemRow = <?= $prItemRow ?>;

    function transactionType() {
        const transaction_type = $('#transaction-type').val()
        if (transaction_type == 'single') {
            $('#single').show()
            $('#multiple').hide()
        } else if (transaction_type == 'multiple') {
            $('#multiple').show()
            $('#single').hide()

            // $("#transaction-payee_id").val('').trigger('change')
            // $("#transaction-gross_amount-disp").val('')
            // $("#transaction-gross_amount").val('')
            // $("#transaction-particular").val('')

        } else {
            $('#multiple').hide()
            $('#single').hide()
        }
    }

    function addEntry(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.add').closest('td').remove()
        clone.find('.allotment_id').attr('name', `items[${row_number}][allotment_id]`)
        clone.append(`<td> <input type='text' class='mask-amount form-control'><input type='hidden' name='items[${row_number}][gross_amount]' class='gross_amount'></td>`)
        clone.append('<td><button type="button" class="remove btn-xs btn-danger"><i class="fa fa-times"></i></button></td>')
        $('#entry_table tbody').append(clone)
        maskAmount()
        row_number++
    }

    function displayPrAllotments(data) {
        $.each(data, (key, val) => {

            const amount = thousands_separators(val.prAllotmentAmt)
            const balance = thousands_separators(val.balance)
            const row = `<tr>
                <td style='display:none'><input type='hidden' name='prItems[${prItemRow}][prAllotmentId]' value='${val.prAllotmentId}'></td>
                <td>${val.pr_number}</td>
                <td>${val.allotment_number}</td>
                <td>${val.purpose}</td>
                <td>${val.mfo_name}</td>
                <td>${val.fund_source_name}</td>
                <td>${amount}</td>
                <td>${balance}</td>
                <td> <input type='text' class='mask-amount form-control'><input type='hidden' name='prItems[${prItemRow}][amount]' class='gross_amount'></td>
                <td><button type="button" class="remove btn-xs btn-danger"><i class="fa fa-times"></i></button></td>
            </tr>`
            $('#prTable tbody').append(row)
            prItemRow++
        })
        maskAmount()
    }

    function addPr(ths) {
        // const clone = $(ths).closest('tr').clone()
        // clone.find('.add').closest('td').remove()
        // clone.find('.pr_id').attr('name', `prItems[${prItemRow}][pr_id]`)
        // clone.append(`<td> <input type='text' class='mask-amount form-control'><input type='hidden' name='prItems[${prItemRow}][amount]' class='gross_amount'></td>`)
        // clone.append('<td><button type="button" class="remove btn-xs btn-danger"><i class="fa fa-times"></i></button></td>')
        // $('#prTable tbody').append(clone)
        // prItemRow++
        const pr_id = $(ths).closest('tr').find('.pr_id').val()

        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=transaction/get-pr-allotments',
            data: {
                id: pr_id
            },
            success: function(results) {
                const res = JSON.parse(results)
                console.log(res)
                displayPrAllotments(res)
            }
        })
        maskAmount()
    }

    function formatNumber(num) {
        var roundedNum = Math.round(num * 100) / 100;
        var numString = roundedNum.toString();
        var numArray = numString.split(".");
        var formattedNum = numArray[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        if (numArray[1]) {
            formattedNum += "." + numArray[1];
        }
        return formattedNum;
    }


    $(document).ready(function() {

        maskAmount()
        $('#entry_table').on('change keyup', '.mask-amount', function(e) {
            e.preventDefault()
            const amount = $(this).maskMoney('unmasked')[0];
            const source = $(this).closest('tr');
            source.children('td').find('.gross_amount').val(amount)

        })
        $('#prTable').on('change keyup', '.mask-amount', function(e) {
            e.preventDefault()
            const amount = $(this).maskMoney('unmasked')[0];
            const source = $(this).closest('tr');
            source.children('td').find('.gross_amount').val(amount)

        })
        $('#entry_table').on('click', '.remove', function(e) {
            $(this).closest('tr').remove()
        })
        $('#prTable').on('click', '.remove', function(e) {
            $(this).closest('tr').remove()
        })
        transactionType()
        $('#transaction-type').change(function() {

            transactionType()

        })
        $("#single_iar").on('change', function() {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + "?r=transaction/iar-details",
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    $("#transaction-payee_id").val(res.id).trigger('change')
                    $("#transaction-gross_amount-disp").val(res.amount)
                    $("#transaction-gross_amount").val(res.amount)
                    $("#transaction-particular").val(res.purpose)
                }
            })
        })


        // const registrationForm = $('#rfi_form');
        // if (registrationForm.length) {
        //     registrationForm.validate({
        //         rules: {
        //             gross_amount: {
        //                 required: true
        //             },
        //             type: {
        //                 required: true
        //             },
        //             responsibility_center_id: {
        //                 required: true
        //             },
        //             payee_id: {
        //                 required: true
        //             },
        //             transaction_date: {
        //                 required: true
        //             },

        //             particular: {
        //                 required: true
        //             },


        //         },
        //         messages: {

        //             type: {
        //                 required: 'Type is Required!'
        //             },
        //             responsibility_center_id: {
        //                 required: 'Responsibility Center is Required!'
        //             },
        //             payee_id: {
        //                 required: 'Payee is Required!'
        //             },
        //             transaction_date: {
        //                 required: 'Transaction Date is Required!'
        //             },


        //             particular: {
        //                 required: 'Particular is Required!'
        //             },

        //             gross_amount: {
        //                 required: 'gross_amount is Required!'
        //             },


        //         },
        //         errorPlacement: function(error, element) {
        //             let elem_name = element.attr('name')
        //             console.log(elem_name)

        //             if (element.is("select")) {
        //                 element.next().append(error)
        //             } else if (elem_name == 'transaction_date') {
        //                 // error.insertAfter(element);
        //                 element.parent().parent().append(error)
        //             } else if (element.is('textarea')) {
        //                 error.insertAfter(element);
        //             } else if (element.is('input')) {
        //                 error.insertAfter(element);
        //             } else if (elem_name == 'gross_amount') {
        //                 error.insertAfter(element);

        //             } else {
        //                 // element.append(error)
        //                 console.log(element)
        //                 error.insertAfter(element);
        //             }
        //             // if (element.is("select")) {
        //             //     // error.appendTo(element.parents('.hobbies'));
        //             //     element.parent().append(error)
        //             // } else if (element.is('textarea')) {
        //             //     error.insertAfter(element);
        //             // } else {
        //             //     // element.append(error)
        //             // }
        //             with_error = 1

        //         },
        //         success: function() {
        //             with_error = 0
        //         },
        //         invalidHandler: function() {
        //             with_error = 1
        //         },


        //     });
        // }

        // $('#rfi_form').on('submit', function(e) {
        //     e.preventDefault()
        //     const form = $('#rfi_form')
        //     $.ajax({
        //         type: 'POST',
        //         url: window.location.pathname + form.attr('action'),
        //         data: form.serialize(),
        //         success: function(data) {
        //             const res = JSON.parse(data)
        //             console.log(res)
        //             if (!res.isSuccess) {

        //                 if (typeof res.error_message === 'object') {
        //                     console.log(res.error_message)

        //                     $.each(res.error_message, (key, val) => {
        //                         console.log(key)
        //                     })
        //                 } else if (typeof res.error_message === 'string') {
        //                     swal({
        //                         icon: 'error',
        //                         title: res.error_message,
        //                         type: "error",
        //                         timer: 3000,
        //                         closeOnConfirm: false,
        //                         closeOnCancel: false
        //                     })
        //                 }
        //             }
        //         },

        //     })
        // })
        let x = 0
        let qwe = '<?= !empty($model->book->name) ? $model->book->name : '' ?>';
        window.onload = function() {
            $('input[name^="PurchaseRequestIndexSearch[bookFilter]').val(qwe).trigger('change')
        };

        $('#transaction-fk_book_id').change(() => {
            x = 0
            // console.log($('input[name^="RecordAllotmentDetailedSearch[bookFilter]"]').val())
            const book = $('#transaction-fk_book_id :selected').text()
            // $('input[name^="PurchaseRequestIndexSearch[bookFilter]').val(book).trigger('change')
            // $(document).ajaxComplete(function() {

            if (x == 0) {
                const book_name = $('#transaction-fk_book_id :selected').text()
                $('input[name^="RecordAllotmentDetailedSearch[bookFilter]"]').val(book_name).trigger('change')
                x = 1
            }
            // });
        })
        $('input[name^="RecordAllotmentDetailedSearch[bookFilter]"]').val('').trigger('change')
        $('input[name^="PurchaseRequestIndexSearch[bookFilter]').val('').trigger('change')
        $('#transaction-fk_book_id').trigger('change')


    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<<JS

    $(document).ready(function(){
        $("#TransactionForm").on("beforeSubmit", function(event) {
            event.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(data) {
                    console.log(data)
                    swal({
                        icon: 'error',
                        title: data,
                        type: "error",
                        timer: 3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                },
                error: function(data) {

                }
            });
            return false;
        });
      
    })
JS;
$this->registerJs($js);

?>