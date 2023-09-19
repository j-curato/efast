<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentTrackerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Document Trackers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-tracker-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Document Tracker', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Document Tracker List'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'date_recieved',
            'document_type',
            'status',
            'document_number',
            'document_date',
            'details:ntext',
            //'responsible_office_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>