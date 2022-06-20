<?php

use app\models\DvAucsEntriesSearch;
use app\models\TrackingSheetIndexSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvAucsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Routing Slip';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-aucs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Dv Aucs', ['create-tracking'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php


    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of DV',
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
                'label' => 'Recive Date and Time',
                'attribute' => 'recieved_at',

            ],




            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $update = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/tracking-update&id=$model->id";
                    $view = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/tracking-view&id=$model->id";
                    return ' ' . Html::a('', $view, ['class' => 'btn-xs btn-primary fa fa-eye'])
                        . ' ' . Html::a('', $update, ['class' => 'btn-xs btn-success fa fa-pencil-square-o']);
                },
                'hiddenFromExport' => true
            ],
        ],
    ]); ?>


    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }
    </style>
</div>