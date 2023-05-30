<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Accics */

$this->title = 'Update ACCIC`s: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Accics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="accics-update">


    <?= $this->render('_form', [
        'model' => $model,
        'cashItems' => $cashItems
    ]) ?>

</div>