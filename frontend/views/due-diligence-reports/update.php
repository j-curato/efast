<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DueDiligenceReports */

$this->title = 'Update Due Diligence Reports: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Due Diligence Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="due-diligence-reports-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
