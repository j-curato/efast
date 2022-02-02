<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Saob */

$this->title = 'Update Saob: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Saobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="saob-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
