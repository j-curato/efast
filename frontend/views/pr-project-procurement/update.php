<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectProcurement */

$this->title = 'Update  Project Procurement: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Pr Project Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-project-procurement-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
