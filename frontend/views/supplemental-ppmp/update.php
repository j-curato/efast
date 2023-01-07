<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupplementalPpmp */

$this->title = 'Update Supplemental Ppmp: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Supplemental Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supplemental-ppmp-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'action' => $action,
    ]) ?>

</div>