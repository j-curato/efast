<?php

use app\components\helpers\MyHelper;
use kartik\export\ExportMenu;
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
        <?= Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) ?>
        <?php


        // if ($_SERVER['REMOTE_ADDR'] !== '210.1.103.26') {

        //     if (Yii::$app->user->can('ro_procurement_admin')) {
        //         echo "<button type='button' class='btn btn-primary'  id ='update_local_purchase_request'>Update Purchase Request</button>";
        //     }
        // }
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 

    if (Yii::$app->user->can('ro_procurement_admin')) {
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
    $cols =
        [
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
                'attribute' => 'is_cancelled',
                'visible' =>  Yii::$app->user->can('ro_procurement_admin') ? true : false
            ],
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'none');
                }
            ]
        ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Purchase Requests'
        ],
        'toolbar' => [


            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $cols,
                    'filename' => "DV",
                    'batchSize' => 10,
                    'stream' => false,
                    'target' => '_popup',

                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,

                    ]

                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'pjax' => true,
        'columns' => $cols,
    ]); ?>
</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 10px;
    }
</style>
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