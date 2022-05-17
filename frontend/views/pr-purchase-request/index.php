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

        <?php
        $whitelist = array('127.0.0.1', "::1", '10.20.17.35');

        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

            if (Yii::$app->user->can('super-user')) {
                echo "<button type='button' class='btn btn-primary'  id ='update_local_purchase_request'>Update Purchase Request</button>";
            }
        }
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 

    if (Yii::$app->user->can('super-user')) {
        $actions =   [
            'class' => 'kartik\grid\ActionColumn',
            'deleteOptions' => ['hidden' => true]
        ];
    } else {
        $actions =   [
            'class' => 'kartik\grid\ActionColumn',
            'updateOptions' => ['hidden' => true],
            'deleteOptions' => ['hidden' => true]
        ];
    }
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
            'purpose',
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
            $actions


        ],
    ]); ?>


</div>
<script>
    $(document).ready(function() {

        $('#update_local_purchase_request').click(function() {

            $.ajax({
                type: "POST",
                url: window.location.pathname + '?r=sync-database/update-procurement',
                data: {
                    id: 1
                },
                success: function(data) {

                    console.log(data)
                    if (data == 'success') {
                        // location.reload()
                    }
                }
            })
        })
    })
</script>