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
        <?= Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'MG RFRs'
        ],
        'columns' => [

            'fk_bank_branch_detail_id',
            'fk_municipality_id',
            'fk_barangay_id',
            'fk_office_id',
            'purok',
            'authorized_personnel',
            'contact_number',
            'saving_account_number',
            'email_address:email',
            'investment_type:ntext',
            'investment_description:ntext',
            'project_consultant:ntext',
            'project_objective:ntext',
            'project_beneficiary:ntext',
            'matching_grant_amount',
            'equity_amount',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>