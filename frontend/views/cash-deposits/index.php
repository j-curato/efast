<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashDepositsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Deposits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-deposits-index">


    <p>
        <?= Yii::$app->user->can('create_rapid_mg_cash_deposits') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type'  => 'primary',
            'heading' => 'Cash Deposits'
        ],
        'columns' => [

            'fk_mgrfr_id',
            'serial_number',
            'reporting_period',
            'date',
            'particular:ntext',
            'matching_grant_amount',
            'equity_amount',
            'other_amount',

        
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_rapid_mg_cash_deposits') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>