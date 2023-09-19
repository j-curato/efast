<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BacPositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bac Positions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-position-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create', ['create'],['class'=>'btn btn-success modalButtonCreate']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'BAC Postions'
        ],
        'columns' => [


            'position',
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