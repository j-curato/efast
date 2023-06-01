<?php

use app\models\Books;
use app\models\VwCashReceivedSearch;
use app\models\VwGdNoAcicChksSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Acics */
/* @var $form yii\widgets\ActiveForm */

$cshItmRowNum = 0;
$cshRcvItmRowNum = 0;
?>

<div class="accics-form panel panel-default">

    <ul>
        <li>Notes</li>
        <li>Click the name of the table to open or close it.</li>
    </ul>
    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="row">
        <div class="col-sm-3">

            <?= $form->field($model, 'date_issued')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_book_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Book'
                ]
            ]) ?>

        </div>
    </div>


    <table class="table " id="cash_itms_tbl">

        <thead>
            <tr class="warning">
                <th colspan="7" class="ctr">
                    Cash Disbursements
                </th>
            </tr>
            <th>Reporting Period</th>
            <th>Mode of Payment</th>
            <th>Check No.</th>
            <th>ADA No.</th>
            <th>Issuance Date</th>
            <th>Book Name</th>
        </thead>
        <tbody>
            <?php

            foreach ($cashItems as $itm) {
                echo "<tr>
                    <td style='display:none'>
                        <input type='text' value='{$itm['item_id']}' name='cashItems[$cshItmRowNum][item_id]'></input>
                        <input type='text' value='{$itm['cash_id']}' name='cashItems[$cshItmRowNum][cash_id]'></input>
                    </td>
                    <td>{$itm['reporting_period']}</td>
                    <td>{$itm['mode_name']}</td>
                    <td>{$itm['check_or_ada_no']}</td>
                    <td>{$itm['ada_number']}</td>
                    <td>{$itm['issuance_date']}</td>
                    <td>{$itm['book_name']}</td>
                    <td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>
                </tr>";
                $cshItmRowNum++;
            }
            ?>
        </tbody>
    </table>
    <table class="table" id="cash_rcv_itms_tbl">

        <thead>
            <tr class="success">
                <th colspan="13" class="ctr">Cash Rceives</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Reporting Period</th>
                <th>Valid From</th>
                <th>Valid to</th>
                <th>Purpose</th>
                <th>Amount</th>
                <th>Document Receive</th>
                <th>Book</th>
                <th>MFO/PAP</th>
                <th>NCA No.</th>
                <th>NTA No.</th>
            </tr>
        </thead>
        <tbody>

            <?php

            foreach ($cashRcvItems as $itm) {
                echo "<tr>
                        <td style='display:none'>
                            <input type='text' value='{$itm['cash_rcv_itm_id']}' name='cshRcvItems[$cshRcvItmRowNum][item_id]'></input>
                            <input type='text' value='{$itm['fk_cash_receive_id']}' name='cshRcvItems[$cshRcvItmRowNum][csh_rcv_id]'></input>
                        </td>
                        <td>{$itm['date']}</td>
                        <td>{$itm['reporting_period']}</td>
                        <td>{$itm['valid_from']}</td>
                        <td>{$itm['valid_to']}</td>
                        <td>{$itm['purpose']}</td>
                        <td>{$itm['cash_amt']}</td>
                        <td>{$itm['document_receive_name']}</td>
                        <td>{$itm['book_name']}</td>
                        <td>{$itm['mfo_name']}</td>
                        <td>{$itm['nca_no']}</td>
                        <td>{$itm['nta_no']}</td>
                        <td>
                            <input type='text' class='mask-amount amount form-control' onkeyup='updateMainAmount(this)' value='{$itm['amount']}'>
                            <input type='hidden' name='cshRcvItems[$cshRcvItmRowNum][amount]' class='amount main-amount' value='{$itm['amount']}' >
                        </td>
                        <td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>
                    </tr>";
                $cshRcvItmRowNum++;
            }
            ?>
        </tbody>
    </table>
    <div class="row">
        <div class="form-group col-sm-1 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $checksSearchModel = new VwGdNoAcicChksSearch();
    $checksDataProvider = $checksSearchModel->search(Yii::$app->request->queryParams);
    $checksDataProvider->pagination = ['pageSize' => 10];
    $checksCols = [

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::input('text', 'cashItems[cash_id]', $model->id, ['class' => 'cash_id']);
            },
            'hidden' => true
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary add-action', 'onClick' => 'AddCashItem(this)']);
            },
        ],
        'reporting_period',
        'mode_name',
        'check_or_ada_no',
        'ada_number',
        'issuance_date',
        'book_name',
    ];
    $cshRcvSearchModel = new VwCashReceivedSearch();
    $cshRcvSearchModel->type = 'acic';
    $cshRcvDataProvider = $cshRcvSearchModel->search(Yii::$app->request->queryParams);
    $cshRcvDataProvider->pagination = ['pageSize' => 10];
    $cshRcvCols = [

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::input('text', 'cshRcvItems[csh_rcv_id]', $model->id, ['class' => 'csh_rcv_id']);
            },
            'hidden' => true
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary add-action', 'onClick' => 'AddCshRcvItem(this)']);
            },
        ],

        'date',
        'reporting_period',
        'valid_from',
        'valid_to',
        'purpose',
        'amount',
        'document_receive_name',
        'book_name',
        'mfo_name',
        'nca_no',
        'nta_no',
        [
            'attribute' => 'bookFilter',
            'hidden' => true
        ],
        [
            'attribute' => 'validityFilter',
            'hidden' => true
        ],

    ];

    ?>

    <?= Collapse::widget([
        'items' => [
            [
                'label' => 'List of Cash Disbursements',
                'content' => GridView::widget([
                    'dataProvider' => $checksDataProvider,
                    'filterModel' => $checksSearchModel,


                    'columns' => $checksCols,
                    'pjax' => true,
                    'export' => false,
                ]),
                'contentOptions' => ['class' => ''],
                'options' => ['class' => 'panel-primary'],
            ],
        ],
    ])
    ?>
    <?=
    Collapse::widget([
        'items' => [
            [
                'label' => 'List of Cash Receives',
                'content' => GridView::widget([
                    'dataProvider' => $cshRcvDataProvider,
                    'filterModel' => $cshRcvSearchModel,


                    'columns' => $cshRcvCols,
                    'pjax' => true,
                    'export' => false,
                ]),
                'contentOptions' => ['class' => ''],
                'options' => ['class' => 'panel-warning'],
            ],
        ],
    ])
    ?>


</div>
<style>
    .accics-form {
        padding: 3rem;
    }

    .ctr {
        text-align: center;
    }

    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>
<?php

$this->registerJsFile("@web/frontend/web/js/globalFunctions.js", ['depends' => [JqueryAsset::class]]);
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let cshItmRowNum = <?= $cshItmRowNum ?>;
    let cshRcvItmRowNum = <?= $cshRcvItmRowNum ?>;

    function AddCashItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.cash_id').attr('name', `cashItems[${cshItmRowNum}][cash_id]`)
        clone.find('.add-action').parent().remove()
        clone.append("<td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>")
        $('#cash_itms_tbl tbody').append(clone)

        cshItmRowNum++
    }

    function AddCshRcvItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.csh_rcv_id').attr('name', `cshRcvItems[${cshRcvItmRowNum}][csh_rcv_id]`)
        clone.find('.add-action').parent().remove()
        clone.append(`<td>
            <input type='text' class='mask-amount amount form-control' onkeyup='updateMainAmount(this)'>
            <input type='hidden' name='cshRcvItems[${cshRcvItmRowNum}][amount]' class='amount main-amount' >
        </td>`)
        clone.append("<td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>")
        $('#cash_rcv_itms_tbl tbody').append(clone)

        cshRcvItmRowNum++
        maskAmount()
    }

    function remove(ths) {
        $(ths).closest('tr').remove()
    }

    function updateMainAmount(q) {
        $(q).parent().find('.main-amount').val($(q).maskMoney('unmasked')[0])
    }


    $(document).ready(function() {
        maskAmount()
        let book_name = '<?= !empty($model->book->name) ? $model->book->name : '' ?>';
        let acic_date = '<?= !empty($model->date_issued) ? $model->date_issued : '' ?>';
        window.onload = function() {
            $('input[name^="VwCashReceivedSearch[bookFilter]').val(book_name).trigger('change')
            $('input[name^="VwCashReceivedSearch[validityFilter]').val(acic_date).trigger('change')
        };
        $('#acics-fk_book_id').change(() => {
            const book = $('#acics-fk_book_id :selected').text()
            $('input[name^="VwCashReceivedSearch[bookFilter]').val(book).trigger('change')
        })
        $('#acics-date_issued').change(() => {
            const date = $('#acics-date_issued').val()
            $('input[name^="VwCashReceivedSearch[validityFilter]').val(date).trigger('change')
        })
    })
</script>

<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#Acics").on("beforeSubmit", function (event) {
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