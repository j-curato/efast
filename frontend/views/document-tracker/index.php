<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentTrackerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Document Trackers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-tracker-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Document Tracker', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date_recieved',
            'document_type',
            'status',
            'document_number',
            //'document_date',
            //'details:ntext',
            //'responsible_office_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
