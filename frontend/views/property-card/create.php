<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyCard */

$this->title = 'Create Property Card';
$this->params['breadcrumbs'][] = ['label' => 'Property Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-card-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
