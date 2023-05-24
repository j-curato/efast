<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sliies */

$this->title = 'Update Sliies: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sliies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sliies-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
