<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LiquidationReportingPeriodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Liquidation Reporting Periods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-reporting-period-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Liquidation Reporting Period', ['create'], ['class' => 'btn btn-success']) ?>
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
            'is_locked',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
