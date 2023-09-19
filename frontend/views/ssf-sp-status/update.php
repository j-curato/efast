<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SsfSpStatus */

$this->title = 'Update SSF SP Status: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ssf Sp Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ssf-sp-status-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
