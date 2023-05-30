<?php

use app\models\Books;
use app\models\VwGdNoAcicChksSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Acics */
/* @var $form yii\widgets\ActiveForm */

$cshItmRowNum = 0;
?>

<div class="accics-form panel panel-default">

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
    <div class="row">
        <div class="form-group col-sm-1 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $searchModel = new VwGdNoAcicChksSearch();

    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->pagination = ['pageSize' => 10];
    $cols = [

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
    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Cash Disbursements',
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
    .accics-form {
        padding: 3rem;
    }
</style>
<script>
    let cshItmRowNum = <?= $cshItmRowNum ?>;

    function AddCashItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.cash_id').attr('name', `cashItems[${cshItmRowNum}][cash_id]`)
        clone.find('.add-action').parent().remove()
        clone.append("<td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>")
        $('#cash_itms_tbl tbody').append(clone)

        cshItmRowNum++
    }

    function remove(ths) {
        $(ths).closest('tr').remove()
    }
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