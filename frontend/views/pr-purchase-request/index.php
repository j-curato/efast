<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrPurchaseRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Purchase Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-purchase-request-index">


    <p>
        <?= Html::a('Create  Purchase Request', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Purchase Requests'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'pr_number',
            'date',
            'book_id',
            'pr_project_procurement_id',
            //'purpose:ntext',
            //'requested_by_id',
            //'approved_by_id',
            //'created_at',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>