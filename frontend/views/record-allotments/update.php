<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */

$this->title = 'Update Record Allotments: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Record Allotments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="record-allotments-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
