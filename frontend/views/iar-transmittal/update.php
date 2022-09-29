<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IarTransmittal */

$this->title = 'Update IAR # ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Iar Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="iar-transmittal-update">


    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'items' => $items,
        'action' => $action,
    ]) ?>

</div>