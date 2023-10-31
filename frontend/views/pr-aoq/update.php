<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */

$this->title = 'Update Pr Aoq: ' . $model->aoq_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Aoqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->aoq_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-aoq-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items
    ]) ?>

</div>