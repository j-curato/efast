<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevBeginningBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jev Beginning Balances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-beginning-balance-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'JEV Beginning Balance'
        ],
        'columns' => [

            'year',
            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => 'book.name'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>