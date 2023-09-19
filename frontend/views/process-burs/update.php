<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessBurs */

$this->title = 'Update Process Burs: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Process Burs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="process-burs-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
