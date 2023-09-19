<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BudgetEntriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budget Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-entries-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Budget Entries', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'chart_of_account_id',
            'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
