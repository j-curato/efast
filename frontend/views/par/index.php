<?php

use app\components\helpers\MyHelper;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PAR';
$this->params['breadcrumbs'][] = $this->title;
$columns = [
    'office_name',
    'par_number',
    'property_number',
    'par_date',
    'acquisition_date',
    'article',
    'description',
    'serial_number',
    'location',
    'unit_of_measure',
    'rcv_by',
    'act_usr',
    'isd_by',
    'is_unserviceable',
    [
        'attribute' => 'acquisition_amount',
        'format' => ['decimal', 2]
    ],
    [
        'label' => 'Action',
        'format' => 'raw',
        'hiddenFromExport' => true,
        'value' => function ($model) {
            // return MyHelper::gridDefaultAction($model->id, 'lrgModal');
            return Html::a('<i class="fa fa-eye"></i>',['view','id'=>$model->id],['class'=>'btn']);
        }
    ],

];
?>
<div class="par-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i>  Create PAR/PC', ['create'], ['class' => 'btn btn-success lrgModal']) ?>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'PAR'
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'pjax'=>true,
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                    'filename' => "DV",
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
        'columns' => $columns
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        max-width: 250rem;
        padding: 0;
    }
</style>
<?php

$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);
?>