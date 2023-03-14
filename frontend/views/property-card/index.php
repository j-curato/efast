<?php

use app\components\helpers\MyHelper;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertyCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Cards';
$this->params['breadcrumbs'][] = $this->title;
$columns = [
    'pc_number',
    'par_number',
    'property_number',
    'par_date',
    'office_name',
    'is_unserviceable',
    'description',
    'acquisition_date',
    'acquisition_amount',
    'unit_of_measure',
    'article',
    'serial_number',
    'location',
    'rcv_by',
    'act_usr',
    'isd_by',

    [
        'label' => 'Actions',
        'format' => 'raw',
        'value' => function ($model) {
            return  Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id],);
        }
    ],
];
?>
<div class="property-card-index">



    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Property Cards'
        ],
        'columns' => $columns,
        'export' => [
            'fontAwesome' => true
        ],
        'toolbar' => [


            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                    'filename' => "Property Cards",
                    // 'batchSize' => 10,
                    // 'stream' => false,
                    // 'target' => '_popup',

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
        'export' => false,

    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>