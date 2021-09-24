<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReportType */

$this->title = 'Update Report Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Report Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="report-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
