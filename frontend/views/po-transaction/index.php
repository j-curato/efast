<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Po Transaction', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'responsibility_center_id',
            'payee:ntext',
            'particular:ntext',
            'amount',
            //'payroll_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
