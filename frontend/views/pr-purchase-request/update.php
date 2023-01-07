<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseRequest */

$this->title = 'Update Pr Purchase Request: ' . $model->pr_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Purchase Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pr_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-purchase-request-update">

    <?php
    $err = '';

    if (!empty($error))
        $err = $error;
    ?>
    <?= $this->render('_form', [
        'model' => $model,
        'error' => $err,
        'action' => $action,
        'items' => $items,
        'allotment_items' => $allotment_items,
    ]) ?>

</div>