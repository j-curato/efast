<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupervisorValidationNotes */

$this->title = 'Create Supervisor Validation Notes';
$this->params['breadcrumbs'][] = ['label' => 'Supervisor Validation Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supervisor-validation-notes-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
