<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RequestForInspection */

$this->title = 'Update RFI No.: ' . $model->rfi_number;
$this->params['breadcrumbs'][] = ['label' => 'Request For Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rfi_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="request-for-inspection-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>