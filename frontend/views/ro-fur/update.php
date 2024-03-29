<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoFur */

$this->title = 'Update Ro Fur: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Furs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ro-fur-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
