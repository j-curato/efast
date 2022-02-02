<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevBeginningBalance */

$this->title = 'Update Jev Beginning Balance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jev Beginning Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jev-beginning-balance-update">


    <?= $this->render('_form', [
        'model' => $model,
        'entries' => $entries
    ]) ?>

</div>