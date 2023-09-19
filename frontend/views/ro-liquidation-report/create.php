<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoLiquidationReport */

$this->title = 'Create Ro Liquidation Report';
$this->params['breadcrumbs'][] = ['label' => 'Ro Liquidation Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-liquidation-report-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'entries' => [],
        'refund_items' => []
    ]) ?>

</div>