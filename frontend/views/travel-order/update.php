<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TravelOrder */

$this->title = 'Update Travel Order: ' . $model->to_number;
$this->params['breadcrumbs'][] = ['label' => 'Travel Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->to_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="travel-order-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>