<?php

use app\models\AcicsSearch;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AcicInBank */
/* @var $form yii\widgets\ActiveForm */

$itemRow = 0;
?>

<div class="acic-in-bank-form card container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
    </div>


    <table class="table" id="items_tbl">
        <thead>
            <th>ACIC No.</th>
        </thead>
        <tbody>
            <?php

            foreach ($items as $itm) {
                echo "<tr>
                    <td style='display:none'>
                        <input type ='hidden' name='items[$itemRow][item_id]' value='{$itm['id']}'>
                        <input type ='hidden' name='items[$itemRow][acic_id]' value='{$itm['fk_acic_id']}'>
                    </td>
                    <td>{$itm['serial_number']}</td>
                    <td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>
                </tr>";
                $itemRow++;
            }

            ?>


        </tbody>
    </table>
    <div class="row">

        <div class="col-sm-1 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:10rem']) ?>
        </div>
    </div>
    <div class="form-group">

    </div>
    <?php ActiveForm::end(); ?>

    <?php
    $searchModel = new AcicsSearch();
    $searchModel->notInBank = true;
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->pagination = ['pageSize' => 10];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'ACIC`s'
        ],
        'pjax' => true,
        'columns' => [
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::input('text', 'items[acic_id]', $model->id, ['class' => 'acic_id']);
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
            [
                'label' => 'ACIC No.',
                'attribute' => 'serial_number'
            ]

        ],
    ]); ?>

</div>
<style>
    .acic-in-bank-form {
        padding: 2rem;
    }
</style>
<script>
    let itemRow = <?= $itemRow ?>;

    function AddItem(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.acic_id').attr('name', `items[${itemRow}][acic_id]`)
        clone.find('.add-action').parent().remove()
        clone.append("<td><button type='button' class='remove btn-xs btn-danger' onclick='remove(this)'><i class='fa fa-minus'></i></button></td>")
        $('#items_tbl tbody').append(clone)

        itemRow++
    }

    function remove(ths) {
        $(ths).closest('tr').remove()
    }
    $(document).ready(function() {

    })
</script>