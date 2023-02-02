<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrPurchaseRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Purchase Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-purchase-request-index">


    <p>
        <?= Html::a('Create  Purchase Request', ['create'], ['class' => 'btn btn-success']) ?>
        <?php


        if ($_SERVER['REMOTE_ADDR'] !== '210.1.103.26') {

            if (Yii::$app->user->can('super-user')) {
                echo "<button type='button' class='btn btn-primary'  id ='update_local_purchase_request'>Update Purchase Request</button>";
            }
        }
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 

    if (Yii::$app->user->can('super-user')) {
        $actions =   [
            'class' => 'kartik\grid\ActionColumn',
            'deleteOptions' => ['hidden' => true]
        ];
    } else {
        $actions =   [
            'class' => 'kartik\grid\ActionColumn',
            'updateOptions' => ['hidden' => true],
            'deleteOptions' => ['hidden' => true]
        ];
    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Purchase Requests'
        ],
        'pjax' => true,
        'columns' => [
            'pr_number',
            'office_name',
            'division',
            'division_program_unit',
            'requested_by',
            'approved_by',
            'book_name',
            'purpose',
            'date',

            [
                'label' => 'Total Cost',
                'attribute' => 'ttlCost',
                'format' => ['decimal', 2]
            ],
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id]);
                }
            ]
        ],
    ]); ?>


</div>
<script>
    $(document).ready(function() {

        $('#update_local_purchase_request').click(function() {

            $.ajax({
                type: "POST",
                url: window.location.pathname + '?r=sync-database/update-procurement',
                data: {
                    id: 1
                },
                success: function(data) {

                    console.log(data)
                    if (data == 'success') {
                        // location.reload()
                    }
                }
            })
        })
    })
</script>