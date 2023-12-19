<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashReceivedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Receive';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-received-index">


    <p>
        <?= Yii::$app->user->can('create_cash_receive') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Cash Receives'
        ],
        'export' => false,
        'columns' => [

            'date',
            'reporting_period',
            [
                'label' => 'Document Received',
                'attribute' => 'documentRecieved.name'
            ],
            [
                'label' => "Book",
                'attribute' => 'book.name'
            ],


            'nca_no',
            'nta_no',
            'nft_no',
            'purpose',
            // 'mfo_pap_code_id',
            [
                'label' => 'Amount',
                'attribute' => 'amount',
                'format' => ['decimal', 2],
            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_cash_receive') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>

    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
        }
    </style>
</div>

<?php


?>