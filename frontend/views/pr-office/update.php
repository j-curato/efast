<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrOffice */

$this->title = 'Update Pr Office: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Offices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-office-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
