<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UnitOfMeasure */

$this->title = 'Update Unit Of Measure: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Unit Of Measures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="unit-of-measure-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
