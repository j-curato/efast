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

    'par_number',

    [
        'label' => 'Action',
        'format' => 'raw',
        'hiddenFromExport' => true,
        'value' => function ($model) {
            return MyHelper::gridDefaultAction($model->id, 'lrgModal');
        }
    ],

];
?>
<div class="par-index">


    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i>  Create PAR/PC', ['create'], ['class' => 'btn btn-success lrgModal']) ?>

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