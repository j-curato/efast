<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OtherPropertyDetails */

$this->title = 'Update Other Property Details: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Other Property Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="other-property-details-update">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>