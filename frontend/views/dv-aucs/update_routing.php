<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = 'Update Routing Slip: ' . $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Routing Slips', 'url' => ['tracking-index']];
$this->params['breadcrumbs'][] = ['label' => $model->dv_number, 'url' => ['tracking-view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';




?>
<div class="dv-aucs-update">
    <?= $this->render('_routing_form', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'model' => $model,
        'items' => $items

    ]) ?>
</div>