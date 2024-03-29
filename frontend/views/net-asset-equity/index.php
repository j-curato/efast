<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NetAssetEquitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Net Asset Equities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="net-asset-equity-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Net Asset Equity', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'group',
            'specific_change',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
