<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoRaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RAO';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-rao-index">


    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=ro-rao/create'), 'id' => 'mdModal', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?php
    // echo $this->render('_search', ['model' => $searchModel]); 


    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'RAO'
        ],
        'columns' => [
            'id',
            'reporting_period',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>