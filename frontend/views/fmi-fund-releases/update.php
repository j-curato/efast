<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiFundReleases */

$this->title = 'Update Fmi Fund Releases: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Fund Releases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-fund-releases-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
