<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvTransactionTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dv Transaction Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-transaction-type-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Dv Transaction Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'create_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
