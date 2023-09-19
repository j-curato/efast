<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\recordAllotmentEntries */

$this->title = 'Update Record Allotment Entries: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Record Allotment Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="record-allotment-entries-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
