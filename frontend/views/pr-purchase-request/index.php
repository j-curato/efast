<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrPurchaseRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Purchase Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-purchase-request-index">


    <p>
        <?= Html::a('Create  Purchase Request', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Purchase Requests'
        ],
        'columns' => [

            'pr_number',
            'date',
            'purpose:ntext',
            'book_id',
            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => 'book.name'
            ],
            [
                'label' => 'Activity/Project',
                'attribute' => 'pr_project_procurement_id',
                'value' => 'projectProcurement.title'
            ],
            [
                'label' => 'Requested By',
                'attribute' => 'requested_by_id',
                'value' => function ($model) {
                    $name = $model->requestedBy->f_name . ' ' . $model->requestedBy->m_name[0] . '. ' . $model->requestedBy->l_name;
                    return strtoupper($name);
                }
            ],
            [
                'label' => 'Approved By',
                'attribute' => 'approved_by_id',
                'value' => function ($model) {
                    $name = $model->approvedBy->f_name . ' ' . $model->approvedBy->m_name[0] . '. ' . $model->approvedBy->l_name;
                    return strtoupper($name);
                }
            ],

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>