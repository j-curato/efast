<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LiquidationReportingPeriod */

$this->title = 'Create Liquidation Reporting Period';
$this->params['breadcrumbs'][] = ['label' => 'Liquidation Reporting Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-reporting-period-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
