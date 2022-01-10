<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrStock */

$this->title = 'Update  Stock: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-stock-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>