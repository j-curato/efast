<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InspectionReport */

$this->title = 'Update Inspection Report: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inspection Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inspection-report-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>