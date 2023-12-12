<?php

use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\PayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MG Database';
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
        'columns' => [
            'office_name',
            'province_name',
            'municipality_name',
            'barangay_name',
            'organization_name',
            'purok',
            'authorized_personnel',
            'contact_number',
            'saving_account_number',
            'email_address',
            'investment_type',

            [
                'attribute' => 'investment_description',
                'contentOptions' => ['style' => 'max-width: 30em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'],
            ],
            'project_consultant',
            [
                'attribute' => 'project_objective',
                'contentOptions' => ['style' => 'max-width: 30em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'],
            ],
            'project_beneficiary',
            'matching_grant_amount',
            'equity_amount',
            'bank_manager',
            'address',
            'bank_name',
            [
                'attribute' => 'total_deposit_equity',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'total_deposit_grant',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'total_deposit_other_amount',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'total_liquidation_grant',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'total_liquidation_equity',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'total_liquidation_other_amount',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'balance_equity',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'balance_grant',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'balance_other_amount',
                'format' => ['decimal', 2]
            ],
            'notification_to_pay_count',
            'due_diligence_report_count',

        ],
    ]); ?>
</div>