<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiPhysicalProgress */

$this->title = 'Update Fmi Physical Progress: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Physical Progresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-physical-progress-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>