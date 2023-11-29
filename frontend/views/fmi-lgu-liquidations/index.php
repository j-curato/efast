<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiLguLiquidationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LGU Liquidations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-lgu-liquidations-index">

    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of LGU Liquidations'
        ],
        'columns' => [
            'fk_fmi_subproject_id',
            'serial_number',
            'fk_office_id',
            'reporting_period',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>