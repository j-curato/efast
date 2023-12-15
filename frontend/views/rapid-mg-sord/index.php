<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RapidMgSordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rapid MG SORDs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rapid-mg-sord-index">
    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of SORDs'
        ],
        'columns' => [
            [
                'attribute' => 'fk_office_id',
                'value' => 'office.office_name'
            ],
            [
                'attribute' => 'fk_mgrfr_id',
                'value' => 'mgrfr.serial_number'
            ],
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