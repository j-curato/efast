<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DepreciationSchedule */

$this->title = 'Create Depreciation Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Depreciation Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="depreciation-schedule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
