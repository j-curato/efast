<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */

$this->title = 'Update Record Allotments: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Record Allotments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="record-allotments-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>
