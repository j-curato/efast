<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReportType */

$this->title = 'Create Report Type';
$this->params['breadcrumbs'][] = ['label' => 'Report Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-type-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
