<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Derecognition */

$this->title = 'Update Derecognition: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Derecognitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="derecognition-update">


    <?= $this->render('_form', [
        'model' => $model,
        'propertyDetails' => $propertyDetails,
    ]) ?>

</div>
