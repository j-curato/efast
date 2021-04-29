<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assignatory */

$this->title = 'Update Assignatory: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Assignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="assignatory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
