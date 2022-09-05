<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PpmpNonCse */

$this->title = 'Update Ppmp Non Cse: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ppmp Non Cses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ppmp-non-cse-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>