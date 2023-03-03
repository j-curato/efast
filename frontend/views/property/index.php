<?php

use app\components\helpers\MyHelper;
use app\models\PropertyArticles;
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
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
    </p>
    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD Cash Disbursement</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="/afms/frontend/web/import_formats/Cash_Disbursement and DV Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['property/import'],
                        'method' => 'post',
                        'id' => 'formupload',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    // echo '<input type="file">';
                    echo FileInput::widget([
                        'name' => 'file',
                        // 'options' => ['multiple' => true],
                        'id' => 'fileupload',
                        'pluginOptions' => [
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => true,
                        ]
                    ]);

                    ActiveForm::end();


                    ?>

                </div>
            </div>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
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
        'columns' => [

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
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'lrgModal');
                }
            ],
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 2rem;
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);
?>