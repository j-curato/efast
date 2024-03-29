<?php

use app\models\Books;
use yii\helpers\Html;
use kartik\icons\Icon;
use yii\base\Security;
use kartik\grid\GridView;
use app\models\VwDvsInBank;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use app\models\VwGdNoRciChecks;
use app\models\VwDvsInBankSearch;
use app\models\VwGdNoRciChecksSearch;
use aryelds\sweetalert\SweetAlertAsset;


/* @var $this yii\web\View */
/* @var $model app\models\Rci */
/* @var $form yii\widgets\ActiveForm */

$itmRow = 0;



?>

<div class="rci-form card">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>


    <ul class="notes">
        <li>Notes:</li>
        <li>Select Book to display all the Checks in bank for the selected book.</li>
        <li>If the check number is not in the list, verify whether the check number has an ACIC. </li>
        <li>If the check number already has an RCI, it will not be displayed in the list. </li>
    </ul>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_book_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Book'
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">


            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
    </div>
    <table class="table" id="items_tbl">

        <thead>
            <th>Reporting Period</th>
            <th>Check No.</th>
            <th>ADA No.</th>
            <th>Date of Issued</th>
            <th>Book</th>
            <th>Mode of Payment</th>
            <th> Amount Disbursed</th>
            <th> Tax Withheld</th>


        </thead>
        <tbody>
            <?php
            foreach ($items as $itm) {









                echo "<tr>
                    <td style='display:none'><input type='text' name='items[$itmRow][item_id]' value='{$itm['item_id']}'></td>
                    <td style='display:none'><input type='text' name='items[$itmRow][cash_item_id]' value='{$itm['cash_id']}'></td>
                    <td>{$itm['reporting_period']}</td>
                    <td>{$itm['check_or_ada_no']}</td>
                    <td>{$itm['ada_number']}</td>
                    <td>{$itm['issuance_date']}</td>
                    <td>{$itm['book_name']}</td>
                    <td>{$itm['mode_name']}</td>
                    <td class=''>" . number_format($itm['ttlDisbursed'], 2) . "</td>
                    <td class=''>" . number_format($itm['ttlTax'], 2) . "</td>
                    <td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>
                </tr>";
                $itmRow++;
            }
            ?>
        </tbody>
    </table>
    <div class="row justify-content-center">

        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success submit-button', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php


    $searchModel = new VwGdNoRciChecksSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->pagination = ['pageSize' => 10];

    $cols = [

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::input('text', 'items[cash_item_id]', $model->id, ['class' => 'cash_item_id']);
            },
            'hidden' => true
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary add-action', 'onClick' => 'AddItem(this)']);
            },
        ],
        'reporting_period',

        'check_or_ada_no',
        'ada_number',
        'issuance_date',
        'book_name',
        'mode_name',

        [
            'attribute' => 'ttlDisbursed',

            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlTax',

            'format' => ['decimal', 2]
        ],

        [
            'attribute' => 'bookFilter',
            'hidden' => true
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Checks in Bank'
        ],
        'pjax' => true,
        'columns' => $cols,
        'export' => false
    ]); ?>




</div>
<style>
    .rci-form {
        padding: 2rem;
    }

    .amt {
        text-align: right;

    }

    .notes li {
        color: red;
    }
</style>
<script>
    let itmRow = <?= $itmRow ?>;
    let book_name = '<?= !empty($model->book->name) ? $model->book->name : '' ?>';

    function AddItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.cash_item_id').attr('name', `items[${itmRow}][cash_item_id]`)
        clone.find('.add-action').parent().remove()
        clone.append("<td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>")
        $('#items_tbl tbody').append(clone)

        itmRow++
    }

    function remove(ths) {
        $(ths).closest('tr').remove()
    }

    function bookFilter(book) {
        $('input[name^="VwGdNoRciChecksSearch[bookFilter]').val(book).trigger('change')
    }

    $(document).ready(function() {
        window.onload = function() {
            bookFilter("<?= $model->book->name ?? '' ?>")
        };
        $('#cashdisbursement-fk_mode_of_payment_id').change(() => {
            $('#cashdisbursement-fk_ro_check_range_id').val(null).trigger('change');
        })
        $('#rci-fk_book_id').change(() => {
            const book = $('#rci-fk_book_id :selected').text()

            bookFilter(book)
        })



    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#Rci").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {

            swal({
                icon: 'error',
                title: data,
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