<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PreRepairInspection */

$this->title = 'Update Pre Repair Inspection: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Pre Repair Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pre-repair-inspection-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>