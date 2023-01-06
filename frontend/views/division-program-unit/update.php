<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DivisionProgramUnit */

$this->title = 'Update Division Program Unit: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Division Program Units', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="division-program-unit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
