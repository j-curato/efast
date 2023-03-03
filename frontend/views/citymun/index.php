<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CitymunSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'City/Municipality';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="citymun-index">


    <p>
        <?= Html::a('Create City/Municipality', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'Heading' => 'City/Municipality'
        ],
        'columns' => [
            'city_mun',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' =>
                function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);
?>