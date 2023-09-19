<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */

$this->title = 'Create  Liquidation Report';
$this->params['breadcrumbs'][] = ['label' => 'Ro Liquidation Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-liquidation-report-create">


    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'entries' => [],
        'refund_items' => []
    ]) ?>

</div>