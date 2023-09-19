<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MonthlyLiquidationProgram */

$this->title = 'Update Monthly Liquidation Program: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Monthly Liquidation Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="monthly-liquidation-program-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
