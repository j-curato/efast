<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DueDiligenceReportsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Due Diligence Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="due-diligence-reports-index">


    <p>
        <?= Yii::$app->user->can('create_rapid_mg_due_diligence_report') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Due Diligence Reports'
        ],
        'columns' => [
            [
                'attribute' => 'fk_office_id',
                'value' => 'office.office_name',
            ],
            'serial_number',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_rapid_mg_due_diligence_report') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>
</div>