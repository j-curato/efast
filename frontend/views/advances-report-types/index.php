<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdvancesReportTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advances Report Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advances-report-type-index">


    <p>
        <?= Html::a('Create Advances Report Type', ['create'], ['class' => 'btn btn-success mdModal']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Advances Report Types'
        ],
        'columns' => [
            'name',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id,);
                }
            ],
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>