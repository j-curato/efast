<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MaintenanceJobRequest */

$this->title = 'Create Maintenance Job Request';
$this->params['breadcrumbs'][] = ['label' => 'Maintenance Job Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maintenance-job-request-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
