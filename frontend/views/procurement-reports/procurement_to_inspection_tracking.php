<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Procurement to Inspection Tracking';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payee-index">
    <?= $this->render('_procurement_to_inspection_tracking_export_form') ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Tracking',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            'office_name',
            'division',
            'pr_number',
            'pr_date',
            'purpose',
            'stock_name',
            'specification',
            'pr_is_cancelled',
            'quantity',
            'unit_cost',
            'rfq_number',
            'rfq_date',
            'rfq_deadline',
            'rfq_is_cancelled',
            'aoq_number',
            'aoq_is_cancelled',
            'payee_name',
            'bidAmount',
            'bidGrossAmount',
            'po_number',
            'po_is_cancelled',
            'poTransmittalNumber',
            'poTransmittalDate',
            'rfi_number',
            'date',
            'inspection_from',
            'inspection_to',
            'inspected_quantity',
            'ir_number',
            'iar_number',
            'iarTransmittalNumber',
            'iarTransmittalDate',
        ],
    ]); ?>



</div>