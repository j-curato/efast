<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyStatus */

$this->title = 'Update Property Status: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Property Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-status-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
