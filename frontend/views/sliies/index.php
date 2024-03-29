<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SliiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sliies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sliies-index">




    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'SLIIE`s'
        ],
        'pjax'=>true,
        'columns' => [
            'serial_number',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn']);
                }
            ],
        ],
    ]); ?>


</div>