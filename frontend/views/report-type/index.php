<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReportTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-type-index">


    <p>
        <?= Html::a('Create Report Type', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Report Types'
        ],
        'columns' => [

            'name',

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
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>