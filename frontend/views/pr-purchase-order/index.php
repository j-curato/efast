<?php

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
        <?= Html::a('Create Purchase Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Purchase Order'
        ],
        'pjax' => true,
        'columns' => [

            'po_number',
            [
                'label' => 'Contract Type',
                'attribute' => 'fk_contract_type_id',
                'value' => 'contractType.contract_name'
            ],
            [
                'attribute' => 'fk_mode_of_procurement_id',
                'value' => 'modeOfProcurement.mode_name'
            ],
            [
                'attribute' => 'fk_pr_aoq_id',
                'value' => 'aoq.aoq_number'
            ],
            'created_at',
            //'place_of_delivery:ntext',
            //'delivery_date',
            //'payment_term',
            //'fk_auth_official',
            //'fk_accounting_unit',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
