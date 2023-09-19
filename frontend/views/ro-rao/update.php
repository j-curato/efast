<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoRao */

$this->title = 'Update Ro Rao: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Raos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ro-rao-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
