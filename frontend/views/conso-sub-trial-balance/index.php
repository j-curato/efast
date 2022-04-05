<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConsoSubTrialBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conso Sub Trial Balances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conso-sub-trial-balance-index">


    <p>
        <?= Html::a('Create Conso Sub Trial Balance', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => $this->title
        ],
        'columns' => [
            'reporting_period',
            'book_type',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>