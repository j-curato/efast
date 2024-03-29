<?php

use app\components\helpers\MyHelper;
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
        <?= Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('RFQ Form', ['blank-view'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => "RFQ's"
        ],
        'pjax' => true,
        'columns' => [


            [
                'attribute' => 'fk_office_id',
                'value' => 'office.office_name'
            ],
            'rfq_number',
            [
                'attribute' => 'pr_purchase_request_id',
                'value' => 'purchaseRequest.pr_number'
            ],
            '_date',
            'deadline',

            [

                'attribute' => 'purpose',
                'value' => 'purchaseRequest.purpose'
            ],
            [

                'attribute' => 'project_title',
                'value' => 'purchaseRequest.projectProcurement.title'
            ],
            [

                'attribute' => 'office_unit',
                'value' => 'purchaseRequest.projectProcurement.office.unit'
            ],
            [
                'attribute' => 'employee_id',
                'value' => 'canvasser.f_name'
            ],
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