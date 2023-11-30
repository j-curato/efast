<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificationToPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notification To Pays';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-to-pay-index">


    <p>
        <?= Yii::$app->user->can('create_rapid_mg_notification_to_pay') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success lrgModal']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => 'Notification to Pay',
            'type' => 'primary'
        ],
        'columns' => [
            'serial_number',
            'date',
            'fk_due_diligence_report_id',
            'matching_grant_amount',
            //'equity_amount',
            //'other_amount',
            //'created_at',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_rapid_mg_notification_to_pay') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'lrgModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>
</div>