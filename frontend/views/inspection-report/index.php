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


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Inspection Reports'
        ],
        'pjax' => true,
        'columns' => [

            'ir_number',
            'rfi_number',
            'division',
            'unit',
            'po_number',
            'unit_head',
            'inspector',
            'chairperson',
            'property_unit',
            'payee',
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