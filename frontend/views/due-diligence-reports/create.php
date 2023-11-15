<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DueDiligenceReports */

$this->title = 'Create Due Diligence Reports';
$this->params['breadcrumbs'][] = ['label' => 'Due Diligence Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="due-diligence-reports-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>