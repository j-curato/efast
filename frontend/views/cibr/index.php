<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CibrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cibrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cibr-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cibr', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            'province',
            'book_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
