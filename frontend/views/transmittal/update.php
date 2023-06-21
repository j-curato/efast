<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = 'Update Transmittal: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transmittal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items'=>$items
    ]) ?>

</div>