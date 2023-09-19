<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rod */

$this->title = 'Update Rod: ' . $model->rod_number;
$this->params['breadcrumbs'][] = ['label' => 'Rods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rod_number, 'url' => ['view', 'id' => $model->rod_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rod-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<style>

    @media print{
        h1{
            display: none;
        }
    }
</style>
