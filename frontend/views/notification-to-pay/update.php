<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationToPay */

$this->title = 'Update Notification To Pay: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Notification To Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notification-to-pay-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
