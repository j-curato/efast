<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MandatoryReserve */

$this->title = 'Update Mandatory Reserve: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mandatory Reserves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mandatory-reserve-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
