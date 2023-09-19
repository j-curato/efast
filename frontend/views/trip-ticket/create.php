<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TripTicket */

$this->title = 'Create Trip Ticket';
$this->params['breadcrumbs'][] = ['label' => 'Trip Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-ticket-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => [],
    ]) ?>

</div>
