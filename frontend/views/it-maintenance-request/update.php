<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItMaintenanceRequest */

$this->title = 'Update IT Maintenance Request: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'It Maintenance Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="it-maintenance-request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>