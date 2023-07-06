<?php

use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use app\models\ForTransmittalSearch;
use app\models\LiquidationEntriesViewSearch;
use app\models\LiquidationViewSearch;
use app\models\PoTransmittal;
use app\models\PoTransmittalSearch;
use app\models\VwNotInCoaTransmittalSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */
/* @var $form yii\widgets\ActiveForm */

$itemRow = 0;
?>

<div class="transmittal-form panel panel-default">
    <?php
    $viewSearchModel = new VwNotInCoaTransmittalSearch();
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
                return Html::input('text', '', $model->id, ['class' => 'transmittal_id']);
            },
            'hidden' => true

        ],
        'transmittal_number',


    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $viewDataProvider,
        'filterModel' => $viewSearchModel,
        'columns' => $viewColumn,
        'pjax' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Transmittals',
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
    </div>
    <table class="table table-striped" id="transaction_table" style="background-color: white;">
        <thead>
            <th>Transmittal Number</th>
        </thead>
        <tbody>
            <?php

            foreach ($items as $itm) {
                echo "<tr>
                    <td style='display:none;'>
                        <input type='text' name='items[$itemRow][item_id]' value='{$itm['item_id']}'>
                        <input type='text' name='items[$itemRow][transmittal_id]' value='{$itm['transmittal_id']}'>
                    </td>
                    <td>{$itm['transmittal_number']}</td>
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
    function remove(i) {
        i.closest("tr").remove()
    }

    function addItem(ths) {
        let source = $(ths).closest('tr')
        let clone = source.clone(true)
        clone.find('.add_btn').closest('td').remove()
        clone.find('.transmittal_id').prop('name', 'items[][transmittal_id]');
        let row = `<td><button id='remove' class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td>`
        clone.append(row)
        $('#transaction_table tbody').append(clone);
    }
</script>


<?php
SweetAlertAsset::register($this);
$script = <<< JS
   

   $(document).ready(()=>{
        $("#PoTransmittalToCoa").on("beforeSubmit", function (event) {
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