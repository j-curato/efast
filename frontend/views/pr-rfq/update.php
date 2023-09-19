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

    <?php
    $err = '';
    $items = [];
    if (!empty($error)) {
        $err = $error;
    }
    if (!empty($pr_items))
        $items = $pr_items;
    ?>
    <?= $this->render('_form', [
        'model' => $model,
        'error' => $err,
        'pr_items' => $items
    ]) ?>

</div>