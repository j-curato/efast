<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rci */

$this->title = 'Update Rci: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Rcis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rci-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items
    ]) ?>

</div>