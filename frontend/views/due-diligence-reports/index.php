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
        <?= Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Due Diligence Reports'
        ],
        'columns' => [

            'serial_number',
            'supplier_name',
            'supplier_address:ntext',
            'contact_person',
            //'contact_number',
            //'supplier_registration_period',
            //'supplier_has_business_permit',
            //'supplier_is_bir_registered',
            //'supplier_has_officer_connection',
            //'supplier_is_financial_capable',
            //'supplier_is_authorized_dealer',
            //'supplier_has_quality_material',
            //'supplier_can_comply_specs',
            //'supplier_has_legal_issues',
            //'supplier_nursery:ntext',
            //'comments:ntext',
            //'fk_mgrfr_id',
            //'fk_conducted_by',
            //'fk_noted_by',
            //'fk_office_id',
            //'created_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>