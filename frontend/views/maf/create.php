<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */

$this->title = 'Create MAF';
$this->params['breadcrumbs'][] = ['label' => 'MAF', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotments-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>