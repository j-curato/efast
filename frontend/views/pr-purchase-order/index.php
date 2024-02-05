<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrPurchaseOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchase Orders';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="pr-purchase-order-index">


    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Purchase Order'
        ],
        'pjax' => true,
        'columns' => [

            'po_number',
            'payee_name',
            'purpose',
            'division',
            'office_name',
            'is_cancelled',
            'mode_of_procurement_name',
            'created_at',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'none');
                }
            ]
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 10px;
    }
</style>