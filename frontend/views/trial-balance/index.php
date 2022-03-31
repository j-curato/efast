<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TrialBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trial Balances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trial-balance-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Trial Balance', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Trial Balance'
        ],
        'columns' => [

            'reporting_period',
            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => 'book.name'
            ],
            'entry_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>