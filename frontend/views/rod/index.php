<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rod-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rod', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of ROD'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rod_number',
            'province',

            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => 'display:none;'],
            ],
        ],
    ]); ?>


</div>