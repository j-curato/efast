<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TripTicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trip Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-ticket-index">


    <p>
        <?= Yii::$app->user->can('create_trip_ticket') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Trip Ticket'
        ],
        'columns' => [
            'serial_no',
            'date',
            [
                'attribute' => 'driver',
                'value' => function ($model) {

                    return $model->carDriver->f_name . ' ' . $model->carDriver->m_name[0] . '. ' . $model->carDriver->l_name;
                }
            ],
            'purpose',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {


                    $updateBtn = Yii::$app->user->can('update_trip_ticket') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],

        ],
    ]); ?>


</div>