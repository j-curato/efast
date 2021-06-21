<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OtherRecieptsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Other Reciepts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="other-reciepts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Other Reciepts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'report',
            'province',
            'fund_source:ntext',
            'advance_type',
            //'object_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
