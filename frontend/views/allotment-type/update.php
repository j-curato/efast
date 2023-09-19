<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AllotmentType */

$this->title = 'Update Allotment Type: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Allotment Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allotment-type-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
