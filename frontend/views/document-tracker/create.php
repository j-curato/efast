<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentTracker */

$this->title = 'Create Document Tracker';
$this->params['breadcrumbs'][] = ['label' => 'Document Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-tracker-create">


    <?= $this->render('_form', [
        'model' => $model,
        'link' => $link,
        'complianceLink' => $complianceLink,
        're_office' => $re_office,
    ]) ?>

</div>