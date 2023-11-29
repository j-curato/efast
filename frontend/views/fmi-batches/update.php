<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBatches */

$this->title = 'Update Fmi Batches: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => ' Batches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-batches-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>