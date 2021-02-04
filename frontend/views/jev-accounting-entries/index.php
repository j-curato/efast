<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevAccountingEntriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jev Accounting Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-accounting-entries-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Jev Accounting Entries', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'jev_preparation_id',
            'chart_of_account_id',
            'debit',
            'credit',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
