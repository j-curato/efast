<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrStockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-stock-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pr Stock', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'Stocks'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'stock',
            'bac_code',
            'unit_of_measure_id',
            'amount',
            //'chart_of_account_id',
            //'created_at',

            [
                'class' => 'kartik\grid\ActionColumn',
                'deleteOptions'=>['style'=>'display:none']
        ],
        ],
    ]); ?>


</div>
