<?php

use app\models\Books;
use app\models\VwRadaiFormLddapAdas;
use app\models\VwRadaiFormLddapAdasSearch;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Radai */
/* @var $form yii\widgets\ActiveForm */

$itemRow = 0;
?>

<div class="radai-form panel panel-default">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-2">
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
                    'autoclose' => true,
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months'
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
    <table class="table table-stripe" id="items_tbl">
        <thead>
            <th>LDDAP No.</th>
            <th>ACIC No.</th>
            <th>Check No.</th>
            <th>Check Date</th>
            <th>Mode of Payment</th>
        </thead>
        <tbody>
            <?php
            // 
            // lddap_ada_id
            // 
            // 
            // 
            // 
            // 
            foreach ($items as $itm) {
                echo "<tr>
                    <td style='display:none'>
                        <input type='text' name='items[$itemRow][item_id]' value='{$itm['item_id']}'>
                        <input type='text' name='items[$itemRow][lddap_ada_id]' value='{$itm['lddap_ada_id']}'>
                    </td>
                    <td>{$itm['lddap_no']}</td>
                    <td>{$itm['acic_no']}</td>
                    <td>{$itm['check_or_ada_no']}</td>
                    <td>{$itm['issuance_date']}</td>
                    <td>{$itm['mode_of_payment_name']}</td>
                    <td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>
                </tr>";
                $itemRow++;
            } ?>
        </tbody>
    </table>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php
    $searchModel = new VwRadaiFormLddapAdasSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->pagination = ['pageSize' => 10];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RADAIs'
        ],
        'columns' => [
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::input('text', '', $model->id, ['class' => 'lddap_ada_id']);
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
            'lddap_no',
            'acic_no',
            'check_or_ada_no',
            'issuance_date',
            'mode_of_payment_name',
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .radai-form {
        padding: 2rem;
    }
</style>
<script>
    let itemRow = <?= $itemRow ?>;

    function remove(ths) {
        $(ths).closest('tr').remove()
    }

    function AddItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.lddap_ada_id').attr('name', `items[${itemRow}][lddap_ada_id]`)
        clone.find('.add-action').parent().remove()
        clone.append("<td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>")
        $('#items_tbl tbody').append(clone)

        itemRow++
        GetCashItemsTotal()
    }
    $(document).ready(function() {


    })
</script>