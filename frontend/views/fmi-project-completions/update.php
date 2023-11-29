<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiProjectCompletions */

$this->title = 'Update Fmi Project Completions: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Project Completions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-project-completions-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
