<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Iirup */

$this->title = 'Update IIRUP: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Iirups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="iirup-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>