<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DepreciationSchedule */

$this->title = 'Update Depreciation Schedule: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Depreciation Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="depreciation-schedule-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
