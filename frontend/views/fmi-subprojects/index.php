<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiSubprojectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Subprojects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-subprojects-index">


    <p>
        <?= Yii::$app->user->can('create_fmi_subprojects') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Subprojects',
        ],
        'columns' => [

            [
                'attribute' => 'fk_office_id',
                'value' => 'office.office_name'
            ],
            'serial_number',
            'project_name',
            [
                'attribute' => 'fk_province_id',
                'value' => 'province.province_name',
            ],
            [
                'attribute' => 'fk_municipality_id',
                'value' => 'municipality.municipality_name',
            ],
            [
                'attribute' => 'fk_barangay_id',
                'value' => 'barangay.barangay_name',
            ],
            'purok:ntext',
            'project_duration',
            'project_road_length',
            'project_start_date',
            [
                'attribute' => 'grant_amount',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'equity_amount',
                'format' => ['decimal', 2]
            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_fmi_subprojects') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]

        ],
    ]); ?>


</div>