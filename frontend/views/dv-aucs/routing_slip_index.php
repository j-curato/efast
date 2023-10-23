<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvAucsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Routing Slip';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-aucs-index">


    <p>
        <?= Yii::$app->user->can('create_routing_slip') ? Html::a('<i class="fa fa-plus"></i> Create', ['create-routing'], ['class' => 'btn btn-success ']) : '' ?>
    </p>

    <?php


    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Routing Slips',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'toolbar' => [
            [
                // 'content' => ExportMenu::widget([
                //     'dataProvider' => $exportDataProvider,
                //     'columns' => $exportColumns,
                //     'filename' => "DV",
                //     'exportConfig' => [
                //         ExportMenu::FORMAT_CSV => false,
                //         ExportMenu::FORMAT_TEXT => false,
                //         ExportMenu::FORMAT_PDF => false,
                //         ExportMenu::FORMAT_HTML => false,
                //         ExportMenu::FORMAT_EXCEL => false,

                //     ]

                // ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'export' => false,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],


            'dv_number',
            'particular',
            [
                'label' => 'Payee',
                'attribute' => 'account_name'
            ],
            [
                'label' => 'Receive Date and Time',
                'attribute' => 'recieved_at',

            ],




            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {

                    $updateBtn = Yii::$app->user->can('update_routing_slip') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update-routing', 'id' => $model->id], ['class' => '']) : '';
                    $view = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/routing-slip-view&id=$model->id";
                    return ' ' . Html::a('', $view, ['class' => ' fa fa-eye'])
                        . ' ' . $updateBtn;
                },
                'hiddenFromExport' => true
            ],
        ],
    ]); ?>



</div>