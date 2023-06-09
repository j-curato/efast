<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItMaintenanceRequest */

$this->title = 'Create It Maintenance Request';
$this->params['breadcrumbs'][] = ['label' => 'It Maintenance Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="it-maintenance-request-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
