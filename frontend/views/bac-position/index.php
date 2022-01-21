<?php

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
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=bac-position/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>