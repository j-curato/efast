<?php

use app\components\helpers\MyHelper;
use app\models\PropertyArticles;
use frontend\components\MyComponent;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
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
        <?= Html::a('Create Property', ['create'], ['class' => 'btn btn-success lrgModal']) ?>
    </p>


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
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
                // return MyComponent::gridDefaultAction($model->id, 'lrgModal');
                return \app\components\helpers\MyHelper::gridDefaultAction($model->id, 'lrgModal');
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
        // 'export' => [
        //     'fontAwesome' => true
        // ],
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
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>