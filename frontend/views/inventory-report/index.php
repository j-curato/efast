<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InventoryReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inventory Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Inventory Report', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'heading'=>'Inventory Reports',
            'type'=>Gridview::TYPE_PRIMARY
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
