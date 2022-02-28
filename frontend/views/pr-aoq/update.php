<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */

$this->title = 'Update Pr Aoq: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Aoqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-aoq-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'aoq_entries' => !empty($aoq_entries) ? $aoq_entries : []
    ]) ?>

</div>