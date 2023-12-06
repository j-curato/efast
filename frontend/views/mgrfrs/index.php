<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MgrfrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MG RFRs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mgrfrs-index">

    <p>
        <?= Yii::$app->user->can('create_rapid_mg_mgrfr') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of MGRFRs'
        ],
        'columns' => [
            [
                'attribute' => 'fk_office_id',
                'value' => 'office.office_name'
            ],
            'serial_number',

            ['attribute' => 'fk_province_id', 'value' => 'province.province_name'],
            ['attribute' => 'fk_municipality_id', 'value' => 'municipality.municipality_name'],
            ['attribute' => 'fk_barangay_id', 'value' => 'barangay.barangay_name'],
            'purok',
            'authorized_personnel',
            'contact_number',
            'email_address:email',
            'investment_type:ntext',
            [
                'attribute' => 'investment_description',
                'contentOptions' => ['style' => 'max-width: 30em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'],
            ],
            'project_consultant:ntext',
            [
                'attribute' => 'project_objective',
                'contentOptions' => ['style' => 'max-width: 30em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'],
            ],
            'project_beneficiary:ntext',
            ['attribute' => 'matching_grant_amount', 'format' => ['decimal', 2]],
            ['attribute' => 'equity_amount', 'format' => ['decimal', 2]],
            //'created_at',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_rapid_mg_mgrfr') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>