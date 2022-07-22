<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php


use aryelds\sweetalert\SweetAlertAsset;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Summary";
$this->params['breadcrumbs'][] = $this->title;
$columns =  [
    'po_number',
    'aoq_number',
    'rfq_number',
    'pr_number',
    'payee',
    'purpose',
    [
        'label' => 'Actions',
        'format' => 'raw',
        'value' => function ($model) {
            // $adjust = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/re-align&id=$model->id";
            // $view = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/view&id=$model->id";
            return ' ' . Html::a('PO', ['pr-purchase-order/view', 'id' => $model->po_id], ['class' => 'btn-xs btn-primary'])
                . ' ' . Html::a('AOQ', ['pr-aoq/view', 'id' => $model->aoq_id], ['class' => 'btn-xs btn-success'])
                . ' ' . Html::a('RFQ', ['pr-rfq/view', 'id' => $model->rfq_id], ['class' => 'btn-xs btn-warning'])
                . ' ' . Html::a('PR', ['pr-purchase-request/view', 'id' => $model->pr_id], ['class' => 'btn-xs btn-info']);
        },
        'hiddenFromExport' => true
    ],

];
?>
<div class="summary-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Summary',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'pjax' => true,
        'columns' => $columns
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>