<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */

$this->title = 'Update RFQ: ' . $model->rfq_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Rfqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rfq_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-rfq-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items
    ]) ?>

</div>