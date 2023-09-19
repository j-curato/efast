<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PreRepairInspection */

$this->title = 'Create Pre Repair Inspection';
$this->params['breadcrumbs'][] = ['label' => 'Pre Repair Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pre-repair-inspection-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
