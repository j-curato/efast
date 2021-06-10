<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevReportingPeriod */

$this->title = 'Create Jev Reporting Period';
$this->params['breadcrumbs'][] = ['label' => 'Jev Reporting Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-reporting-period-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
