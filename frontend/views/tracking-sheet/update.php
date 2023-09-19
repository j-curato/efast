<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrackingSheet */

$this->title = 'Update Tracking Sheet: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tracking Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tracking-sheet-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
