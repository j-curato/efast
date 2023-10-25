<?php

use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions Tracking';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Transactions Tracking'
        ],
        'columns' => [
            'transactionNum',
            'transactionDate',
            'responsibilityCenter',
            'payee',
            'dvStatus',
            'orsNum',
            'dv_number',
            'checkNum',
            'adaNum',
            'cashIsCancelled',
            'acicNum',
            'acicInBankNum',
            'acicInBankDate',
        ],
    ]); ?>


</div>