<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashFlowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Flows';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-flow-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cash Flow', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'major_cashflow',
            'sub_cashflow1',
            'sub_cashflow2',
            'specific_cashflow',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
