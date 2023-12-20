<?php

use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\PayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FMI Database';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mg-database">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Matching Grant Database',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'pjax' => true,
        'columns' => [
            'serial_number',
            'project_name',
            'project_duration',
            'project_road_length',
            'project_start_date',
            'province_name',
            'municipality_name',
            'barangay_name',
            'purok',
            'batch_name',
            'bank_account_name',
            'bank_account_number',
            'bank_manager',
            'address',
            'branch_name',
            'bank_name',
            [
                'attribute' => 'total_grant_deposit',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'total_deposit_equity',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'total_deposit_other',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'total_liquidated_equity',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'total_liquidated_grant',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'total_liquidated_other',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'grant_beginning_balance',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'equity_beginning_balance',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'attribute' => 'other_beginning_balance',
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            'bank_certification_link',
            'certificate_of_project_link',
            'certificate_of_turnover_link',
            'spcr_link',

        ],
    ]); ?>
</div>