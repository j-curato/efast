<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MonthlyLiquidationProgram */

$this->title = 'Create Monthly Liquidation Program';
$this->params['breadcrumbs'][] = ['label' => 'Monthly Liquidation Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monthly-liquidation-program-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
