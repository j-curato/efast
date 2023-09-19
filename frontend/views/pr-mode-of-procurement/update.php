<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrModeOfProcurement */

$this->title = 'Update Pr Mode Of Procurement: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Mode Of Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-mode-of-procurement-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
