<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiFundReleases */

$this->title = 'Create Fmi Fund Releases';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Fund Releases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-fund-releases-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
