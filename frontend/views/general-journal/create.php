<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GeneralJournal */

$this->title = 'Create General Journal';
$this->params['breadcrumbs'][] = ['label' => 'General Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-journal-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>