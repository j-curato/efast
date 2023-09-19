<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyCard */

$this->title = 'Update Property Card: ' . $model->pc_number;
$this->params['breadcrumbs'][] = ['label' => 'Property Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pc_number, 'url' => ['view', 'id' => $model->pc_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-card-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
