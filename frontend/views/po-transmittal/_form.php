<?php

use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use app\models\ForTransmittalSearch;
use app\models\LiquidationEntriesViewSearch;
use app\models\LiquidationViewSearch;
use app\models\Office;
use app\models\VwNoPoTransmittalLiqsSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */
/* @var $form yii\widgets\ActiveForm */

$itemRow = 0;
?>

<div class="transmittal-form panel panel-default">
    <?php
    $viewSearchModel = new VwNoPoTransmittalLiqsSearch();
    // $viewSearchModel->status = 'at_po';
    // $viewSearchModel->is_cancelled = 0;
    $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);

    $viewDataProvider->pagination = ['pageSize' => 10];
    $viewColumn = [
        [
            'label' => 'Action',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::button('<id class="fa fa-plus"></id>', ['class' => 'add_btn btn-xs btn-primary', 'onclick' => 'addItem(this)']);
            },
        ],

        [
            'label' => 'id',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::input('text', '', $model->id, ['class' => 'dv_id']);
            },
            'hidden' => true

        ],
        'province',
        'check_date',
        'check_number',
        'dv_number',
        'reporting_period',
        'payee',
        'particular',

        [
            'label' => 'Total Disbursements',
            'attribute' => 'total_withdrawal',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Sales Tax (VAT/Non-VAT)',
            'attribute' => 'total_vat',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Income Tax (Expanded Tax)',
            'attribute' => 'total_expanded',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Liquidation',
            'attribute' => 'total_liquidation_damage',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Gross Payment',
            'attribute' => 'gross_payment',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],


    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $viewDataProvider,
        'filterModel' => $viewSearchModel,
        'columns' => $viewColumn,
        'pjax' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Liquidations',
        ],
        'export' => false

    ]); ?>

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',

                ]
            ]) ?>
        </div>
        <?php
        if (Yii::$app->user->can('super-iser')) {?>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Office',
                    ]
                ]) ?>
            </div>
        <?php } ?>
    </div>
    <table class="table table-striped" id="transaction_table" style="background-color: white;">
        <thead>
            <th>Province</th>
            <th>Check Date</th>
            <th>Check Number</th>
            <th>DV Number</th>
            <th>Reporting Period</th>
            <th>Payee</th>
            <th>Particular</th>
            <th>Total Disbursements</th>
            <th>Total Sales Tax</th>
            <th>Total Income Tax</th>
            <th>Total Liquidation Damage</th>
            <th>Gross Payment</th>
        </thead>
        <tbody>
            <?php

            foreach ($items as $itm) {

                echo "<tr>
                    <td style='display:none;'>
                        <input type='text' name='items[$itemRow][item_id]' value='{$itm['item_id']}'>
                        <input type='text' name='items[$itemRow][dv_id]' value='{$itm['dv_id']}'>
                    </td>
                    <td>{$itm['province']}</td>
                    <td>{$itm['check_date']}</td>
                    <td>{$itm['check_number']}</td>
                    <td>{$itm['dv_number']}</td>
                    <td>{$itm['reporting_period']}</td>
                    <td>{$itm['payee']}</td>
                    <td>{$itm['particular']}</td>
                    <td>{$itm['total_withdrawal']}</td>
                    <td>{$itm['total_vat']}</td>
                    <td>{$itm['total_expanded']}</td>
                    <td>{$itm['total_liquidation_damage']}</td>
                    <td>{$itm['gross_payment']}</td>
                    <td><button id='remove' class='btn-xs btn-danger ' onclick='remove(this)'><i class='glyphicon glyphicon-minus'></i></button></td>
                </tr>";
                $itemRow++;
            }
            ?>
        </tbody>

    </table>
    <div class="row">

        <div class="form-group col-sm-1 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .transmittal-form {
        padding: 2rem;
    }
</style>

<script>
    function addItem(ths) {
        let source = $(ths).closest('tr')
        let clone = source.clone(true)
        clone.find('.add_btn').closest('td').remove()
        clone.find('.dv_id').prop('name', 'items[][dv_id]');
        let row = `<td><button id='remove' class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td>`
        clone.append(row)
        $('#transaction_table tbody').append(clone);
    }

    function remove(i) {
        i.closest("tr").remove()
    }
</script>


<?php
SweetAlertAsset::register($this);
$script = <<< JS
     

    $(document).ready(()=>{
        $("#PoTransmittal").on("beforeSubmit", function (event) {
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
    })
    

JS;
$this->registerJs($script);
?>