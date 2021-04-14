<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashRecievedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Recieveds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-recieved-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cash Recieved', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'document_recieved_id',
            'book_id',
            'mfo_pap_code_id',
            'date',
            //'reporting_period',
            //'nca_no',
            //'nta_no',
            //'nft_no',
            //'purpose',
            //'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
