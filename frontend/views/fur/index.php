<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Furs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fur-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Fur', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            // 'heading' => 'List of Areas',
        ],
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            'province',
            'created_at',

            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => 'display:none'],
                'updateOptions' => ['style' => 'display:none']
            ]
        ],
    ]); ?>


</div>