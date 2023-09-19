<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Property */

$this->title = 'Update Property: ' . $model->property_number;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->property_number, 'url' => ['view', 'id' => $model->property_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
