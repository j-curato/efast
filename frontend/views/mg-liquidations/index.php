<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MgLiquidationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mg Liquidations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mg-liquidations-index">


    <p>
        <?= Yii::$app->user->can('create_mg_liquidation') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'MG Liquidations'
        ],
        'columns' => [
            'serial_number',
            'reporting_period',
            'fk_mgrfr_id',
            'created_at',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_mg_liquidation') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>