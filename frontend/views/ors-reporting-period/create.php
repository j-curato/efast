<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrsReportingPeriod */

$this->title = 'Create Ors Reporting Period';
$this->params['breadcrumbs'][] = ['label' => 'Ors Reporting Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ors-reporting-period-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
