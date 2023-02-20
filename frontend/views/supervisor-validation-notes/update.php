<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupervisorValidationNotes */

$this->title = 'Update Supervisor Validation Notes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Supervisor Validation Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supervisor-validation-notes-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>