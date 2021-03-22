<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RaoudsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Raouds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raouds-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Raouds', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'record_allotment_id',
            'process_ors_id',
            'serial_number',
            'reporting_period',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
