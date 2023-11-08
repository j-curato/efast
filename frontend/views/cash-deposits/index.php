<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashDepositsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Deposits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-deposits-index">


    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'fk_mgrfr_id',
            'serial_number',
            'reporting_period',
            'date',
            'particular:ntext',
            'matching_grant_amount',
            'equity_amount',
            'other_amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>