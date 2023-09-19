<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = 'Update Cancelled Check: ' . $model->check_number;
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->check_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="liquidation-update">

    <h3><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_cancelled_form', [
        'model' => $model,

    ]) ?>

</div>