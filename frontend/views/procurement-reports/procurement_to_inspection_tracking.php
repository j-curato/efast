<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;

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
            [
                'attribute' => 'rfq_number',
                'format' => 'raw',
                'value' => function ($model) {
                    return !empty($model->rfq_number) ? Html::a($model->rfq_number, Url::to(["pr-rfq/view", 'id' => $model->rfq_id])) : '';
                }
            ],
            'rfq_date',
            'rfq_deadline',
            'rfq_is_cancelled',
            [
                'attribute' => 'aoq_number',
                'format' => 'raw',
                'value' => function ($model) {
                    return !empty($model->aoq_number) ? Html::a($model->aoq_number, Url::to(["pr-aoq/view", 'id' => $model->aoq_id])) : '';
                }
            ],
            'aoq_is_cancelled',
            'payee_name',
            'bidAmount',
            'bidGrossAmount',
            [
                'attribute' => 'po_number',
                'format' => 'raw',
                'value' => function ($model) {
                    return !empty($model->po_number) ? Html::a($model->po_number, Url::to(["pr-purchase-order/view", 'id' => $model->purchase_order_id])) : '';
                }
            ],
            'po_is_cancelled',
            'poTransmittalNumber',
            'poTransmittalDate',
            [
                'attribute' => 'rfi_number',
                'format' => 'raw',
                'value' => function ($model) {
                    return !empty($model->rfi_number) ? Html::a($model->rfi_number, Url::to(["request-for-inspection/view", 'id' => $model->request_for_inspection_id])) : '';
                }
            ],
            'date',
            'inspection_from',
            'inspection_to',
            'inspected_quantity',

            [
                'attribute' => 'ir_number',
                'format' => 'raw',
                'value' => function ($model) {

                    return !empty($model->ir_number) ? Html::a($model->ir_number, Url::to(["inspection-report/view", 'id' => $model->inspection_report_id])) : '';
                }
            ],
            [
                'attribute' => 'iar_number',
                'format' => 'raw',
                'value' => function ($model) {

                    return !empty($model->iar_number) ? Html::a($model->iar_number, Url::to(["iar/view", 'id' => $model->iar_id])) : '';
                }
            ],
            'iarTransmittalNumber',
            'iarTransmittalDate',
        ],
    ]); ?>



</div>