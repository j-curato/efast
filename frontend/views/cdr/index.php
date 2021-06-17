<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cdrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdr-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cdr', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'serial_number',
            'reporting_period',
            'province',
            'book_name',
            //'report_type',
            //'is_final',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
