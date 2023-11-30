<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiProjectCompletions */

$this->title = 'Update  Project Completions: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => ' Project Completions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-project-completions-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>