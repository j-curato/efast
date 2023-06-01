<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AcicInBank */

$this->title = 'Create Acic In Bank';
$this->params['breadcrumbs'][] = ['label' => 'Acic In Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acic-in-bank-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => [],
    ]) ?>

</div>