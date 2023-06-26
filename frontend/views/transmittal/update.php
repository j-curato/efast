<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = 'Update Transmittal: ' . $model->transmittal_number;
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transmittal-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items'=>$items
    ]) ?>

</div>