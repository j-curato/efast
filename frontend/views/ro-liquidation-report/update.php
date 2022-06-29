<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */

$this->title = 'Update Ro Liquidation Report: ' . $model->liquidation_report_number;
$this->params['breadcrumbs'][] = ['label' => 'Ro Liquidation Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->liquidation_report_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ro-liquidation-report-update">


    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'entries' => $entries,
        'refund_items' => $refund_items
    ]) ?>

</div>