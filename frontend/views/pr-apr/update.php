<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrApr */

$this->title = 'Update Pr Apr: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Aprs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-apr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
