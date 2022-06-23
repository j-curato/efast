<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */

$this->title = 'Update Jev Preparation: ' . $model->jev_number;
$this->params['breadcrumbs'][] = ['label' => 'Jev Preparations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->jev_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jev-preparation-update">


    <?= $this->render('_form_new', [
        'model' => $model,
        'entries'=>$entries
  
    ]) ?>

</div>
