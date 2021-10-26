<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Par */

$this->title = 'Update Par: ' . $model->property_number;
$this->params['breadcrumbs'][] = ['label' => 'Pars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->property_number, 'url' => ['view', 'id' => $model->property_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="par-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
