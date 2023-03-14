<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rlsddp */

$this->title = 'Update RLSDDP: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Rlsddps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rlsddp-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>