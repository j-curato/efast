<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConsoTrialBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conso Trial Balances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conso-trial-balance-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Conso Trial Balance', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>'primary',
            'heading'=>'Conso Trial Balance'
        ],
        'columns' => [

            'id',
            'reporting_period',
            'entry_type',
            'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
