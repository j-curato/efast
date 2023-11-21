<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MgLiquidations */

$this->title = 'Update Mg Liquidations: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Mg Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mg-liquidations-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
