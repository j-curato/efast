<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrRfqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "RFQ's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-rfq-index">


    <p>
        <?= Html::a('Create Pr Rfq', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>"RFQ's"
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'rfq_number',
            'pr_purchase_request_id',
            '_date',
            // 'rbac_composition_id',
            //'employee_id',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
