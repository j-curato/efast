<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiLguLiquidations */

$this->title = 'Update  LGU Liquidations: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => ' LGU Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-lgu-liquidations-update">

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>