<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LddapAdasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lddap Adas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lddap-adas-index">




    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'LDDAP-ADA`s'
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