<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AcicInBank */

$this->title = 'Update Acic In Bank: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Acic In Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="acic-in-bank-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>