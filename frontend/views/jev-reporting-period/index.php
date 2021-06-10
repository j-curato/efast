<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevReportingPeriodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jev Reporting Periods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-reporting-period-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Jev Reporting Period', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            'is_disabled',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
