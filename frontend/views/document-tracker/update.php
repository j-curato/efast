<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentTracker */

$this->title = 'Update Document Tracker: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Document Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="document-tracker-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
