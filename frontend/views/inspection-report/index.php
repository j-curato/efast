<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InspectionReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inspection Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inspection-report-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Inspection Reports'
        ],
        'pjax' => true,
        'columns' => [
            'office_name',
            'division',
            'ir_number',
            'rfi_number',
            'end_user',
            'purpose',
            'inspector_name',
            'po_number',
            'payee_name',
            'requested_by_name',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]);
                }
            ]
        ],
    ]); ?>


</div>