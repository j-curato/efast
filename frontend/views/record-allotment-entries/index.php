<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\recordAllotmentEntriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Record Allotment Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotment-entries-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Record Allotment Entries', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'record_allotment_id',
            'chart_of_account_id',
            'amount',
            'lvl',
            //'object_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
