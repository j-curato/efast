<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Update Transaction: ' . $model->tracking_number;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaction-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'action' => $action,
        'transactionPrItems' => $transactionPrItems
    ]) ?>

</div>