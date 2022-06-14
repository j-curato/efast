<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */

$this->title = 'Update Ro Liquidation Report: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Liquidation Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ro-liquidation-report-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'entries' => $entries,
        'refund_items' => $refund_items
    ]) ?>

</div>