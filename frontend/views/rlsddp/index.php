<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RlsddpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RLSDDPs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rlsddp-index">
    <p>
        <?= Yii::$app->user->can('create_rlsddp') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RLSDDPs'
        ],
        'pjax' => true,
        'columns' => [
            'office_name',
            'serial_number',
            'date',
            'status',
            'accountable_officer',
            'supervisor',
            'circumstances',
            'blottered',
            'police_station',
            'blotter_date',


            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {

                    $updateBtn = Yii::$app->user->can('update_rlsddp') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>