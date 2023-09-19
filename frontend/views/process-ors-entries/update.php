<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrsEntries */

$this->title = 'Update Process Ors Entries: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Process Ors Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="process-ors-entries-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
