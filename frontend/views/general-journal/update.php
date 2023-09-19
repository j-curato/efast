<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GeneralJournal */

$month = DateTime::createFromFormat('Y-m',$model->reporting_period)->format('F Y');
$this->title = 'Update General Journal '.$model->book->name.' As of '.$month ;
$this->params['breadcrumbs'][] = ['label' => 'General Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="general-journal-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
