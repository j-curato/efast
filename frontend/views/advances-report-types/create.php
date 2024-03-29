<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AdvancesReportType */

$this->title = 'Create Advances Report Type';
$this->params['breadcrumbs'][] = ['label' => 'Advances Report Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advances-report-type-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
