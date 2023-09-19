<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LiquidationReportingPeriod */

$this->title = 'Update Liquidation Reporting Period: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Liquidation Reporting Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="liquidation-reporting-period-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
