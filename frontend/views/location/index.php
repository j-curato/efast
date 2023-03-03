<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-index">


    <p>
        <?= Html::a('Create Location', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Locations'
        ],
        'columns' => [

            'location',
            [
                'attribute' => 'is_nc',
                'value' => function ($model) {

                    return $model->is_nc ? 'NC' : 'Office';
                }
            ],
            [
                'attribute' => 'fk_division_id',
                'value' => function ($model) {
                    return $model->divisions->division;
                }
            ],
            [
                'attribute' => 'fk_office_id',
                'value' => function ($model) {
                    return $model->office->office_name;
                }
            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class])
?>