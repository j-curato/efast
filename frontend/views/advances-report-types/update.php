<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AdvancesReportType */

$this->title = 'Update Advances Report Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Advances Report Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advances-report-type-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
