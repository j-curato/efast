<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SsfSpNum */

$this->title = 'Update SSF SF No.: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Ssf Sp Nums', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ssf-sp-num-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>