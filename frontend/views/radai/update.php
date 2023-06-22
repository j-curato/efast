<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Radai */

$this->title = 'Update RADAI: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Radais', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="radai-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>