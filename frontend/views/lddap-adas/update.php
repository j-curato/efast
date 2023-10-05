<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LddapAdas */

$this->title = 'Update LDDAP ADA: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Lddap Adas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lddap-adas-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>