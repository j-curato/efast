<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubTrialBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Trial Balances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-trial-balance-index">


    <p>
        <?= Html::a('Create Sub Trial Balance', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Sub Trial Balance'
        ],
        'columns' => [

            'reporting_period',
            [
                'label'=>'Book',
                'attribute'=>'book_id',
                'value'=>'book.name'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>