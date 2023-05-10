<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoCheckRange */

$this->title = 'Update Ro Check Range: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ro-check-range-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
