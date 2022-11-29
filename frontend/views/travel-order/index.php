<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TravelOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Travel Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="travel-order-index">


    <p>
        <?= Html::a('Create Travel Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Travel Orders'
        ],
        'columns' => [

            'id',
            'date',
            'destination:ntext',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>