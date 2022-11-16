<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TripTicket */

$this->title = 'Update Trip Ticket: ' . $model->serial_no;
$this->params['breadcrumbs'][] = ['label' => 'Trip Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trip-ticket-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>