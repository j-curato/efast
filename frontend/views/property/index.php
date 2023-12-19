<?php

use app\models\PropertyArticles;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Properties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">

    <p>
        <?= Yii::$app->user->can('create_property') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success lrgModal']) : '' ?>
    </p>

    <?php
    $cols =  [

        'property_number',
        [
            'attribute' => 'fk_office_id',
            'value' => 'office.office_name'
        ],
        [
            'attribute' => 'article',
            'value' => function ($model) {
                $article = !empty($model->fk_property_article_id) ?
                    PropertyArticles::findOne($model->fk_property_article_id)->article_name
                    : $model->article;
                return   $article;
            }
        ],
        [
            'attribute' => 'description',
            'value' => function ($model) {
                $specs = preg_replace('#\[n\]#', "\n", $model->description);
                return   $specs;
            }
        ],

        [
            'label' => 'SSF/NON-SSF',
            'attribute' => 'is_ssf',
            'value' => function ($model) {
                $is_ssf = [
                    '0' => 'Non-SSF',
                    '1' => 'SSF',
                ];
                return $is_ssf[$model->is_ssf];
            }
        ],
        [
            'label' => 'Unit of Measure',
            'attribute' => 'unit_of_measure_id',
            'value' => function ($model) {
                $unit_of_measure = '';
                if (!empty($model->unitOfMeasure->unit_of_measure)) {

                    $unit_of_measure = $model->unitOfMeasure->unit_of_measure;
                }
                return $unit_of_measure;
            }
        ],
        [
            'attribute' => 'acquisition_amount',
            'format' => ['decimal', 2]
        ],
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                $updateBtn = Yii::$app->user->can('update_property') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'lrgModal']) : '';
                return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
            }
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GRIDVIEW::TYPE_PRIMARY,
            'heading' => 'Property',
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'pjax' => true,
        'toolbar' => [


            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $cols,
                    'filename' => "Property",

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
        'columns' => $cols
    ]); ?>


</div>
<style>
    /* .grid-view td {
        white-space: normal;
        width: 2rem;
    } */
</style>
<?php
 
?>