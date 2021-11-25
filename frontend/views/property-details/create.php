<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyDetails */

$this->title = 'Create Property Details';
$this->params['breadcrumbs'][] = ['label' => 'Property Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
